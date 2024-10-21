<?php

declare(strict_types=1);

namespace App\Http\Procedures\V1\Participants\Additionally;

use App\Domain\Abstracts\AbstractProcedure;
use App\Http\Requests\Participants\Additionally\ParticipantDiscvaleficationRequest;
use App\Jobs\AddTournamentHistoryJob;
use App\Repository\EventRepository;
use App\Repository\TournamentValueRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

require_once dirname(__DIR__, 5) . '/Domain/Constants/ProcedureNameConst.php';
require_once dirname(__DIR__, 5) . '/Domain/Constants/FieldConst.php';

class ParticipantDisqualificationProcedure extends AbstractProcedure
{
    public static string $name = PROCEDURE_PARTICIPANT_DISQUALIFICATION;
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
     * @param ParticipantDiscvaleficationRequest $request
     * @return JsonResponse
     */
    public function handle(ParticipantDiscvaleficationRequest $request): JsonResponse
    {
        define('ATTRIBUTES', $request->validated());
        $event = $this->eventRepository->findByKey(ATTRIBUTES[FIELD_EVENT_KEY]);
        $repository = $this->tournamentValueRepository->removeParticipant($event, ATTRIBUTES);

        AddTournamentHistoryJob::dispatch([
            FIELD_TOURNAMENT_ID         => $repository->tournament_id,
            FIELD_TOURNAMENT_ADMIN_ID   => ATTRIBUTES[FIELD_ADMIN_ID],
            FIELD_STATUS                => STATUS_DISQUALIFICATION,
            FIELD_USER_ID               => ATTRIBUTES[FIELD_USER_ID],
            FIELD_DESCRIPTION           => DESCRIPTION_DISQUALIFICATION,
            FIELD_CURRENT_DATE          => Carbon::today(),
            FIELD_CURRENT_TIME          => Carbon::now()->format('H:i:s'),
        ]);
        return new JsonResponse(
            data: /*new TournamentValueResource(*/
            [
                FIELD_ID => self::identifier(),
                FIELD_ATTRIBUTES => $repository,
                ...self::meta($request, ATTRIBUTES)
            ],
            /*),*/
            status: Response::HTTP_CREATED
        );
    }
}
