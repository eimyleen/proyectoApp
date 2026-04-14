<?php

namespace App\Concerns;

use App\Models\Log;

trait BackupDatabase
{
    //
    public function performBackup(int $userId) {
        //
        try {
            info(now());
            // Database credentials
            $database = env('DB_DATABASE');
            $username = env('DB_USERNAME');
            $password = env('DB_PASSWORD');
            $host = env('DB_HOST');
            $port = env('DB_PORT');

            // Backup file name (compressed)
            $gzip_file_name = $database.'_backup_'.now()->format("d-m-Y-H-m-s").'.sql.gz';
            $gzip_file_path = storage_path("app/backups/$gzip_file_name");

            // MySQL dump command, streamed directly into gzip
            $backup_cmd = "mysqldump --user=$username --password=$password --host=$host --port=$port --single-transaction --quick --lock-tables=false $database | gzip > $gzip_file_path";

            // Execute the command
            exec($backup_cmd, $output, $result);

            if ($result) {
                Log::error("Backup failed for user $userId: " . implode("\n", $output));
                throw new \Exception('Database backup failed.');
            }

            return $gzip_file_name;
        } catch (\Exception $e) {
            // Handle or rethrow the exception
            Log::error("Backup process failed for user $userId: " . $e->getMessage());
            throw $e;
        }
    }
}
