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
            $file_name = $database.'_backup_auto_'.now()->format("d-m-dY-H-m-s");
            $file_path = storage_path("app/backups/$file_name.sql");

            // MySQL dump command, streamed directly into gzip
            $backup_cmd = "mysqldump --user=$username --password=$password --host=$host --port=$port --single-transaction --quick --lock-tables=false $database > \"$file_path\"";

            // Execute the command
            $process_command = Process::run($backup_cmd);

            if ($process_command->failed()) {
                $this->error('Falló el backup');
                $this->error($process_command->errorOutput());
                return;
            }

            $this->info('Backup generado correctamente');
            $this->info($file_name);

            return $file_path;
        } catch (\Exception $e) {
            // Handle or rethrow the exception
            $this->error("Backup process failed" . $e->getMessage());
            throw $e;
        }
    }
}
