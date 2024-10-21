<?php

declare(strict_types=1);

namespace App\Http\Procedures\V1\Participants;

use App\Domain\Abstracts\AbstractProcedure;
use App\Http\Requests\Participants\StoreParticipantReqest;
use App\Jobs\AddAdminJob;
use App\Jobs\AddTournamentHistoryJob;
use App\Repository\EventRepository;
use App\Repository\ParticipantRepository;
use App\Repository\TournamentRepository;
use App\Services\Tournaments\AlgorithmRanging;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Sajya\Server\Procedure;

require_once dirname(__DIR__, 4) . '/Domain/Constants/ParticipantMessageConst.php';
require_once dirname(__DIR__, 4) . '/Domain/Constants/ProcedureNameConst.php';
require_once dirname(__DIR__, 4) . '/Domain/Constants/FieldConst.php';
require_once dirname(__DIR__, 4) . '/Domain/Constants/ActionConst.php';

class ParticipantStoreProcedure extends AbstractProcedure
{
    public static string $name = PROCEDURE_PARTICIPANT_STORE;
    private AlgorithmRanging $algorithmRanging;
    private ParticipantRepository $participantRepository;
    private EventRepository $eventRepository;
    private TournamentRepository $tournament;

    public function __construct(
        ParticipantRepository $participantRepository,
        AlgorithmRanging $algorithmRanging,
        EventRepository $eventRepository,
        TournamentRepository $tournament
    )
    {
        $this->participantRepository        = $participantRepository;
        $this->algorithmRanging             = $algorithmRanging;
        $this->eventRepository              = $eventRepository;
        $this->tournament                   = $tournament;
    }

    /**
     * @param StoreParticipantReqest $request
     * @return JsonResponse
     */
    public function handle(StoreParticipantReqest $request): JsonResponse
    {
        $attributes = $request->validated();
        $attributes[FIELD_EVENT_ID]            = (integer)$attributes[FIELD_EVENT_ID];
        $attributes[FIELD_USER_ID]             = (integer)$attributes[FIELD_USER_ID];
        $attributes[FIELD_INVITED_USER_ID]     = (integer)$attributes[FIELD_INVITED_USER_ID];
        $attributes[FIELD_KEY]                 = Str::uuid()->toString();

        $users = $this->participantRepository->userList($attributes[FIELD_EVENT_ID]);

        foreach ($users as $value)
        {
            if ($value->user_id === $attributes[FIELD_USER_ID])
            {
                return new JsonResponse(
                    data: [
                        FIELD_ID => self::identifier(),
                        FIELD_ATTRIBUTES => ALREADY_PARTICIPANT,
                        ...self::meta($request, $attributes)
                    ],
                    status: Response::HTTP_CREATED
                );
            }
        }

        $participantStore                   = $this->participantRepository->store($attributes);
        $event                              = $this->eventRepository->findById((integer)$attributes[FIELD_EVENT_ID]);
        $tournament                         = $this->tournament->findByTournamentKey($event->key);

        $this->algorithmRanging->ranging($event, $tournament[0], $this->participantRepository, $participantStore);

        AddTournamentHistoryJob::dispatch([
            FIELD_TOURNAMENT_ID         => $tournament[0]->id,
            FIELD_TOURNAMENT_ADMIN_ID   => $attributes[FIELD_ADMIN_ID],
            FIELD_STATUS                => STATUS_CREATED,
            FIELD_USER_ID               => $attributes[FIELD_USER_ID],
            FIELD_CURRENT_DATE          => Carbon::today(),
            FIELD_CURRENT_TIME          => Carbon::now()->format('H:i:s'),
        ]);

        AddAdminJob::dispatch([
            FIELD_TOURNAMENT_ID         => $tournament[0]->id,
            FIELD_USER_ID               => $attributes[FIELD_ADMIN_ID],
        ]);

        return new JsonResponse(
            data: [
                FIELD_ID => self::identifier(),
                FIELD_ATTRIBUTES => $participantStore->toArray(),
                ...self::meta($request, $attributes)
            ],
            status: Response::HTTP_CREATED
        );
    }
}
