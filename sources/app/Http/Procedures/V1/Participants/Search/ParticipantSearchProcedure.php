<?php

declare(strict_types=1);

namespace App\Http\Procedures\V1\Participants\Search;

use App\Domain\Abstracts\AbstractProcedure;
use App\Http\Requests\Participants\Search\ParticipantSearchRequest;
use App\Http\Resources\Participants\ParticipantCollection;
use App\Repository\EventRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

require_once dirname(__DIR__, 5) . '/Domain/Constants/ProcedureNameConst.php';
require_once dirname(__DIR__, 5) . '/Domain/Constants/FieldConst.php';
require_once dirname(__DIR__, 5) . '/Domain/Constants/EntitiesConst.php';

class ParticipantSearchProcedure extends AbstractProcedure
{
    /**
     * The name of the procedure that is used for referencing.
     *
     * @var string
     */
    public static string $name = 'ParticipantSearchProcedure';
    protected EventRepository $eventRepository;
    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * Execute the procedure.
     *
     * @param ParticipantSearchRequest $request
     *
     * @return JsonResponse
     */
    public function handle(ParticipantSearchRequest $request): JsonResponse
    {
        define('ATTRIBUTES', $request->validated());

        $event = $this->eventRepository->findByKey(ATTRIBUTES[FIELD_EVENT_KEY]);

        $querySearch = DB::table(TABLE_PARTICIPANTS)
            ->select('*')
            ->join(
                TABLE_USERS,
                TABLE_PARTICIPANTS . '.' . FIELD_USER_ID,
                '=',
                TABLE_USERS . '.' . FIELD_ID
            );

        if (!empty(ATTRIBUTES[FIELD_FIRST_NAME])) {
            $querySearch->where(TABLE_USERS . '.' . FIELD_FIRST_NAME, 'like', '%' . ATTRIBUTES[FIELD_FIRST_NAME] . '%');
        }

        if (!empty(ATTRIBUTES[FIELD_LAST_NAME])) {
            $querySearch->where(TABLE_USERS . '.' . FIELD_LAST_NAME, 'like', '%' . ATTRIBUTES[FIELD_LAST_NAME] . '%');
        }

        if (!empty(ATTRIBUTES[FIELD_BIRTH_DATE])) {
            $querySearch->where(TABLE_USERS . '.' . FIELD_BIRTH_DATE, '=', ATTRIBUTES[FIELD_BIRTH_DATE]);
        }

        if (!empty(ATTRIBUTES[FIELD_BIRTH_DATE])) {
            $querySearch->where(TABLE_USERS . '.' . FIELD_BIRTH_DATE, '=', ATTRIBUTES[FIELD_BIRTH_DATE]);
        }

        if (!empty(ATTRIBUTES[FIELD_GENDER])) {
            $querySearch->where(TABLE_USERS . '.' . FIELD_GENDER, '=', ATTRIBUTES[FIELD_GENDER]);
        }

        if (!empty(ATTRIBUTES[FIELD_PHONE])) {
            $querySearch->where(TABLE_USERS . '.' . FIELD_PHONE, '=', ATTRIBUTES[FIELD_PHONE]);
        }

        if (!empty(ATTRIBUTES[FIELD_WIDTH])) {
            $querySearch->leftJoin(
                TABLE_OPTIONS,
                TABLE_USERS . '.' . FIELD_ID,
                '=',
                TABLE_OPTIONS . '.' . FIELD_USER_ID
            )->where([
                FIELD_ENTITY => 'user',
                FIELD_NAME => 'Weight',
                FIELD_VALUE => ATTRIBUTES[FIELD_WIDTH]
            ]);
        }

        if (!empty(ATTRIBUTES[FIELD_HEIGHT])) {
            $querySearch->leftJoin(
                TABLE_OPTIONS,
                TABLE_USERS . '.' . FIELD_ID,
                '=',
                TABLE_OPTIONS . '.' . FIELD_USER_ID
            )->where([
                FIELD_ENTITY => 'user',
                FIELD_NAME => 'Height',
                FIELD_VALUE => ATTRIBUTES[FIELD_HEIGHT]
            ]);
        }

        $querySearch->where([FIELD_EVENT_ID => $event->id]);

        return new JsonResponse(
            data: [
                FIELD_ID => self::identifier(),
                FIELD_ATTRIBUTES => $querySearch->get(),
                ...self::meta($request, ATTRIBUTES)
            ],
            status: Response::HTTP_CREATED
        );
    }
}
