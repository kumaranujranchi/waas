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
}
