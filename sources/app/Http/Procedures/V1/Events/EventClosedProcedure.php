<?php

declare(strict_types=1);

namespace App\Http\Procedures\V1\Events;

use App\Domain\Abstracts\AbstractProcedure;
use App\Http\Requests\Events\EventClosedRequest;
use App\Http\Resources\Events\EventResource;
use App\Repository\EventRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

require_once dirname(__DIR__, 4) . '/Domain/Constants/EventStatusesConst.php';
require_once dirname(__DIR__, 4) . '/Domain/Constants/FieldConst.php';
require_once dirname(__DIR__, 4) . '/Domain/Constants/ProcedureNameConst.php';

class EventClosedProcedure extends AbstractProcedure
{
    public static string $name = PROCEDURE_EVENT_CLOSED;
    protected EventRepository $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * @param EventClosedRequest $request
     *
     * @return JsonResponse
     */
    public function handle(EventClosedRequest $request): JsonResponse
    {
        define('ATTRIBUTES', $request->validated());
        $repository = $this->eventRepository->updateStatus(ATTRIBUTES, EVENT_NO_ACTIVE);

        return new JsonResponse(
            data: [
                FIELD_ID => self::identifier(),
                FIELD_ATTRIBUTES => new EventResource($repository),
                ...self::meta($request, ATTRIBUTES)
            ],
            status: Response::HTTP_CREATED
        );
    }
}
