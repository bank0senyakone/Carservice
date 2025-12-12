<?php

namespace App\Models;

use App\Core\DB;

/**
 * Auth Model
 * Handles user authentication data access
 */
class AuthModel
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = DB::getInstance()->getConnection();
    }

    /**
     * Get user by username
     * 
     * @param string $username
     * @return array|false
     */
    public function getUserByUsername(string $username): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }

    /**
     * Get user by ID
     * 
     * @param int $id
     * @return array|false
     */
    public function getUserById(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Verify password against hash
     * 
     * @param string $hash The stored password hash
     * @param string $plainText The plain text password to verify
     * @return bool
     */
    public function verifyPassword(string $hash, string $plainText): bool
    {
        return password_verify($plainText, $hash);
    }

    /**
     * Register a new user
     * 
     * @param array $data User data [username, password, name, role]
     * @return int|false The new user ID or false on failure
     */
    public function registerUser(array $data): int|false
    {
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        $stmt = $this->db->prepare("
            INSERT INTO users (username, password, name, role) 
            VALUES (:username, :password, :name, :role)
        ");

        $result = $stmt->execute([
            'username' => $data['username'],
            'password' => $hashedPassword,
            'name' => $data['name'],
            'role' => $data['role'] ?? 'Staff'
        ]);

        return $result ? (int)$this->db->lastInsertId() : false;
    }

    /**
     * Update user password
     * 
     * @param int $id User ID
     * @param string $newPassword New plain text password
     * @return bool
     */
    public function updatePassword(int $id, string $newPassword): bool
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare("UPDATE users SET password = :password WHERE id = :id");
        return $stmt->execute(['password' => $hashedPassword, 'id' => $id]);
    }

    /**
     * Get all users
     * 
     * @return array
     */
    public function getAllUsers(): array
    {
        $stmt = $this->db->query("SELECT id, username, name, role, created_at FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }
}
