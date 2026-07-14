<?php
namespace App\Model;

use App\Core\Database as DB;

class UserModel
{
    // ---- Auth ----

    public static function findByUsername(string $username): array|false
    {
        return DB::queryOne(
            'SELECT * FROM users WHERE username = ? AND is_active = 1 LIMIT 1',
            [$username]
        );
    }

    public static function findByEmail(string $email): array|false
    {
        return DB::queryOne(
            'SELECT * FROM users WHERE email = ? AND is_active = 1 LIMIT 1',
            [$email]
        );
    }

    public static function findById(int $id): array|false
    {
        return DB::queryOne('SELECT * FROM users WHERE id = ? LIMIT 1', [$id]);
    }

    public static function existsByUsername(string $username): bool
    {
        $r = DB::queryOne('SELECT id FROM users WHERE username = ? LIMIT 1', [$username]);
        return (bool) $r;
    }

    public static function existsByEmail(string $email): bool
    {
        $r = DB::queryOne('SELECT id FROM users WHERE email = ? LIMIT 1', [$email]);
        return (bool) $r;
    }

    // ---- Register ----

    public static function create(array $data): int
    {
        DB::execute(
            'INSERT INTO users (username,password,email,first_name,last_name,contact,role)
             VALUES (?,?,?,?,?,?,?)',
            [
                $data['username'],
                password_hash($data['password'], PASSWORD_BCRYPT),
                $data['email'],
                $data['first_name'],
                $data['last_name'],
                $data['contact'] ?? null,
                'USER',
            ]
        );
        return (int) DB::lastInsertId();
    }

    // ---- Profile ----

    public static function update(int $id, array $data): void
    {
        DB::execute(
            'UPDATE users SET first_name=?,last_name=?,email=?,contact=?,company=?,designation=? WHERE id=?',
            [
                $data['first_name'],
                $data['last_name'],
                $data['email'],
                $data['contact'] ?? null,
                $data['company'] ?? null,
                $data['designation'] ?? null,
                $id,
            ]
        );
    }

    public static function updatePhoto(int $id, string $path): void
    {
        DB::execute('UPDATE users SET photo_path=? WHERE id=?', [$path, $id]);
    }

    // ---- Listing ----

    public static function getAllActive(int $page, int $perPage): array
    {
        $sql = "SELECT id,username,first_name,last_name,company,designation,photo_path,role
                FROM users WHERE is_active=1 ORDER BY first_name,last_name";
        return DB::paginate($sql, [], $page, $perPage);
    }

    public static function getAllActiveExcept(int $excludeId, int $page, int $perPage): array
    {
        $sql = "SELECT id,username,first_name,last_name,company,designation,photo_path,role
                FROM users WHERE is_active=1 AND id != ? ORDER BY first_name,last_name";
        return DB::paginate($sql, [$excludeId], $page, $perPage);
    }

    // ---- Helpers ----

    public static function photoUrl(?string $path): string
    {
        return $path ? $path : '/img/default-avatar.svg';
    }

    public static function fullName(array $user): string
    {
        return trim($user['first_name'] . ' ' . $user['last_name']);
    }
}
