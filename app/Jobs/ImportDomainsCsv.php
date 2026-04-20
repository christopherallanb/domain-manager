<?php

namespace App\Jobs;

use App\Models\Domain;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportDomainsCsv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $path;

    /**
     * Create a new job instance.
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (!file_exists($this->path)) {
            return;
        }

        $rows = array_map('str_getcsv', file($this->path));
        if (count($rows) === 0) return;
        $header = array_map('trim', array_shift($rows));

        foreach ($rows as $row) {
            if (!is_array($row)) continue;
            $data = @array_combine($header, $row);
            if (!$data || empty($data['name'])) continue;

            Domain::updateOrCreate(['name' => $data['name']], [
                'registrar' => $data['registrar'] ?? null,
                'expiration_date' => $data['expiration_date'] ?? null,
                'annual_cost' => isset($data['annual_cost']) ? (float)$data['annual_cost'] : 0,
                'notes' => $data['notes'] ?? null,
                'auto_renew' => isset($data['auto_renew']) ? (bool)$data['auto_renew'] : false,
            ]);
        }
    }
}
