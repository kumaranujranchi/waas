<?php
/**
 * Order Model
 * Handles order processing and transactions
 */

require_once __DIR__ . '/../classes/Database.php';

class Order
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Create new order
     */
    public function createOrder($orderData, $orderItems)
    {
        try {
            $this->db->beginTransaction();

            // Insert order
            $orderId = $this->db->insert('orders', $orderData);

            if (!$orderId) {
                throw new Exception('Failed to create order');
            }

            // Insert order items
            foreach ($orderItems as $item) {
                $item['order_id'] = $orderId;
                $this->db->insert('order_items', $item);
            }

            $this->db->commit();
            return ['success' => true, 'order_id' => $orderId];

        } catch (Exception $e) {
            $this->db->rollback();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Get order by ID
     */
    public function getOrderById($id)
    {
        $sql = "SELECT o.*, u.full_name as user_name, u.email as user_email 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.id 
                WHERE o.id = ?";

        return $this->db->fetchOne($sql, [$id]);
    }

    /**
     * Get order by order number
     */
    public function getOrderByNumber($orderNumber)
    {
        $sql = "SELECT o.*, u.full_name as user_name, u.email as user_email 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.id 
                WHERE o.order_number = ?";

        return $this->db->fetchOne($sql, [$orderNumber]);
    }

    /**
     * Get order items
     */
    public function getOrderItems($orderId)
    {
        $sql = "SELECT * FROM order_items WHERE order_id = ?";
        return $this->db->fetchAll($sql, [$orderId]);
    }

    /**
     * Get user orders
     */
    public function getUserOrders($userId)
    {
        $sql = "SELECT * FROM orders 
                WHERE user_id = ? 
                ORDER BY created_at DESC";

        return $this->db->fetchAll($sql, [$userId]);
    }

    /**
     * Update order status
     */
    public function updateOrderStatus($id, $status)
    {
        $updated = $this->db->update('orders', ['payment_status' => $status], 'id = ?', [$id]);

        // Trigger Commission if Completed
        if ($updated && $status === 'completed') {
            require_once __DIR__ . '/Affiliate.php';
            $affiliateModel = new Affiliate();
            $affiliateModel->processCommission($id);

            // Also update order_status to processing or completed? 
            // Usually, payment completed means order is at least 'processing'
            $this->updateOperationalStatus($id, 'processing');
        }

        return $updated;
    }

    /**
     * Update operational/fulfillment status
     */
    public function updateOperationalStatus($id, $status)
    {
        return $this->db->update('orders', ['order_status' => $status], 'id = ?', [$id]);
    }

    /**
     * Update payment details
     */
    public function updatePaymentDetails($orderId, $paymentId, $status)
    {
        $data = [
            'payment_id' => $paymentId,
            'payment_status' => $status
        ];

        $updated = $this->db->update('orders', $data, 'id = ?', [$orderId]);

        // Trigger Commission if Completed
        if ($updated && $status === 'completed') {
            require_once __DIR__ . '/Affiliate.php';
            $affiliateModel = new Affiliate();
            $affiliateModel->processCommission($orderId);
        }

        return $updated;
    }

    /**
     * Get all orders (Admin)
     */
    public function getAllOrders($limit = null, $offset = 0)
    {
        $sql = "SELECT o.*, u.full_name as user_name, u.email as user_email 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.id 
                ORDER BY o.created_at DESC";

        if ($limit) {
            $sql .= " LIMIT ? OFFSET ?";
            return $this->db->fetchAll($sql, [$limit, $offset]);
        }

        return $this->db->fetchAll($sql);
    }

    /**
     * Get total revenue
     */
    public function getTotalRevenue()
    {
        $sql = "SELECT SUM(final_amount) as total 
                FROM orders 
                WHERE payment_status = 'completed'";

        $result = $this->db->fetchOne($sql);
        return $result['total'] ?? 0;
    }

    /**
     * Count orders by status
     */
    public function countOrdersByStatus($status = null)
    {
        $sql = "SELECT COUNT(*) as total FROM orders";
        $params = [];

        if ($status) {
            $sql .= " WHERE payment_status = ?";
            $params[] = $status;
        }

        $result = $this->db->fetchOne($sql, $params);
        return $result['total'] ?? 0;
    }

    /**
     * Get recent orders
     */
    public function getRecentOrders($limit = 10)
    {
        $sql = "SELECT o.*, u.full_name as user_name 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.id 
                ORDER BY o.created_at DESC 
                LIMIT ?";

        return $this->db->fetchAll($sql, [$limit]);
    }
    /**
     * Get order by transaction ID (mapped to payment_id column)
     */
    public function getOrderByTransactionId($transactionId)
    {
        $sql = "SELECT o.*, u.full_name as user_name, u.email as user_email 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.id 
                WHERE o.payment_id = ?";

        return $this->db->fetchOne($sql, [$transactionId]);
    }
}
