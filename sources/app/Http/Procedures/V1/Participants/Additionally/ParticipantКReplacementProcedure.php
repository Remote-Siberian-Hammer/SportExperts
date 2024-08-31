<?php

declare(strict_types=1);

namespace App\Http\Procedures\V1\Participants\Additionally;

use App\Http\Requests\Participants\Additionally\ParticipantReplacementRequest;
use App\Http\Resources\TournamentValues\TournamentValueResource;
use App\Repository\EventRepository;
use App\Repository\TournamentValueRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Sajya\Server\Procedure;

class ParticipantКReplacementProcedure extends Procedure
{
    /**
     * The name of the procedure that is used for referencing.
     *
     * @var string
     */
    public static string $name = 'ParticipantКReplacementProcedure';
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
     * Execute the procedure.
     *
     * @param ParticipantReplacementRequest $request
     *
     * @return JsonResponse
     */
    public function handle(ParticipantReplacementRequest $request): JsonResponse
    {
        $attributes = $request->validated();
        $event = $this->eventRepository->findById($attributes['event_id']);
        return new JsonResponse(
            data: new TournamentValueResource($this->tournamentValueRepository->replaceParticipant($event, $attributes)),
            status: Response::HTTP_CREATED
        );
    }
}
