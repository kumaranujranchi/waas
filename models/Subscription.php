<?php
/**
 * Subscription Model
 * Handles user subscriptions
 */

require_once __DIR__ . '/../classes/Database.php';

class Subscription
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Create new subscription
     */
    public function createSubscription($data)
    {
        return $this->db->insert('subscriptions', $data);
    }

    /**
     * Get subscription by ID
     */
    public function getSubscriptionById($id)
    {
        $sql = "SELECT s.*, p.name as product_name, pp.plan_name, pp.price, pp.billing_cycle 
                FROM subscriptions s 
                LEFT JOIN products p ON s.product_id = p.id 
                LEFT JOIN pricing_plans pp ON s.plan_id = pp.id 
                WHERE s.id = ?";

        return $this->db->fetchOne($sql, [$id]);
    }

    /**
     * Get user subscriptions
     */
    public function getUserSubscriptions($userId, $status = null)
    {
        $sql = "SELECT s.*, p.name as product_name, p.slug as product_slug, 
                pp.plan_name, pp.price, pp.billing_cycle 
                FROM subscriptions s 
                LEFT JOIN products p ON s.product_id = p.id 
                LEFT JOIN pricing_plans pp ON s.plan_id = pp.id 
                WHERE s.user_id = ?";

        $params = [$userId];

        if ($status) {
            $sql .= " AND s.subscription_status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY s.created_at DESC";

        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Get active subscriptions
     */
    public function getActiveSubscriptions($userId)
    {
        return $this->getUserSubscriptions($userId, 'active');
    }

    /**
     * Update subscription status
     */
    public function updateSubscriptionStatus($id, $status)
    {
        return $this->db->update('subscriptions', ['subscription_status' => $status], 'id = ?', [$id]);
    }

    /**
     * Cancel subscription
     */
    public function cancelSubscription($id)
    {
        return $this->updateSubscriptionStatus($id, 'cancelled');
    }

    /**
     * Check if user has active subscription for product
     */
    public function hasActiveSubscription($userId, $productId)
    {
        $sql = "SELECT id FROM subscriptions 
                WHERE user_id = ? AND product_id = ? 
                AND subscription_status = 'active' 
                AND end_date >= CURDATE()";

        $result = $this->db->fetchOne($sql, [$userId, $productId]);
        return $result !== null;
    }

    /**
     * Get all subscriptions (Admin)
     */
    public function getAllSubscriptions($limit = null, $offset = 0)
    {
        $sql = "SELECT s.*, u.full_name as user_name, u.email as user_email, 
                p.name as product_name, pp.plan_name 
                FROM subscriptions s 
                LEFT JOIN users u ON s.user_id = u.id 
                LEFT JOIN products p ON s.product_id = p.id 
                LEFT JOIN pricing_plans pp ON s.plan_id = pp.id 
                ORDER BY s.created_at DESC";

        if ($limit) {
            $sql .= " LIMIT ? OFFSET ?";
            return $this->db->fetchAll($sql, [$limit, $offset]);
        }

        return $this->db->fetchAll($sql);
    }

    /**
     * Count subscriptions by status
     */
    public function countSubscriptionsByStatus($status = null)
    {
        $sql = "SELECT COUNT(*) as total FROM subscriptions";
        $params = [];

        if ($status) {
            $sql .= " WHERE subscription_status = ?";
            $params[] = $status;
        }

        $result = $this->db->fetchOne($sql, $params);
        return $result['total'] ?? 0;
    }

    /**
     * Get expiring subscriptions (within next 7 days)
     */
    public function getExpiringSubscriptions()
    {
        $sql = "SELECT s.*, u.email as user_email, u.full_name as user_name, 
                p.name as product_name 
                FROM subscriptions s 
                LEFT JOIN users u ON s.user_id = u.id 
                LEFT JOIN products p ON s.product_id = p.id 
                WHERE s.subscription_status = 'active' 
                AND s.end_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) 
                ORDER BY s.end_date ASC";

        return $this->db->fetchAll($sql);
    }
}
