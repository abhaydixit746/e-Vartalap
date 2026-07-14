<?php
namespace App\Model;

use App\Core\Database as DB;

class QuestionModel
{
    // ---- Listing ----

    public static function getApproved(int $page, int $perPage): array
    {
        $sql = "SELECT q.*,
                       u.username AS author_username,
                       u.first_name, u.last_name, u.photo_path,
                       (SELECT COUNT(*) FROM answers a
                        WHERE a.question_id=q.id AND a.status='APPROVED') AS answer_count
                FROM questions q
                JOIN users u ON u.id = q.author_id
                WHERE q.status = 'APPROVED'
                ORDER BY q.created_at DESC";
        return DB::paginate($sql, [], $page, $perPage);
    }

    public static function getAll(int $page, int $perPage): array
    {
        $sql = "SELECT q.*,
                       u.username AS author_username,
                       u.first_name, u.last_name, u.photo_path,
                       (SELECT COUNT(*) FROM answers a WHERE a.question_id=q.id) AS answer_count
                FROM questions q
                JOIN users u ON u.id = q.author_id
                ORDER BY q.created_at DESC";
        return DB::paginate($sql, [], $page, $perPage);
    }

    public static function getByAuthor(int $authorId, int $page, int $perPage): array
    {
        $sql = "SELECT q.*,
                       u.username AS author_username,
                       u.first_name, u.last_name, u.photo_path,
                       (SELECT COUNT(*) FROM answers a
                        WHERE a.question_id=q.id AND a.status='APPROVED') AS answer_count
                FROM questions q
                JOIN users u ON u.id = q.author_id
                WHERE q.author_id = ?
                ORDER BY q.created_at DESC";
        return DB::paginate($sql, [$authorId], $page, $perPage);
    }

    public static function getUnanswered(int $page, int $perPage): array
    {
        $sql = "SELECT q.*,
                       u.username AS author_username,
                       u.first_name, u.last_name, u.photo_path,
                       0 AS answer_count
                FROM questions q
                JOIN users u ON u.id = q.author_id
                WHERE q.status = 'APPROVED'
                  AND NOT EXISTS (
                      SELECT 1 FROM answers a
                      WHERE a.question_id = q.id AND a.status = 'APPROVED'
                  )
                ORDER BY q.created_at DESC";
        return DB::paginate($sql, [], $page, $perPage);
    }

    public static function search(string $keyword, int $page, int $perPage): array
    {
        $kw  = '%' . $keyword . '%';
        $sql = "SELECT q.*,
                       u.username AS author_username,
                       u.first_name, u.last_name, u.photo_path,
                       (SELECT COUNT(*) FROM answers a
                        WHERE a.question_id=q.id AND a.status='APPROVED') AS answer_count
                FROM questions q
                JOIN users u ON u.id = q.author_id
                WHERE q.status = 'APPROVED'
                  AND (q.title LIKE ? OR q.body LIKE ?)
                ORDER BY q.created_at DESC";
        return DB::paginate($sql, [$kw, $kw], $page, $perPage);
    }

    // ---- Single ----

    public static function findById(int $id): array|false
    {
        return DB::queryOne(
            "SELECT q.*,
                    u.username AS author_username,
                    u.first_name, u.last_name, u.photo_path
             FROM questions q
             JOIN users u ON u.id = q.author_id
             WHERE q.id = ?",
            [$id]
        );
    }

    public static function incrementViewCount(int $id): void
    {
        DB::execute('UPDATE questions SET view_count = view_count + 1 WHERE id = ?', [$id]);
    }

    // ---- Tags ----

    public static function getTags(int $questionId): array
    {
        return DB::query(
            'SELECT t.name FROM tags t
             JOIN question_tags qt ON qt.tag_id = t.id
             WHERE qt.question_id = ?',
            [$questionId]
        );
    }

    public static function syncTags(int $questionId, string $tagString): void
    {
        // Remove old tags
        DB::execute('DELETE FROM question_tags WHERE question_id=?', [$questionId]);
        if (trim($tagString) === '') return;

        $names = array_slice(
            array_filter(array_map('trim', explode(',', strtolower($tagString)))),
            0, 5
        );
        foreach ($names as $name) {
            // Find or create tag
            $tag = DB::queryOne('SELECT id FROM tags WHERE name=?', [$name]);
            if (!$tag) {
                DB::execute('INSERT INTO tags (name) VALUES (?)', [$name]);
                $tagId = (int) DB::lastInsertId();
            } else {
                $tagId = (int) $tag['id'];
            }
            DB::execute(
                'INSERT IGNORE INTO question_tags (question_id,tag_id) VALUES (?,?)',
                [$questionId, $tagId]
            );
        }
    }

    // ---- CRUD ----

    public static function create(int $authorId, string $title, string $body, string $tags): int
    {
        DB::execute(
            'INSERT INTO questions (title,body,author_id,status) VALUES (?,?,?,?)',
            [trim($title), trim($body), $authorId, 'PENDING']
        );
        $id = (int) DB::lastInsertId();
        self::syncTags($id, $tags);
        return $id;
    }

    public static function setStatus(int $id, string $status): void
    {
        DB::execute('UPDATE questions SET status=? WHERE id=?', [$status, $id]);
    }

    public static function countPending(): int
    {
        $r = DB::queryOne("SELECT COUNT(*) AS cnt FROM questions WHERE status='PENDING'");
        return (int) ($r['cnt'] ?? 0);
    }
}
