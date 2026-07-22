<?php

namespace App\Console\Commands;

use App\Models\ConfiguracionBackupAuto;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:backup-run')]
#[Description('Genera backups por comando, usado para el automatizado.')]
class BackupRun extends Command
{
    protected $signature = 'backup-run';
    protected $description = 'Ejecuta el backup automático';

    public function handle()
    {
        try {
            $this->info('Comando backup:run ejecutado');

            $config = ConfiguracionBackupAuto::first();

            if (!$config || !$config->activo) {
                $this->info('Backup desactivado o sin config');
                \Log::info('Backup desactivado o sin config');
                return;
            }

            if (now()->toDateString() < $config->fecha_inicio) {
                $this->info('Aún no llega la fecha de inicio');
                \Log::info('Aún no llega la fecha de inicio');
                return;
            }

            $intervalo =
                ($config->intervalo_minutos * 60) +
                ($config->intervalo_horas * 3600) +
                ($config->intervalo_dias * 86400);

            if ($intervalo <= 0) {
                \Log::warning('Intervalo inválido');
                return;
            }

            if (!$config->ultimo_backup) {
                $ejecutar = true;
            } else {
                $ejecutar = now()->diffInSeconds($config->ultimo_backup, true) >= $intervalo;
            }

            \Log::info([
                'intervalo' => $intervalo,
                'ultimo_backup' => $config->ultimo_backup,
                'diff' => $config->ultimo_backup
                    ? now()->diffInSeconds($config->ultimo_backup)
                    : null,
                'ejecutar' => $ejecutar
            ]);

            if (!$ejecutar) {
                $this->info('Aún no toca ejecutar backup');
                \Log::info('Aún no toca ejecutar backup');
                return;
            }

            $db = config('database.connections.mysql.database');
            $user = config('database.connections.mysql.username');
            $pass = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');

            $fileName = 'backup_auto_' . now()->format('Y-m-d_H-i-s') . '.sql';
            $path = storage_path('app/backups/' . $fileName);

            if (!file_exists(storage_path('app/backups'))) {
                mkdir(storage_path('app/backups'), 0755, true);
            }

            $command = sprintf(
                'mysqldump --skip-ssl --user=%s --password=%s --host=%s %s > %s 2>&1',
                escapeshellarg($user),
                escapeshellarg($pass),
                escapeshellarg($host),
                escapeshellarg($db),
                escapeshellarg($path)
            );

            exec($command, $output, $result);

            if ($result !== 0) {
                \Log::error('Error al generar backup automático');
                return;
            } else {
                $this->info('Backup automático generado correctamente');
                \Log::info('Backup automático generado correctamente');

                $config->update([
                    'ultimo_backup' => now()
                ]);
            }

        } catch (\Throwable $e) {

            \Log::error('❌ ERROR EN BACKUP: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            $this->error($e->getMessage());
        }
    }
}
