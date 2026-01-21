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
        $existingUser = $this->getUserByEmail($data['email']);
        if ($existingUser) {
            if (!empty($existingUser['google_id'])) {
                return ['success' => false, 'message' => 'Account exists! Please use "Sign up with Google" to login.'];
            }
            return ['success' => false, 'message' => 'Email already registered. Please login instead.'];
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
        return $result !== false && $result !== null;
    }

    /**
     * Get user by ID
     */
    public function getUserById($id)
    {
        $sql = "SELECT id, email, full_name, phone, role, status, created_at, google_id, avatar 
                FROM users WHERE id = ?";
        return $this->db->fetchOne($sql, [$id]);
    }

    /**
     * Get user by email
     */
    public function getUserByEmail($email)
    {
        $sql = "SELECT id, email, full_name, phone, role, status, created_at, google_id, avatar 
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

    /**
     * Find or create user from Google Login
     */
    public function findOrCreateByGoogle($userInfo)
    {
        $googleId = $userInfo->id;
        $email = $userInfo->email;
        $name = $userInfo->name;
        $picture = $userInfo->picture;

        // 1. Check if user exists by google_id
        $sql = "SELECT * FROM users WHERE google_id = ?";
        $user = $this->db->fetchOne($sql, [$googleId]);

        if ($user) {
            return ['success' => true, 'user' => $user];
        }

        // 2. Check if user exists by email (Link account)
        $sql = "SELECT * FROM users WHERE email = ?";
        $user = $this->db->fetchOne($sql, [$email]);

        if ($user) {
            // Update with google_id and avatar if missing
            $updateData = ['google_id' => $googleId];
            if (empty($user['avatar'])) {
                $updateData['avatar'] = $picture;
            }

            $this->db->update('users', $updateData, 'id = ?', [$user['id']]);

            // Refresh user data
            $user = $this->db->fetchOne("SELECT * FROM users WHERE id = ?", [$user['id']]);
            return ['success' => true, 'user' => $user];
        }

        // 3. Create new user
        // Generate random password
        $password = bin2hex(random_bytes(8));

        $userData = [
            'full_name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'customer',
            'google_id' => $googleId,
            'avatar' => $picture,
            'status' => 'active'
        ];

        $userId = $this->db->insert('users', $userData);

        if ($userId) {
            $user = $this->db->fetchOne("SELECT * FROM users WHERE id = ?", [$userId]);
            return ['success' => true, 'user' => $user];
        }

        return ['success' => false, 'message' => 'Registration failed'];
    }
}
