<?php

namespace App\Console\Commands;

use App\Models\Log;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;

#[Signature('app:data-base-backup-command')]
#[Description('Command description')]
class DataBaseBackupCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
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
            $process_command = Process::run($backup_cmd);

            return $gzip_file_name;
        } catch (\Exception $e) {
            // Handle or rethrow the exception
            $this->error("Backup process failed" . $e->getMessage());
            throw $e;
        }
    }
}
