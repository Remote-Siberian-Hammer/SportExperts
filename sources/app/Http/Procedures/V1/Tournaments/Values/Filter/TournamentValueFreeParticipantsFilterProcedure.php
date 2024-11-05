<?php

declare(strict_types=1);

namespace App\Http\Procedures\V1\Tournaments\Values\Filter;

use App\Domain\Abstracts\AbstractProcedure;
use App\Http\Requests\Filter\TournamentValueFreeParticipantsFilterRequest;
use App\Http\Resources\Participants\ParticipantCollection;
use App\Http\Resources\TournamentValues\TournamentValueCollection;
use App\Repository\EventRepository;
use App\Repository\TournamentValueRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Sajya\Server\Procedure;

require_once dirname(__DIR__, 6) . '/Domain/Constants/ProcedureNameConst.php';
require_once dirname(__DIR__, 6) . '/Domain/Constants/FieldConst.php';

class TournamentValueFreeParticipantsFilterProcedure extends AbstractProcedure
{
    public static string $name = PROCEDURE_TOURNAMENT_VALUE_FREE_PARTICIPANTS;
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
     * @param TournamentValueFreeParticipantsFilterRequest $request
     * @return JsonResponse
     */
    public function handle(TournamentValueFreeParticipantsFilterRequest $request): JsonResponse
    {
        define("ATTRIBUTES", $request->validated());
        $event = $this->eventRepository->findByKey(ATTRIBUTES[FIELD_EVENT_KEY]);
        $repository = $this->tournamentValueRepository->freeParticipants($event, ATTRIBUTES);
        $collectData = new ParticipantCollection($repository);

        return new JsonResponse(
            data: [
                FIELD_ID => self::identifier(),
                FIELD_ATTRIBUTES =>  $collectData->resource,
                ...self::meta($request, ATTRIBUTES)
            ],
            status: Response::HTTP_CREATED
        );
    }
}
