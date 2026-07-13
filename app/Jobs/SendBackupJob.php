<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendBackupJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $backupPath = env('BACKUP_LOCATION');
        $sqlPath = null;
        
        // Find the most recent SQL file
        if (is_dir($backupPath)) {
            $files = glob($backupPath . '/*.sql');
            if (!empty($files)) {
                // Sort files by modification time, newest first
                usort($files, function($a, $b) {
                    return filemtime($b) - filemtime($a);
                });
                $sqlPath = $files[0];
            }
        }

        // Generate the Excel file
        $excelFileName = 'backup-' . date('Y-m-d_H-i-s') . '.xlsx';
        $excelPath = storage_path('app/' . $excelFileName);
        
        \Maatwebsite\Excel\Facades\Excel::store(new \App\Exports\DetailsExport, $excelFileName);

        // Get the email
        $email = env('EMAIL_FOR_BACKUP');

        if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            \Illuminate\Support\Facades\Mail::to($email)->send(
                new \App\Mail\SendBackup($excelPath, $sqlPath)
            );
        }

        // Optional: clean up the excel file to save space
        if (file_exists($excelPath)) {
            unlink($excelPath);
        }
    }
}
