<?php

namespace App\Jobs;

use App\Models\Participant;
use App\Models\TournamentAdmin;
use App\Models\User;
use App\Repository\TournamentHistoryActionRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

require_once dirname(__DIR__) . '/Domain/Constants/ActionConst.php';

class AddTournamentHistoryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $attributes;
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $description = '';
        $admin = TournamentAdmin::where([
            FIELD_TOURNAMENT_ID         => $this->attributes[FIELD_TOURNAMENT_ID],
            FIELD_USER_ID               => $this->attributes[FIELD_TOURNAMENT_ADMIN_ID],
            ])->first();
        $athlete = User::find($this->attributes[FIELD_USER_ID]);
        $tournamentHistoryActionRepository = new TournamentHistoryActionRepository();
        switch ($this->attributes[FIELD_STATUS]) {
            case STATUS_CREATED:
                $description = sprintf(
                    DESCRIPTION_CREATED,
                    $athlete->last_name,
                    $athlete->first_name
                );
                break;
            case STATUS_DISQUALIFICATION:
                $description = sprintf(
                    DESCRIPTION_DISQUALIFICATION,
                    $athlete->last_name,
                    $athlete->first_name
                );
                break;
            case STATUS_SKIPPED:
                $description = sprintf(
                    DESCRIPTION_SKIPPED,
                    $athlete->last_name,
                    $athlete->first_name
                );
                break;
            case STATUS_REPLACEMENT:
                $participants = Participant::where([FIELD_KEY => $this->attributes[FIELD_NEW_PARTICIPANT_KEY]])->first();
                $newAthlete = User::find($participants->user_id);
                $description = sprintf(
                    DESCRIPTION_REPLACEMENT,
                    $athlete->last_name,
                    $athlete->first_name,
                    $newAthlete->last_name,
                    $newAthlete->first_name,
                );
                break;
        }
        $tournamentHistoryActionRepository->store([
            FIELD_TOURNAMENT_ID         => $this->attributes[FIELD_TOURNAMENT_ID],
            FIELD_TOURNAMENT_ADMIN_ID   => $admin->id,
            FIELD_STATUS                => $this->attributes[FIELD_STATUS],
            FIELD_DESCRIPTION           => $description,
            FIELD_CURRENT_DATE          => $this->attributes[FIELD_CURRENT_DATE],
            FIELD_CURRENT_TIME          => $this->attributes[FIELD_CURRENT_TIME],
        ]);
    }
}
