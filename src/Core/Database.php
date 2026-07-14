<?php
namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    private function __construct() {}
    private function __clone() {}

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $db = CFG['db'];

            $dsn = "mysql:host={$db['host']};port={$db['port']};dbname={$db['dbname']};charset={$db['charset']}";

            // Aiven requires TLS. If the CA certificate is supplied as a
            // Render multiline secret, materialize it into a temporary file.
            $caPath = $db['ssl_ca'];
            if (!$caPath && !empty($db['ssl_ca_cert'])) {
                $caPath = sys_get_temp_dir() . '/aiven-ca.pem';
                if (!is_file($caPath)) {
                    file_put_contents($caPath, $db['ssl_ca_cert'], LOCK_EX);
                    @chmod($caPath, 0600);
                }
            }

            if (!empty($db['ssl_mode'])) {
                $mode = strtolower($db['ssl_mode']);
                $dsn .= ";sslmode={$mode}";
                if ($caPath) {
                    $dsn .= ";sslrootcert='{$caPath}'";
                }
            }

            try {
                self::$instance = new PDO($dsn, $db['username'], $db['password'], [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                    PDO::MYSQL_ATTR_FOUND_ROWS   => true,
                ]);
            } catch (PDOException $e) {
                error_log('DB connection failed: ' . $e->getMessage());
                http_response_code(500);
                die('Database connection error. Please try again later.');
            }
        }

        return self::$instance;
    }

    public static function query(string $sql, array $params = []): array
    {
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function queryOne(string $sql, array $params = []): array|false
    {
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    public static function execute(string $sql, array $params = []): int
    {
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    public static function lastInsertId(): string
    {
        return self::getInstance()->lastInsertId();
    }

    public static function paginate(string $sql, array $params, int $page, int $perPage): array
    {
        $countSql = 'SELECT COUNT(*) as cnt FROM (' . $sql . ') _c';
        $total = (int) self::queryOne($countSql, $params)['cnt'];
        $offset = max(0, $page - 1) * $perPage;
        $items = self::query($sql . " LIMIT {$perPage} OFFSET {$offset}", $params);

        return [
            'items' => $items,
            'total' => $total,
            'pages' => (int) ceil($total / $perPage),
            'currentPage' => $page,
            'perPage' => $perPage,
        ];
    }
}
