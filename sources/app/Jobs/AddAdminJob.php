<?php

namespace App\Jobs;

use App\Repository\TournamentAdminRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddAdminJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $attributes;
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function handle(): void
    {
        $repository = new TournamentAdminRepository();
        if (empty($repository->isAdmin($this->attributes))) {
            $repository->store($this->attributes);
        }
    }
}
