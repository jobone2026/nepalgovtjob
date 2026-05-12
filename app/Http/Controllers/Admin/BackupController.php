<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use ZipArchive;

class BackupController extends Controller
{
    /**
     * Display backup management page
     */
    public function index()
    {
        $backups = $this->getBackupFiles();
        
        return view('admin.backups.index', compact('backups'));
    }

    /**
     * Create a new database backup
     */
    public function create()
    {
        try {
            $filename = 'backup_' . date('Y-m-d_His') . '.sql';
            $backupPath = storage_path('app/backups');
            
            // Create backups directory if it doesn't exist
            if (!file_exists($backupPath)) {
                mkdir($backupPath, 0755, true);
            }
            
            $filepath = $backupPath . '/' . $filename;
            
            // Use PHP-based backup (works on all systems)
            $this->createPhpBackup($filepath);
            
            if (!file_exists($filepath) || filesize($filepath) === 0) {
                throw new \Exception('Backup file was not created or is empty');
            }
            
            // Compress the backup
            $zipFilename = str_replace('.sql', '.zip', $filename);
            $zipPath = $backupPath . '/' . $zipFilename;
            
            $zip = new ZipArchive();
            if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
                $zip->addFile($filepath, $filename);
                $zip->close();
                
                // Delete uncompressed SQL file
                unlink($filepath);
            }
            
            return redirect()->route('admin.backups.index')
                ->with('success', 'Database backup created successfully!');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.backups.index')
                ->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    /**
     * Create backup using PHP (works on all systems)
     */
    private function createPhpBackup($filepath)
    {
        $dbName = config('database.connections.mysql.database');
        
        // Get all tables
        $tables = DB::select('SHOW TABLES');
        $tableKey = 'Tables_in_' . $dbName;
        
        $sql = "-- JobOne.in Database Backup\n";
        $sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
        $sql .= "-- Database: {$dbName}\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n";
        $sql .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
        $sql .= "SET time_zone = \"+00:00\";\n\n";
        
        foreach ($tables as $table) {
            $tableName = $table->$tableKey;
            
            // Get CREATE TABLE statement
            $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
            $sql .= "\n-- Table structure for table `{$tableName}`\n";
            $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
            $sql .= $createTable[0]->{'Create Table'} . ";\n\n";
            
            // Get table data
            $rows = DB::table($tableName)->get();
            
            if ($rows->count() > 0) {
                $sql .= "-- Dumping data for table `{$tableName}`\n";
                
                foreach ($rows as $row) {
                    $values = [];
                    foreach ($row as $value) {
                        if ($value === null) {
                            $values[] = 'NULL';
                        } else {
                            $values[] = "'" . addslashes($value) . "'";
                        }
                    }
                    
                    $columns = array_keys((array)$row);
                    $sql .= "INSERT INTO `{$tableName}` (`" . implode('`, `', $columns) . "`) VALUES (" . implode(', ', $values) . ");\n";
                }
                
                $sql .= "\n";
            }
        }
        
        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
        
        // Write to file
        file_put_contents($filepath, $sql);
    }

    /**
     * Download a backup file
     */
    public function download($filename)
    {
        $filepath = storage_path('app/backups/' . $filename);
        
        if (!file_exists($filepath)) {
            return redirect()->route('admin.backups.index')
                ->with('error', 'Backup file not found');
        }
        
        return response()->download($filepath);
    }

    /**
     * Delete a backup file
     */
    public function delete($filename)
    {
        $filepath = storage_path('app/backups/' . $filename);
        
        if (file_exists($filepath)) {
            unlink($filepath);
            
            return redirect()->route('admin.backups.index')
                ->with('success', 'Backup deleted successfully');
        }
        
        return redirect()->route('admin.backups.index')
            ->with('error', 'Backup file not found');
    }

    /**
     * Restore database from backup
     */
    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|string'
        ]);
        
        try {
            $filename = $request->backup_file;
            $filepath = storage_path('app/backups/' . $filename);
            
            if (!file_exists($filepath)) {
                throw new \Exception('Backup file not found');
            }
            
            // Extract SQL file from ZIP
            $zip = new ZipArchive();
            $extractPath = storage_path('app/backups/temp_' . time());
            
            if (!file_exists($extractPath)) {
                mkdir($extractPath, 0755, true);
            }
            
            if ($zip->open($filepath) === TRUE) {
                $zip->extractTo($extractPath);
                $zip->close();
                
                // Find the SQL file
                $sqlFile = null;
                $files = scandir($extractPath);
                foreach ($files as $file) {
                    if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                        $sqlFile = $extractPath . '/' . $file;
                        break;
                    }
                }
                
                if (!$sqlFile) {
                    throw new \Exception('SQL file not found in backup');
                }
                
                // Restore database using PHP
                $this->restorePhpBackup($sqlFile);
                
                // Clean up temp files - remove all files first, then directory
                if (file_exists($sqlFile)) {
                    unlink($sqlFile);
                }
                
                // Remove all files in temp directory
                $tempFiles = scandir($extractPath);
                foreach ($tempFiles as $file) {
                    if ($file !== '.' && $file !== '..') {
                        $filePath = $extractPath . '/' . $file;
                        if (is_file($filePath)) {
                            unlink($filePath);
                        } elseif (is_dir($filePath)) {
                            $this->removeDirectory($filePath);
                        }
                    }
                }
                
                // Remove temp directory
                if (is_dir($extractPath)) {
                    rmdir($extractPath);
                }
                
                // Clear all caches
                Artisan::call('cache:clear');
                Artisan::call('config:clear');
                Artisan::call('route:clear');
                Artisan::call('view:clear');
                
                return redirect()->route('admin.backups.index')
                    ->with('success', 'Database restored successfully! Please re-login.');
                    
            } else {
                throw new \Exception('Failed to extract backup file');
            }
            
        } catch (\Exception $e) {
            return redirect()->route('admin.backups.index')
                ->with('error', 'Restore failed: ' . $e->getMessage());
        }
    }

    /**
     * Recursively remove directory
     */
    private function removeDirectory($dir)
    {
        if (is_dir($dir)) {
            $files = scandir($dir);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $path = $dir . '/' . $file;
                    if (is_dir($path)) {
                        $this->removeDirectory($path);
                    } else {
                        unlink($path);
                    }
                }
            }
            rmdir($dir);
        }
    }

    /**
     * Restore backup using PHP (works on all systems)
     */
    private function restorePhpBackup($filepath)
    {
        // Read SQL file
        $sql = file_get_contents($filepath);
        
        if (empty($sql)) {
            throw new \Exception('Backup file is empty');
        }
        
        // Get database connection
        $connection = DB::connection();
        $pdo = $connection->getPdo();
        
        // Disable foreign key checks
        $pdo->exec('SET FOREIGN_KEY_CHECKS=0');
        
        // Split SQL into individual statements more carefully
        $statements = [];
        $currentStatement = '';
        
        $lines = explode("\n", $sql);
        
        foreach ($lines as $line) {
            $line = trim($line);
            
            // Skip comments and empty lines
            if (empty($line) || str_starts_with($line, '--') || str_starts_with($line, '/*') || str_starts_with($line, '*')) {
                continue;
            }
            
            $currentStatement .= ' ' . $line;
            
            // Check if statement ends with semicolon
            if (str_ends_with($line, ';')) {
                $statement = trim($currentStatement);
                if (!empty($statement)) {
                    $statements[] = $statement;
                }
                $currentStatement = '';
            }
        }
        
        // Execute each statement
        $executed = 0;
        $failed = 0;
        $skipped = 0;
        
        foreach ($statements as $statement) {
            $statement = trim($statement);
            
            // Skip empty statements and comments
            if (empty($statement) || str_starts_with($statement, '--') || str_starts_with($statement, '/*')) {
                $skipped++;
                continue;
            }
            
            // Skip DROP TABLE statements - they delete data!
            if (stripos($statement, 'DROP TABLE') !== false) {
                \Log::warning('Skipped DROP TABLE statement for safety');
                $skipped++;
                continue;
            }
            
            try {
                \Log::debug('Executing SQL: ' . substr($statement, 0, 80));
                $pdo->exec($statement);
                $executed++;
            } catch (\Exception $e) {
                $failed++;
                \Log::warning('Restore statement failed: ' . substr($statement, 0, 100) . '... Error: ' . $e->getMessage());
            }
        }
        
        // Re-enable foreign key checks
        $pdo->exec('SET FOREIGN_KEY_CHECKS=1');
        
        \Log::info("Restore completed: {$executed} executed, {$failed} failed, {$skipped} skipped");
    }

    /**
     * Upload and restore backup
     */
    public function upload(Request $request)
    {
        $request->validate([
            'backup_upload' => 'required|file|mimes:zip|max:512000' // Max 500MB
        ]);
        
        try {
            $file = $request->file('backup_upload');
            $filename = 'uploaded_' . date('Y-m-d_His') . '.zip';
            $backupPath = storage_path('app/backups');
            
            if (!file_exists($backupPath)) {
                mkdir($backupPath, 0755, true);
            }
            
            $file->move($backupPath, $filename);
            
            return redirect()->route('admin.backups.index')
                ->with('success', 'Backup file uploaded successfully! You can now restore it.');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.backups.index')
                ->with('error', 'Upload failed: ' . $e->getMessage());
        }
    }

    /**
     * Get list of backup files
     */
    private function getBackupFiles()
    {
        $backupPath = storage_path('app/backups');
        
        if (!file_exists($backupPath)) {
            return [];
        }
        
        $files = scandir($backupPath);
        $backups = [];
        
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'zip') {
                $filepath = $backupPath . '/' . $file;
                $backups[] = [
                    'filename' => $file,
                    'size' => $this->formatBytes(filesize($filepath)),
                    'date' => date('Y-m-d H:i:s', filemtime($filepath))
                ];
            }
        }
        
        // Sort by date descending
        usort($backups, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        
        return $backups;
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
