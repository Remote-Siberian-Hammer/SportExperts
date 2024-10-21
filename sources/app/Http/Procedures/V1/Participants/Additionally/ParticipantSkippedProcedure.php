<?php

declare(strict_types=1);

namespace App\Http\Procedures\V1\Participants\Additionally;

use App\Domain\Abstracts\AbstractProcedure;
use App\Http\Requests\Participants\Additionally\ParticipantSkippedRequest;
use App\Http\Resources\TournamentValues\TournamentValueResource;
use App\Jobs\AddTournamentHistoryJob;
use App\Repository\EventRepository;
use App\Repository\TournamentRepository;
use App\Repository\TournamentValueRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

require_once dirname(__DIR__, 5) . '/Domain/Constants/ProcedureNameConst.php';
require_once dirname(__DIR__, 5) . '/Domain/Constants/FieldConst.php';

class ParticipantSkippedProcedure extends AbstractProcedure
{
    public static string $name = PROCEDURE_PARTICIPANT_SKIPPED;
    private EventRepository $eventRepository;
    private TournamentRepository $tournamentRepository;
    private TournamentValueRepository $tournamentValueRepository;

    public function __construct(
        EventRepository $eventRepository,
        TournamentRepository $tournamentRepository,
        TournamentValueRepository $tournamentValueRepository
    )
    {
        $this->eventRepository = $eventRepository;
        $this->tournamentRepository = $tournamentRepository;
        $this->tournamentValueRepository = $tournamentValueRepository;
    }

    /**
     * @param ParticipantSkippedRequest $request
     * @return JsonResponse
     */
    public function handle(ParticipantSkippedRequest $request): JsonResponse
    {
        define('ATTRIBUTES', $request->validated());
        $event = $this->eventRepository->findByKey(ATTRIBUTES[FIELD_EVENT_KEY]);
        $repository = $this->tournamentValueRepository->advanceSkipValue($event, ATTRIBUTES);
        // Увеличение шага
        $tournament = $this->tournamentRepository->findByTournamentKey(ATTRIBUTES[FIELD_EVENT_KEY]);
        $step = $this->tournamentRepository->increaseStep($tournament[count($tournament) - 1], ATTRIBUTES[FIELD_STAGE]);
        // Перенос всех спортсменов с предыдущего шага
        $this->tournamentValueRepository->copyAthlete($step, ATTRIBUTES);

        AddTournamentHistoryJob::dispatch([
            FIELD_TOURNAMENT_ID         => $tournament->id,
            FIELD_TOURNAMENT_ADMIN_ID   => ATTRIBUTES[FIELD_ADMIN_ID],
            FIELD_STATUS                => STATUS_SKIPPED,
            FIELD_USER_ID               => ATTRIBUTES[FIELD_USER_ID],
            FIELD_DESCRIPTION           => DESCRIPTION_SKIPPED,
            FIELD_CURRENT_DATE          => Carbon::today(),
            FIELD_CURRENT_TIME          => Carbon::now()->format('H:i:s'),
        ]);

        return new JsonResponse(
            data: [
                FIELD_ID => self::identifier(),
                FIELD_ATTRIBUTES => new TournamentValueResource($repository),
                ...self::meta($request, ATTRIBUTES)
            ],
            status: Response::HTTP_CREATED
        );
    }
}
