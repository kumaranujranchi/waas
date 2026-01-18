<?php
/**
 * User Model
 * Handles user authentication and management
 */

require_once __DIR__ . '/../classes/Database.php';

class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Register new user
     */
    public function register($data)
    {
        // Check if email already exists
        if ($this->emailExists($data['email'])) {
            return ['success' => false, 'message' => 'Email already registered'];
        }

        // Hash password
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        // Insert user
        $userId = $this->db->insert('users', $data);

        if ($userId) {
            return ['success' => true, 'user_id' => $userId, 'message' => 'Registration successful'];
        }

        return ['success' => false, 'message' => 'Registration failed'];
    }

    /**
     * Login user
     */
    public function login($email, $password)
    {
        $sql = "SELECT * FROM users WHERE email = ? AND status = 'active'";
        $user = $this->db->fetchOne($sql, [$email]);

        if (!$user) {
            return ['success' => false, 'message' => 'Invalid email or password'];
        }

        if (!password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Invalid email or password'];
        }

        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_role'] = $user['role'];

        return ['success' => true, 'user' => $user, 'message' => 'Login successful'];
    }

    /**
     * Logout user
     */
    public function logout()
    {
        session_destroy();
        return true;
    }

    /**
     * Check if email exists
     */
    public function emailExists($email)
    {
        $sql = "SELECT id FROM users WHERE email = ?";
        $result = $this->db->fetchOne($sql, [$email]);
        return $result !== null;
    }

    /**
     * Get user by ID
     */
    public function getUserById($id)
    {
        $sql = "SELECT id, email, full_name, phone, role, status, created_at 
                FROM users WHERE id = ?";
        return $this->db->fetchOne($sql, [$id]);
    }

    /**
     * Get user by email
     */
    public function getUserByEmail($email)
    {
        $sql = "SELECT id, email, full_name, phone, role, status, created_at 
                FROM users WHERE email = ?";
        return $this->db->fetchOne($sql, [$email]);
    }

    /**
     * Update user profile
     */
    public function updateProfile($id, $data)
    {
        // Remove password from data if empty
        if (isset($data['password']) && empty($data['password'])) {
            unset($data['password']);
        } elseif (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        return $this->db->update('users', $data, 'id = ?', [$id]);
    }

    /**
     * Change password
     */
    public function changePassword($userId, $currentPassword, $newPassword)
    {
        $sql = "SELECT password FROM users WHERE id = ?";
        $user = $this->db->fetchOne($sql, [$userId]);

        if (!$user || !password_verify($currentPassword, $user['password'])) {
            return ['success' => false, 'message' => 'Current password is incorrect'];
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updated = $this->db->update('users', ['password' => $hashedPassword], 'id = ?', [$userId]);

        if ($updated) {
            return ['success' => true, 'message' => 'Password changed successfully'];
        }

        return ['success' => false, 'message' => 'Failed to change password'];
    }

    /**
     * Get all users (Admin)
     */
    public function getAllUsers($limit = null, $offset = 0)
    {
        $sql = "SELECT id, email, full_name, phone, role, status, created_at 
                FROM users 
                ORDER BY created_at DESC";

        if ($limit) {
            $sql .= " LIMIT ? OFFSET ?";
            return $this->db->fetchAll($sql, [$limit, $offset]);
        }

        return $this->db->fetchAll($sql);
    }

    /**
     * Count total users
     */
    public function countUsers()
    {
        $sql = "SELECT COUNT(*) as total FROM users";
        $result = $this->db->fetchOne($sql);
        return $result['total'] ?? 0;
    }

    /**
     * Update user status (Admin)
     */
    public function updateUserStatus($id, $status)
    {
        return $this->db->update('users', ['status' => $status], 'id = ?', [$id]);
    }

    /**
     * Delete user (Admin)
     */
    public function deleteUser($id)
    {
        return $this->db->delete('users', 'id = ?', [$id]);
    }
}
