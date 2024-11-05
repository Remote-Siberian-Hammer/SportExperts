<?php

declare(strict_types=1);

namespace App\Http\Procedures\V1\Tournaments;

use App\Domain\Abstracts\AbstractProcedure;
use App\Http\Requests\Tournaments\TournamentReadRequest;
use App\Http\Resources\Tournaments\TournamentResource;
use App\Repository\TournamentRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

require_once dirname(__DIR__, 4) . '/Domain/Constants/ProcedureNameConst.php';
require_once dirname(__DIR__, 4) . '/Domain/Constants/FieldConst.php';

class TournamentReadProcedure extends AbstractProcedure
{
    public static string $name = PROCEDURE_TOURNAMENT_READ;
    private TournamentRepository $tournamentRepository;

    public function __construct(TournamentRepository $tournamentRepository)
    {
        $this->tournamentRepository = $tournamentRepository;
    }

    /**
     * @param TournamentReadRequest $request
     * @return JsonResponse
     */
    public function handle(TournamentReadRequest $request): JsonResponse
    {
        define('ATTRIBUTES', $request->validated());
        $tournamentRepository = $this->tournamentRepository->findByTournamentKey(ATTRIBUTES[FIELD_EVENT_KEY]);
        $tournamentRepository->sortBy(FIELD_STAGE);

        $tournaments = [];
        foreach ($tournamentRepository as $item) {
            $tournaments[$item[FIELD_STAGE]][] = new TournamentResource($item);
        }

        return new JsonResponse(
            data: [
                FIELD_ID => self::identifier(),
                FIELD_ATTRIBUTES => $tournaments,
                ...self::meta($request, ATTRIBUTES)
            ],
            status: Response::HTTP_CREATED
        );
    }
}
