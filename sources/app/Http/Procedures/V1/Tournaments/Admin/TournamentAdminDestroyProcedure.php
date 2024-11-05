<?php

declare(strict_types=1);

namespace App\Http\Procedures\V1\Tournaments\Admin;

use App\Domain\Abstracts\AbstractProcedure;
use App\Http\Requests\TournamentAdmin\TournamentAdminReadRequest;
use App\Http\Resources\TournamentAdmin\TournamentAdminResource;
use App\Repository\TournamentAdminRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Sajya\Server\Procedure;

require_once dirname(__DIR__, 5) . '/Domain/Constants/ErrorMessageConst.php';
require_once dirname(__DIR__, 5) . '/Domain/Constants/ProcedureNameConst.php';
require_once dirname(__DIR__, 5) . '/Domain/Constants/FieldConst.php';

class TournamentAdminDestroyProcedure extends AbstractProcedure
{
    /**
     * The name of the procedure that is used for referencing.
     *
     * @var string
     */
    public static string $name = PROCEDURE_TOURNAMENT_ADMIN_DESTROY;
    private TournamentAdminRepository $tournamentAdminRepository;
    public function __construct(TournamentAdminRepository $tournamentAdminRepository)
    {
        $this->tournamentAdminRepository = $tournamentAdminRepository;
    }

    /**
     * TournamentAdminUpdateProcedure the handle.
     *
     * @param TournamentAdminReadRequest $request
     *
     * @return JsonResponse
     */
    public function handle(TournamentAdminReadRequest $request): JsonResponse
    {
        define("ATTRIBUTES", $request->validated());
        $entity = $this->tournamentAdminRepository->findById(ATTRIBUTES[FIELD_ID]);
        if ($this->tournamentAdminRepository->destroy($entity))
        {
            return new JsonResponse(
                data: [
                    FIELD_ID => self::identifier(),
                    FIELD_ATTRIBUTES => new TournamentAdminResource($entity),
                    ...self::meta($request, ATTRIBUTES)
                ],
                status: Response::HTTP_CREATED
            );
        }

        return new JsonResponse(
            data: [
                FIELD_ID => self::identifier(),
                FIELD_ATTRIBUTES => [
                    FIELD_MESSAGE => MESSAGE_NOT_DESTROY
                ],
                ...self::meta($request, ATTRIBUTES)
            ],
            status: Response::HTTP_CREATED
        );
    }
}
