<?php
/**
 * Category Model
 * Handles product categories
 */

require_once __DIR__ . '/../classes/Database.php';

class Category
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Get all active categories
     */
    public function getAllCategories()
    {
        $sql = "SELECT * FROM categories 
                WHERE status = 'active' 
                ORDER BY display_order ASC, name ASC";

        return $this->db->fetchAll($sql);
    }

    /**
     * Get category by ID
     */
    public function getCategoryById($id)
    {
        $sql = "SELECT * FROM categories WHERE id = ? AND status = 'active'";
        return $this->db->fetchOne($sql, [$id]);
    }

    /**
     * Get category by slug
     */
    public function getCategoryBySlug($slug)
    {
        $sql = "SELECT * FROM categories WHERE slug = ? AND status = 'active'";
        return $this->db->fetchOne($sql, [$slug]);
    }

    /**
     * Count products in category
     */
    public function countProductsInCategory($categoryId)
    {
        $sql = "SELECT COUNT(*) as total FROM products 
                WHERE category_id = ? AND status = 'active'";
        $result = $this->db->fetchOne($sql, [$categoryId]);
        return $result['total'] ?? 0;
    }
}
