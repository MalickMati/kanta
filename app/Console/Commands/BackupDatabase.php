<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackupDatabase extends Command
{
    protected $signature = 'backup:db';
    protected $description = 'Backup the MySQL database to a specific drive';

    public function handle()
    {
        // === Database details from .env ===
        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $host     = env('DB_HOST', '127.0.0.1');

        // === Destination folder (change this path to your drive) ===
        // Windows example: D:\LaravelBackups
        // Linux example: /media/m/008CCE6A8CCE59B4
        $backupPath = '/media/m/008CCE6A8CCE59B4/LaravelBackups'; // ← change this to your own drive path

        if (!is_dir($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        // === File name ===
        $fileName = 'backup-' . date('Y-m-d_H-i-s') . '.sql';
        $filePath = $backupPath . DIRECTORY_SEPARATOR . $fileName;

        // === Build mysqldump command ===
        $command = "mysqldump --user=\"{$username}\" --password=\"{$password}\" --host=\"{$host}\" {$database} > \"{$filePath}\"";

        // === Run command ===
        $this->info("Backing up database to: {$filePath}");
        exec($command, $output, $returnVar);

        if ($returnVar === 0) {
            $this->info("Backup completed successfully!");
        } else {
            $this->error("Backup failed. Please check your MySQL credentials or path.");
        }

        return 0;
    }
}
