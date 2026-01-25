<?php
require_once __DIR__ . '/../classes/Database.php';

class Affiliate
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Check if user is an affiliate
     */
    public function isAffiliate($userId)
    {
        $sql = "SELECT id FROM affiliates WHERE user_id = ?";
        return $this->db->fetchOne($sql, [$userId]);
    }

    /**
     * Get affiliate details by User ID
     */
    public function getAffiliateByUserId($userId)
    {
        $sql = "SELECT * FROM affiliates WHERE user_id = ?";
        return $this->db->fetchOne($sql, [$userId]);
    }

    /**
     * Get affiliate details by Referral Code
     */
    public function getAffiliateByCode($code)
    {
        $sql = "SELECT * FROM affiliates WHERE referral_code = ? AND status = 'active'";
        return $this->db->fetchOne($sql, [$code]);
    }

    /**
     * Create new affiliate account
     */
    public function createAffiliate($userId)
    {
        // Generate unique referral code (Firstname + Random 3 digits, or generic)
        // Let's use generic first: AFF-[RANDOM]
        $code = 'AFF-' . strtoupper(substr(md5(uniqid()), 0, 6));

        // Ensure unique
        while ($this->getAffiliateByCode($code)) {
            $code = 'AFF-' . strtoupper(substr(md5(uniqid()), 0, 6));
        }

        $data = [
            'user_id' => $userId,
            'referral_code' => $code,
            'status' => 'active', // Auto-approve for now
            'commission_rate' => 20.00
        ];

        return $this->db->insert('affiliates', $data);
    }

    /**
     * Track a referral (When a new user registers)
     */
    public function trackReferral($affiliateId, $newUserId)
    {
        $data = [
            'affiliate_id' => $affiliateId,
            'user_id' => $newUserId,
            'status' => 'pending'
        ];
        return $this->db->insert('referrals', $data);
    }

    /**
     * Get Dashboard Stats
     */
    public function getStats($affiliateId)
    {
        $sql = "SELECT 
                    COUNT(id) as total_referrals,
                    SUM(CASE WHEN status = 'paid' THEN commission_amount ELSE 0 END) as total_earnings,
                    SUM(CASE WHEN status = 'pending' THEN commission_amount ELSE 0 END) as pending_earnings
                FROM referrals 
                WHERE affiliate_id = ?";
        return $this->db->fetchOne($sql, [$affiliateId]);
    }

    /**
     * Get Referral History
     */
    public function getReferrals($affiliateId, $limit = 20)
    {
        $sql = "SELECT r.*, u.full_name, u.created_at as joined_at 
                FROM referrals r
                JOIN users u ON r.user_id = u.id
                WHERE r.affiliate_id = ?
                ORDER BY r.created_at DESC LIMIT ?";
        return $this->db->fetchAll($sql, [$affiliateId, $limit]);
    }

    /**
     * Get ALL Affiliates (Admin)
     */
    public function getAllAffiliates()
    {
        $sql = "SELECT a.*, u.full_name, u.email 
                FROM affiliates a
                JOIN users u ON a.user_id = u.id
                ORDER BY a.created_at DESC";
        return $this->db->fetchAll($sql);
    }

    /**
     * Update Affiliate Status (Admin)
     */
    public function updateStatus($id, $status)
    {
        return $this->db->update('affiliates', ['status' => $status], 'id = ?', [$id]);
    }
}
