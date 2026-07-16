<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class BackupController extends Controller
{
    private string $backupPath;

    public function __construct()
    {
        $this->backupPath = storage_path('app/backups');
        if (!File::isDirectory($this->backupPath)) {
            File::makeDirectory($this->backupPath, 0755, true);
        }
    }

    public function create(Request $request)
    {
        $config = config('database.connections.mysql');
        $filename = 'backup_' . now()->format('Y-m-d_His') . '.sql';
        $filepath = $this->backupPath . '/' . $filename;

        $host = escapeshellarg($config['host']);
        $port = escapeshellarg($config['port']);
        $database = escapeshellarg($config['database']);
        $username = escapeshellarg($config['username']);
        $password = $config['password'];

        $mysqldump = 'C:\xampp\mysql\bin\mysqldump.exe';

        $cmd = "{$mysqldump} -h {$host} -P {$port} -u {$username}";

        if (!empty($password)) {
            $cmd .= ' -p' . escapeshellarg($password);
        }

        $cmd .= " --single-transaction --routines --triggers {$database} > \"{$filepath}\" 2>&1";

        exec($cmd, $output, $returnCode);

        if ($returnCode !== 0 || !File::exists($filepath) || filesize($filepath) === 0) {
            $error = implode("\n", $output);
            return back()->withErrors(['backup' => "Error al crear backup: {$error}"]);
        }

        $metaPath = str_replace('.sql', '.json', $filepath);
        File::put($metaPath, json_encode([
            'filename' => $filename,
            'user' => auth()->user()->name,
            'user_id' => auth()->id(),
            'created_at' => now()->toDateTimeString(),
            'size' => filesize($filepath),
        ], JSON_PRETTY_PRINT));

        return back()->with('success', "Backup creado exitosamente: {$filename}");
    }

    public function list()
    {
        $files = collect(File::files($this->backupPath))
            ->filter(fn($f) => $f->getExtension() === 'sql')
            ->map(function ($file) {
                $metaPath = str_replace('.sql', '.json', $file->getPathname());
                $meta = File::exists($metaPath)
                    ? json_decode(File::get($metaPath), true)
                    : [
                        'filename' => $file->getFilename(),
                        'user' => 'Desconocido',
                        'created_at' => $file->getMTime(),
                        'size' => $file->getSize(),
                    ];

                return [
                    'filename' => $file->getFilename(),
                    'user' => $meta['user'] ?? 'Desconocido',
                    'created_at' => $meta['created_at'] ?? date('Y-m-d H:i:s', $file->getMTime()),
                    'size' => $this->formatSize($meta['size'] ?? $file->getSize()),
                ];
            })
            ->sortByDesc('created_at')
            ->values();

        return response()->json($files);
    }

    public function download(string $filename)
    {
        $filepath = $this->backupPath . '/' . basename($filename);

        if (!File::exists($filepath)) {
            abort(404, 'Backup no encontrado.');
        }

        return response()->download($filepath, $filename, [
            'Content-Type' => 'application/sql',
        ]);
    }

    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:sql|max:51200',
        ]);

        $file = $request->file('backup_file');
        $content = file_get_contents($file->getRealPath());

        if (empty(trim($content))) {
            return back()->withErrors(['backup_file' => 'El archivo de backup está vacío.']);
        }

        $config = config('database.connections.mysql');

        $tempFile = tempnam(sys_get_temp_dir(), 'restore_');
        file_put_contents($tempFile, $content);

        $host = escapeshellarg($config['host']);
        $port = escapeshellarg($config['port']);
        $database = escapeshellarg($config['database']);
        $username = escapeshellarg($config['username']);
        $password = $config['password'];

        $mysql = 'C:\xampp\mysql\bin\mysql.exe';

        $cmd = "{$mysql} -h {$host} -P {$port} -u {$username}";

        if (!empty($password)) {
            $cmd .= ' -p' . escapeshellarg($password);
        }

        $cmd .= " {$database} < \"{$tempFile}\" 2>&1";

        exec($cmd, $output, $returnCode);

        @unlink($tempFile);

        if ($returnCode !== 0) {
            $error = implode("\n", $output);
            return back()->withErrors(['backup_file' => "Error al restaurar: {$error}"]);
        }

        return back()->with('success', 'Base de datos restaurada exitosamente.');
    }

    public function destroy(string $filename)
    {
        $filepath = $this->backupPath . '/' . basename($filename);
        $metaPath = str_replace('.sql', '.json', $filepath);

        if (File::exists($filepath)) {
            File::delete($filepath);
        }
        if (File::exists($metaPath)) {
            File::delete($metaPath);
        }

        return response()->json(['message' => 'Backup eliminado.']);
    }

    private function formatSize(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 1) . ' MB';
        } elseif ($bytes >= 1024) {
            return round($bytes / 1024, 1) . ' KB';
        }
        return $bytes . ' B';
    }
}
