<?php

declare(strict_types=1);

namespace App\Http\Procedures\V1\Tournaments\Admin;

use App\Domain\Abstracts\AbstractProcedure;
use App\Http\Requests\TournamentAdmin\TournamentAdminListRequest;
use App\Http\Resources\TournamentAdmin\TournamentAdminCollection;
use App\Repository\TournamentAdminRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Sajya\Server\Procedure;

class TournamentAdminListProcedure extends AbstractProcedure
{
    /**
     * The name of the procedure that is used for referencing.
     *
     * @var string
     */
    public static string $name = 'TournamentAdminListProcedure';
    private TournamentAdminRepository $tournamentAdminRepository;

    public function __construct(TournamentAdminRepository $tournamentAdminRepository)
    {
        $this->tournamentAdminRepository = $tournamentAdminRepository;
    }

    /**
     * TournamentAdminUpdateProcedure the handle.
     *
     * @param TournamentAdminListRequest $request
     *
     * @return JsonResponse
     */
    public function handle(TournamentAdminListRequest $request): JsonResponse
    {
        define("ATTRIBUTES", $request->validated());
        $repository = $this->tournamentAdminRepository->list();
        $dataCollection = new TournamentAdminCollection($repository);

        return new JsonResponse(
            data: [
                FIELD_ID => self::identifier(),
                FIELD_ATTRIBUTES => $dataCollection->resource,
                ...self::meta($request, ATTRIBUTES)
            ],
            status: Response::HTTP_CREATED
        );
    }
}
