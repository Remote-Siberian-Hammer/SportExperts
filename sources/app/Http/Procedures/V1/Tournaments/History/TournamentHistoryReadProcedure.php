<?php

declare(strict_types=1);

namespace App\Http\Procedures\V1\Tournaments\History;

use App\Domain\Abstracts\AbstractProcedure;
use App\Http\Requests\TournamentHistory\TournamentHistoryReadRequest;
use App\Http\Resources\TournamentHistory\TournamentHistoryCollection;
use App\Http\Resources\TournamentHistory\TournamentHistoryResource;
use App\Repository\TournamentHistoryActionRepository;
use App\Repository\TournamentRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

require_once dirname(__DIR__, 5) . '/Domain/Constants/ProcedureNameConst.php';
require_once dirname(__DIR__, 5) . '/Domain/Constants/FieldConst.php';

class TournamentHistoryReadProcedure extends AbstractProcedure
{
    public static string $name = PROCEDURE_TOURNAMENT_HISTORY_READ;
    private TournamentRepository $tournamentRepository;
    private TournamentHistoryActionRepository $tournamentHistoryActionRepository;
    public function __construct(
        TournamentRepository $tournamentRepository,
        TournamentHistoryActionRepository $tournamentHistoryActionRepository
    )
    {
        $this->tournamentRepository = $tournamentRepository;
        $this->tournamentHistoryActionRepository = $tournamentHistoryActionRepository;
    }

    /**
     * @param TournamentHistoryReadRequest $request
     * @return JsonResponse
     */
    public function handle(TournamentHistoryReadRequest $request): JsonResponse
    {
        define("ATTRIBUTES", $request->validated());
        $tournament = $this->tournamentRepository->findByTournamentKey(ATTRIBUTES[FIELD_EVENT_KEY]);
        $repository = $this->tournamentHistoryActionRepository->getTournamentHistory([FIELD_TOURNAMENT_ID => $tournament[0]->id]);
        $collectData = new TournamentHistoryCollection($repository);

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
