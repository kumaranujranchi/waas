<?php
/**
 * Product Model
 * Handles all product-related database operations
 */

require_once __DIR__ . '/../classes/Database.php';

class Product
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Get all active products
     */
    public function getAllProducts($limit = null, $offset = 0)
    {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.status = 'active' 
                ORDER BY p.is_featured DESC, p.created_at DESC";

        if ($limit) {
            $sql .= " LIMIT ? OFFSET ?";
            return $this->db->fetchAll($sql, [$limit, $offset]);
        }

        return $this->db->fetchAll($sql);
    }

    /**
     * Get featured products
     */
    public function getFeaturedProducts($limit = 4)
    {
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.status = 'active' AND p.is_featured = 1 
                ORDER BY p.created_at DESC 
                LIMIT ?";

        return $this->db->fetchAll($sql, [$limit]);
    }

    /**
     * Get product by ID
     */
    public function getProductById($id)
    {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.id = ? AND p.status = 'active'";

        return $this->db->fetchOne($sql, [$id]);
    }

    /**
     * Get product by slug
     */
    public function getProductBySlug($slug)
    {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.slug = ? AND p.status = 'active'";

        return $this->db->fetchOne($sql, [$slug]);
    }

    /**
     * Get products by category
     */
    public function getProductsByCategory($categoryId)
    {
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.category_id = ? AND p.status = 'active' 
                ORDER BY p.is_featured DESC, p.created_at DESC";

        return $this->db->fetchAll($sql, [$categoryId]);
    }

    /**
     * Search products
     */
    public function searchProducts($query)
    {
        $searchTerm = "%{$query}%";
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.status = 'active' 
                AND (p.name LIKE ? OR p.short_description LIKE ? OR p.full_description LIKE ?) 
                ORDER BY p.is_featured DESC, p.created_at DESC";

        return $this->db->fetchAll($sql, [$searchTerm, $searchTerm, $searchTerm]);
    }

    /**
     * Get product features
     */
    public function getProductFeatures($productId)
    {
        $sql = "SELECT * FROM product_features 
                WHERE product_id = ? 
                ORDER BY display_order ASC";

        return $this->db->fetchAll($sql, [$productId]);
    }

    /**
     * Get product pricing plans
     */
    public function getProductPricingPlans($productId, $activeOnly = false)
    {
        $sql = "SELECT * FROM pricing_plans WHERE product_id = ?";
        $params = [$productId];

        if ($activeOnly) {
            $sql .= " AND status = 'active'";
        }

        $sql .= " ORDER BY billing_cycle ASC";

        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Get product FAQs
     */
    /**
     * Get product FAQs
     * @deprecated Use $product['faqs'] (JSON) instead
     */
    public function getProductFAQs($productId)
    {
        // Fallback to legacy table if needed, or return empty
        // ideally we switch entirely to JSON
        $sql = "SELECT * FROM product_faqs 
                WHERE product_id = ? 
                ORDER BY display_order ASC";

        return $this->db->fetchAll($sql, [$productId]);
    }

    /**
     * Get pricing plan by ID
     */
    public function getPricingPlanById($planId)
    {
        $sql = "SELECT pp.*, p.name as product_name 
                FROM pricing_plans pp 
                LEFT JOIN products p ON pp.product_id = p.id 
                WHERE pp.id = ? AND pp.status = 'active'";

        return $this->db->fetchOne($sql, [$planId]);
    }

    /**
     * Count total products
     */
    public function countProducts()
    {
        $sql = "SELECT COUNT(*) as total FROM products WHERE status = 'active'";
        $result = $this->db->fetchOne($sql);
        return $result['total'] ?? 0;
    }

    /**
     * Create new product (Admin)
     */
    /**
     * Create new product (Admin)
     */
    public function createProduct($data)
    {
        // Ensure faqs is initialized and is JSON
        if (!isset($data['faqs'])) {
            $data['faqs'] = json_encode([]);
        } elseif (is_array($data['faqs'])) {
            $data['faqs'] = json_encode($data['faqs']);
        }
        return $this->db->insert('products', $data);
    }

    /**
     * Update product (Admin)
     */
    public function updateProduct($id, $data)
    {
        // Ensure faqs is not null if passed
        if (array_key_exists('faqs', $data) && is_array($data['faqs'])) {
            $data['faqs'] = json_encode($data['faqs']);
        }
        return $this->db->update('products', $data, 'id = ?', [$id]);
    }

    /**
     * Delete product (Admin)
     */
    public function deleteProduct($id)
    {
        return $this->db->delete('products', 'id = ?', [$id]);
    }

    /**
     * Create pricing plan (Admin)
     */
    public function createPricingPlan($data)
    {
        return $this->db->insert('pricing_plans', $data);
    }

    /**
     * Create FAQ (Admin)
     */
    public function createFAQ($data)
    {
        return $this->db->insert('product_faqs', $data);
    }
}
