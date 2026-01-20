<?php
/**
 * Consultation Model
 * Handles consultation booking requests
 */

require_once __DIR__ . '/../classes/Database.php';

class Consultation
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Create new consultation booking
     */
    public function createBooking($data)
    {
        return $this->db->insert('consultations', $data);
    }

    /**
     * Get booking by ID
     */
    public function getBookingById($id)
    {
        $sql = "SELECT c.*, u.email as user_email 
                FROM consultations c 
                LEFT JOIN users u ON c.user_id = u.id 
                WHERE c.id = ?";

        return $this->db->fetchOne($sql, [$id]);
    }

    /**
     * Get user bookings
     */
    public function getBookingsByUser($userId)
    {
        $sql = "SELECT * FROM consultations 
                WHERE user_id = ? 
                ORDER BY created_at DESC";

        return $this->db->fetchAll($sql, [$userId]);
    }

    /**
     * Get bookings by email (for non-registered users)
     */
    public function getBookingsByEmail($email)
    {
        $sql = "SELECT * FROM consultations 
                WHERE email = ? 
                ORDER BY created_at DESC";

        return $this->db->fetchAll($sql, [$email]);
    }

    /**
     * Update booking status
     */
    public function updateBookingStatus($id, $status, $adminNotes = null)
    {
        $data = ['status' => $status];

        if ($adminNotes) {
            $data['admin_notes'] = $adminNotes;
        }

        return $this->db->update('consultations', $data, 'id = ?', [$id]);
    }

    /**
     * Get all bookings (Admin)
     */
    public function getAllBookings($status = null, $limit = null, $offset = 0)
    {
        $sql = "SELECT c.*, u.full_name as user_name 
                FROM consultations c 
                LEFT JOIN users u ON c.user_id = u.id";

        $params = [];

        if ($status) {
            $sql .= " WHERE c.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY c.created_at DESC";

        if ($limit) {
            $sql .= " LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;
        }

        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Get pending bookings
     */
    public function getPendingBookings()
    {
        return $this->getAllBookings('pending');
    }

    /**
     * Count bookings by status
     */
    public function countBookingsByStatus($status = null)
    {
        $sql = "SELECT COUNT(*) as total FROM consultations";
        $params = [];

        if ($status) {
            $sql .= " WHERE status = ?";
            $params[] = $status;
        }

        $result = $this->db->fetchOne($sql, $params);
        return $result['total'] ?? 0;
    }

    /**
     * Get upcoming consultations (confirmed, within next 7 days)
     */
    public function getUpcomingConsultations()
    {
        $sql = "SELECT * FROM consultations 
                WHERE status = 'confirmed' 
                AND preferred_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) 
                ORDER BY preferred_date ASC, preferred_time ASC";

        return $this->db->fetchAll($sql);
    }

    /**
     * Delete booking
     */
    public function deleteBooking($id)
    {
        return $this->db->delete('consultations', 'id = ?', [$id]);
    }

    // --- Availability System ---

    /**
     * Get availability settings for all days
     */
    public function getAvailabilitySettings()
    {
        $sql = "SELECT * FROM consultation_availability ORDER BY FIELD(day_of_week, 'mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun')";
        return $this->db->fetchAll($sql);
    }

    /**
     * Update availability for a specific day
     */
    public function updateAvailability($day, $isActive, $startTime, $endTime)
    {
        $sql = "INSERT INTO consultation_availability (day_of_week, is_active, start_time, end_time) 
                VALUES (?, ?, ?, ?) 
                ON DUPLICATE KEY UPDATE is_active = VALUES(is_active), start_time = VALUES(start_time), end_time = VALUES(end_time)";
        return $this->db->query($sql, [$day, $isActive, $startTime, $endTime]);
    }

    /**
     * Get blocked dates
     */
    public function getBlockedDates()
    {
        $sql = "SELECT * FROM consultation_blocked_dates ORDER BY date ASC";
        return $this->db->fetchAll($sql);
    }

    /**
     * Add a blocked date
     */
    public function addBlockedDate($date, $reason)
    {
        $sql = "INSERT IGNORE INTO consultation_blocked_dates (date, reason) VALUES (?, ?)";
        return $this->db->query($sql, [$date, $reason]);
    }

    /**
     * delete blocked date
     */
    public function deleteBlockedDate($id)
    {
        return $this->db->delete('consultation_blocked_dates', 'id = ?', [$id]);
    }

    /**
     * Get available time slots for a specific date
     */
    public function getAvailableSlots($date)
    {
        // 1. Check if date is blocked
        $blockedSql = "SELECT COUNT(*) as total FROM consultation_blocked_dates WHERE date = ?";
        $isBlocked = $this->db->fetchOne($blockedSql, [$date]);
        if ($isBlocked['total'] > 0) {
            return []; // Date is blocked
        }

        // 2. Get Day of Week (mon, tue...)
        $dayOfWeek = strtolower(date('D', strtotime($date)));

        // 3. Get Operating Hours
        $availSql = "SELECT * FROM consultation_availability WHERE day_of_week = ? AND is_active = 1";
        $availability = $this->db->fetchOne($availSql, [$dayOfWeek]);

        if (!$availability) {
            return []; // Not a working day
        }

        // 4. Generate Slots (30 min intervals)
        $slots = [];
        $start = strtotime($date . ' ' . $availability['start_time']);
        $end = strtotime($date . ' ' . $availability['end_time']);
        $interval = 30 * 60; // 30 mins

        // 5. Get existing bookings for this date (confirmed or pending)
        $bookingSql = "SELECT preferred_time FROM consultations 
                       WHERE preferred_date = ? AND status IN ('pending', 'confirmed')";
        $existingBookings = $this->db->fetchAll($bookingSql, [$date]);

        $bookedTimes = array_map(function ($b) {
            return date('H:i', strtotime($b['preferred_time']));
        }, $existingBookings);

        // Loop to create slots
        for ($current = $start; $current < $end; $current += $interval) {
            $timeLabel = date('h:i A', $current);
            $timeValue = date('H:i', $current);

            // Check if slot is already booked
            if (!in_array($timeValue, $bookedTimes)) {
                // For today, exclude past times
                if ($date === date('Y-m-d') && $current < time()) {
                    continue;
                }
                $slots[] = ['value' => $timeValue, 'label' => $timeLabel];
            }
        }

        return $slots;
    }
}
