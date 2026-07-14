<?php
namespace App\Util;

class FileUpload
{
    public static function storePhoto(array $file): string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('Upload error code: ' . $file['error']);
        }
        if ($file['size'] > CFG['app']['max_upload']) {
            throw new \RuntimeException('File size exceeds 5MB limit.');
        }

        $mime = mime_content_type($file['tmp_name']);
        if (!in_array($mime, CFG['app']['allowed_img'], true)) {
            throw new \RuntimeException('Invalid file type. Allowed: JPEG, PNG, GIF, WEBP.');
        }

        $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = bin2hex(random_bytes(16)) . '.' . strtolower($ext);
        $destDir  = CFG['app']['upload_dir'];

        if (!is_dir($destDir)) mkdir($destDir, 0755, true);

        $dest = $destDir . '/' . $filename;
        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            throw new \RuntimeException('Failed to save uploaded file.');
        }

        return CFG['app']['upload_url'] . '/' . $filename;
    }

    public static function deletePhoto(?string $path): void
    {
        if (!$path) return;
        $full = ROOT . '/public' . $path;
        if (file_exists($full)) unlink($full);
    }
}
