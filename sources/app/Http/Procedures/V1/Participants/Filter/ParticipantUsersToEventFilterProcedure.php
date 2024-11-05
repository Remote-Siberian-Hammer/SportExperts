<?php

declare(strict_types=1);

namespace App\Http\Procedures\V1\Participants\Filter;

use App\Domain\Abstracts\AbstractProcedure;
use App\Http\Requests\Filter\ParticipantsUserToEventFilterRequest;
use App\Http\Resources\Participants\ParticipantCollection;
use App\Repository\ParticipantRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Sajya\Server\Procedure;

require_once dirname(__DIR__, 5) . '/Domain/Constants/ProcedureNameConst.php';
require_once dirname(__DIR__, 5) . '/Domain/Constants/FieldConst.php';

class ParticipantUsersToEventFilterProcedure extends AbstractProcedure
{
    public static string $name = PROCEDURE_PARTICIPANT_FILTER_PARTICIPANT_USER_TO_EVENT;
    private ParticipantRepository $participantRepository;

    public function __construct(ParticipantRepository $participantRepository)
    {
        $this->participantRepository = $participantRepository;
    }

    /**
     * @param ParticipantsUserToEventFilterRequest $request
     * @return JsonResponse
     */
    public function handle(ParticipantsUserToEventFilterRequest $request)
    {
        define('ATTRIBUTES', $request->validated());
        $repository = $this->participantRepository->findByEventKey(ATTRIBUTES[FIELD_EVENT_KEY]);
        $collectData = new ParticipantCollection($repository);

        return new JsonResponse(
            data: [
                FIELD_ID => self::identifier(),
                FIELD_ATTRIBUTES => $collectData->resource,
                ...self::meta($request, ATTRIBUTES)
            ],
            status: Response::HTTP_CREATED
        );
    }
}
