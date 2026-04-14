<?php

namespace App\Concerns;

use App\Models\Log;
use Illuminate\Support\Facades\Process;

trait BackupDatabase
{
    //
    public function performBackup(int $userId) {
        //
    try {
        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $host = env('DB_HOST');
        $port = env('DB_PORT');

        $baseName = $database.'_backup_'.now()->format("d-m-Y-H-i-s");

        $sql_file = storage_path("app/backups/$baseName.sql");

        $mysql_path = "D:\\Programas\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin";
        $gzip_path = "D:\\Programas\\laragon\\bin\\git\\usr\\bin";

        // Ejecutables
        $mysqldump = "\"{$mysql_path}\\mysqldump.exe\"";
        $gzip = "\"{$gzip_path}\\gzip.exe\"";

        $dump_cmd = "$mysqldump --user=$username --password=$password --host=$host --port=$port --single-transaction --quick --lock-tables=false $database > \"$sql_file\"";

        $processDump = Process::run("cmd /c $dump_cmd");

        if ($processDump->failed()) {
            Log::error("Dump failed: " . $processDump->errorOutput());
            throw new \Exception('Database dump failed.');
        }

        return $baseName . '.sql';
        } catch (\Exception $e) {
            // Handle or rethrow the exception
            Log::error("Backup process failed for user $userId: " . $e->getMessage());
            throw $e;
        }
    }
}
