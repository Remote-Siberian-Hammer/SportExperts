<?php

declare(strict_types=1);

namespace App\Http\Procedures\V1\Teams;

use App\Domain\Abstracts\AbstractProcedure;
use App\Http\Requests\Teams\TeamReadRequest;
use App\Http\Resources\Teams\TeamResource;
use App\Repository\TeamRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Sajya\Server\Procedure;

require_once dirname(__DIR__, 4) . '/Domain/Constants/ProcedureNameConst.php';
require_once dirname(__DIR__, 4) . '/Domain/Constants/FieldConst.php';

class TeamDestroyProcedure extends AbstractProcedure
{
    public static string $name = PROCEDURE_TEAM_DESTROY;
    private TeamRepository $teamRepository;

    public function __construct(TeamRepository $teamRepository) {
        $this->teamRepository = $teamRepository;
    }

    /**
     * @param TeamReadRequest $request
     * @return JsonResponse
     */
    public function handle(TeamReadRequest $request): JsonResponse
    {
        define('ATTRIBUTES', $request->validated());
        $team = $this->teamRepository->findById(ATTRIBUTES[FIELD_ID]);
        $repository = $this->teamRepository->destroy($team);

        return new JsonResponse(
            data: [
                FIELD_ID => self::identifier(),
                FIELD_ATTRIBUTES => new TeamResource($repository),
                ...self::meta($request, ATTRIBUTES)
            ],
            status: Response::HTTP_CREATED
        );
    }
}
