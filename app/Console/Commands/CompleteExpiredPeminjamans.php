<?php

namespace App\Console\Commands;

use App\Models\Peminjaman;
use Illuminate\Console\Command;

class CompleteExpiredPeminjamans extends Command
{
    protected $signature = 'peminjaman:complete-expired';

    protected $description = 'Mark approved loans as completed after their end date';

    public function handle(): int
    {
        $updated = Peminjaman::query()
            ->whereIn('status', ['disetujui', 'aktif'])
            ->whereDate('tanggal_selesai', '<', now()->toDateString())
            ->update([
                'status' => 'selesai',
                'updated_at' => now(),
            ]);

        $this->info("Marked {$updated} expired loan(s) as completed.");

        return self::SUCCESS;
    }
}
