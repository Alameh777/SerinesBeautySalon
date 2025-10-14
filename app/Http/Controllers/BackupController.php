<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class BackupController extends Controller
{
    public function downloadBackup()
    {
        $db = config('database.connections.mysql.database');
        $user = config('database.connections.mysql.username');
        $pass = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');

        // Backup file name with timestamp
        $filename = 'backup_' . now()->format('Y_m_d_H_i_s') . '.sql';
        $path = storage_path('app/' . $filename);

        // Path to mysqldump (XAMPP default)
        $mysqlDumpPath = 'C:\xampp\mysql\bin\mysqldump.exe';

        // Build command (escape quotes for Windows)
        $command = "\"{$mysqlDumpPath}\" --user=\"{$user}\" --password=\"{$pass}\" --host=\"{$host}\" {$db} > \"{$path}\"";

        // Execute the command
        system($command);

        // Verify backup file
        if (!File::exists($path)) {
            return back()->with('error', 'Backup failed. Check database config or mysqldump path.');
        }

        // Return file as download and delete after sending
        return response()->download($path)->deleteFileAfterSend(true);
    }
}
