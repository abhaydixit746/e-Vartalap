<?php
namespace App\Model;

use App\Core\Database as DB;

class AnswerModel
{
    public static function getForQuestion(int $questionId, bool $allStatuses = false): array
    {
        $where = $allStatuses ? '' : "AND a.status = 'APPROVED'";
        return DB::query(
            "SELECT a.*,
                    u.username AS author_username,
                    u.first_name, u.last_name, u.photo_path
             FROM answers a
             JOIN users u ON u.id = a.author_id
             WHERE a.question_id = ? {$where}
             ORDER BY a.is_accepted DESC, a.created_at ASC",
            [$questionId]
        );
    }

    public static function findById(int $id): array|false
    {
        return DB::queryOne(
            'SELECT a.*, q.author_id AS question_author_id
             FROM answers a
             JOIN questions q ON q.id = a.question_id
             WHERE a.id = ?',
            [$id]
        );
    }

    public static function create(int $authorId, int $questionId, string $body): int
    {
        DB::execute(
            'INSERT INTO answers (body,author_id,question_id,status) VALUES (?,?,?,?)',
            [trim($body), $authorId, $questionId, 'PENDING']
        );
        return (int) DB::lastInsertId();
    }

    public static function setStatus(int $id, string $status): void
    {
        DB::execute('UPDATE answers SET status=? WHERE id=?', [$status, $id]);
    }

    public static function setAccepted(int $id): void
    {
        DB::execute('UPDATE answers SET is_accepted=1 WHERE id=?', [$id]);
    }

    public static function getPending(int $page, int $perPage): array
    {
        $sql = "SELECT a.*,
                       u.username AS author_username,
                       u.first_name, u.last_name,
                       q.title AS question_title
                FROM answers a
                JOIN users u ON u.id = a.author_id
                JOIN questions q ON q.id = a.question_id
                WHERE a.status = 'PENDING'
                ORDER BY a.created_at ASC";
        return DB::paginate($sql, [], $page, $perPage);
    }

    public static function countPending(): int
    {
        $r = DB::queryOne("SELECT COUNT(*) AS cnt FROM answers WHERE status='PENDING'");
        return (int) ($r['cnt'] ?? 0);
    }
}
