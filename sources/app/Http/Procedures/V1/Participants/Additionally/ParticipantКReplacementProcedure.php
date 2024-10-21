<?php

declare(strict_types=1);

namespace App\Http\Procedures\V1\Participants\Additionally;

use App\Domain\Abstracts\AbstractProcedure;
use App\Http\Requests\Participants\Additionally\ParticipantReplacementRequest;
use App\Http\Resources\TournamentValues\TournamentValueResource;
use App\Jobs\AddTournamentHistoryJob;
use App\Repository\EventRepository;
use App\Repository\TournamentRepository;
use App\Repository\TournamentValueRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

require_once dirname(__DIR__, 5) . '/Domain/Constants/ProcedureNameConst.php';
require_once dirname(__DIR__, 5) . '/Domain/Constants/FieldConst.php';

class ParticipantКReplacementProcedure extends AbstractProcedure
{
    public static string $name = PROCEDURE_PARTICIPANT_REPLACEMENT;
    private EventRepository $eventRepository;
    private TournamentValueRepository $tournamentValueRepository;

    public function __construct(
        EventRepository $eventRepository,
        TournamentValueRepository $tournamentValueRepository
    )
    {
        $this->eventRepository = $eventRepository;
        $this->tournamentValueRepository = $tournamentValueRepository;
    }

    /**
     * @param ParticipantReplacementRequest $request
     * @return JsonResponse
     */
    public function handle(ParticipantReplacementRequest $request): JsonResponse
    {
        define('ATTRIBUTES', $request->validated());
        $event = $this->eventRepository->findByKey(ATTRIBUTES[FIELD_EVENT_KEY]);
        $repository = $this->tournamentValueRepository->replaceParticipant($event, ATTRIBUTES);
        AddTournamentHistoryJob::dispatch([
            FIELD_TOURNAMENT_ID         => $repository->tournament_id,
            FIELD_TOURNAMENT_ADMIN_ID   => ATTRIBUTES[FIELD_ADMIN_ID],
            FIELD_STATUS                => STATUS_REPLACEMENT,
            FIELD_USER_ID               => ATTRIBUTES[FIELD_USER_ID],
            FIELD_NEW_PARTICIPANT_KEY   => ATTRIBUTES[FIELD_NEW_PARTICIPANT_KEY],
            FIELD_DESCRIPTION           => DESCRIPTION_REPLACEMENT,
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
