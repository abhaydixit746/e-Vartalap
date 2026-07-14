<?php
namespace App\Core;

use PDO;
use PDOException;

/**
 * Database — PDO singleton
 * Thread-safe PDO connection with prepared statements only.
 * Replaces legacy: raw mysqli / DriverManager calls.
 */
class Database
{
    private static ?PDO $instance = null;

    private function __construct() {}
    private function __clone() {}

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $db  = CFG['db'];
            $dsn = "mysql:host={$db['host']};port={$db['port']};dbname={$db['dbname']};charset={$db['charset']}";
            try {
                self::$instance = new PDO($dsn, $db['username'], $db['password'], [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                    PDO::MYSQL_ATTR_FOUND_ROWS   => true,
                ]);
            } catch (PDOException $e) {
                // Never expose DB details in production
                error_log('DB connection failed: ' . $e->getMessage());
                http_response_code(500);
                die('Database connection error. Please try again later.');
            }
        }
        return self::$instance;
    }

    /** Convenience: execute a query and return all rows */
    public static function query(string $sql, array $params = []): array
    {
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /** Convenience: execute and return single row */
    public static function queryOne(string $sql, array $params = []): array|false
    {
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    /** Convenience: execute INSERT/UPDATE/DELETE, return affected rows */
    public static function execute(string $sql, array $params = []): int
    {
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    /** Return last insert id */
    public static function lastInsertId(): string
    {
        return self::getInstance()->lastInsertId();
    }

    /** Pagination helper — returns [items, total, pages] */
    public static function paginate(string $sql, array $params, int $page, int $perPage): array
    {
        // Count
        $countSql = 'SELECT COUNT(*) as cnt FROM (' . $sql . ') _c';
        $total    = (int) self::queryOne($countSql, $params)['cnt'];

        // Data
        $offset = max(0, $page - 1) * $perPage;
        $items  = self::query($sql . " LIMIT {$perPage} OFFSET {$offset}", $params);

        return [
            'items'       => $items,
            'total'       => $total,
            'pages'       => (int) ceil($total / $perPage),
            'currentPage' => $page,
            'perPage'     => $perPage,
        ];
    }
}
