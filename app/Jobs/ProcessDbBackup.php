<?php

namespace App\Jobs;

use App\Concerns\BackupDatabase;
use App\Events\BackupCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessDbBackup implements ShouldQueue
{
    use Queueable, BackupDatabase;

    public $timeout = 300; // 5 minutes

    /**
     * Create a new job instance.
     */
    public function __construct(public int $userId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        try {
            $backup_file = $this->performBackup($this->userId);

            broadcast(new BackupCompleted($this->userId, 'The database has been successfully backed up.', $backup_file));
        } catch (\Exception $e) {
            broadcast(new BackupCompleted($this->userId, 'An error has occurred: ' . $e->getMessage(), $backup_file));
        }
    }
}
