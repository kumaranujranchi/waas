<?php
/**
 * Consultation Model
 * Handles consultation booking requests and slot management
 */

require_once __DIR__ . '/../classes/Database.php';

class Consultation
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // ===== BOOKING MANAGEMENT =====

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

    // ===== SLOT-BASED AVAILABILITY SYSTEM =====

    /**
     * Add a new time slot for a specific date
     */
    public function addSlot($date, $startTime, $endTime)
    {
        $data = [
            'date' => $date,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'is_booked' => 0
        ];
        return $this->db->insert('consultation_slots', $data);
    }

    /**
     * Get all slots for a specific date
     */
    public function getSlotsByDate($date)
    {
        $sql = "SELECT * FROM consultation_slots WHERE date = ? ORDER BY start_time ASC";
        return $this->db->fetchAll($sql, [$date]);
    }

    /**
     * Get available (unbooked) slots for a specific date
     */
    public function getAvailableSlots($date)
    {
        $sql = "SELECT * FROM consultation_slots 
                WHERE date = ? AND is_booked = 0 
                ORDER BY start_time ASC";
        return $this->db->fetchAll($sql, [$date]);
    }

    /**
     * Get all dates that have at least one available slot (next 7 days)
     */
    public function getAvailableDates()
    {
        $today = date('Y-m-d');
        $nextWeek = date('Y-m-d', strtotime('+7 days'));

        $sql = "SELECT DISTINCT date FROM consultation_slots 
                WHERE date BETWEEN ? AND ? 
                AND is_booked = 0 
                ORDER BY date ASC";

        $results = $this->db->fetchAll($sql, [$today, $nextWeek]);
        return array_column($results, 'date');
    }

    /**
     * Get all slots for the next 7 days (for admin calendar view)
     */
    public function getUpcomingSlots($days = 7)
    {
        $today = date('Y-m-d');
        $endDate = date('Y-m-d', strtotime("+{$days} days"));

        $sql = "SELECT * FROM consultation_slots 
                WHERE date BETWEEN ? AND ? 
                ORDER BY date ASC, start_time ASC";

        return $this->db->fetchAll($sql, [$today, $endDate]);
    }

    /**
     * Book a specific slot
     */
    public function bookSlot($slotId, $bookingId)
    {
        $data = [
            'is_booked' => 1,
            'booking_id' => $bookingId
        ];
        return $this->db->update('consultation_slots', $data, 'id = ?', [$slotId]);
    }

    /**
     * Delete a slot
     */
    public function deleteSlot($id)
    {
        return $this->db->delete('consultation_slots', 'id = ?', [$id]);
    }

    /**
     * Get slot by ID
     */
    public function getSlotById($id)
    {
        $sql = "SELECT * FROM consultation_slots WHERE id = ?";
        return $this->db->fetchOne($sql, [$id]);
    }

    /**
     * Check if a slot is available
     */
    public function isSlotAvailable($slotId)
    {
        $slot = $this->getSlotById($slotId);
        return $slot && $slot['is_booked'] == 0;
    }
}
