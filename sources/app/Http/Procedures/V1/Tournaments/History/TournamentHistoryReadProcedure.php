<?php

declare(strict_types=1);

namespace App\Http\Procedures\V1\Tournaments\History;

use App\Domain\Abstracts\AbstractProcedure;
use App\Http\Requests\TournamentHistory\TournamentHistoryReadRequest;
use App\Http\Resources\TournamentHistory\TournamentHistoryResource;
use App\Repository\TournamentHistoryActionRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class TournamentHistoryReadProcedure extends AbstractProcedure
{
    public static string $name = 'TournamentHistoryReadProcedure';
    private TournamentHistoryActionRepository $tournamentHistoryActionRepository;
    public function __construct(TournamentHistoryActionRepository $tournamentHistoryActionRepository)
    {
        $this->tournamentHistoryActionRepository = $tournamentHistoryActionRepository;
    }

    /**
     * @param TournamentHistoryReadRequest $request
     * @return JsonResponse
     */
    public function handle(TournamentHistoryReadRequest $request): JsonResponse
    {
        define("ATTRIBUTES", $request->validated());
        $repository = $this->tournamentHistoryActionRepository->findById(ATTRIBUTES['id']);

        return new JsonResponse(
            data: [
                FIELD_ID => self::identifier(),
                FIELD_ATTRIBUTES => new TournamentHistoryResource($repository),
                ...self::meta($request, ATTRIBUTES)
            ],
            status: Response::HTTP_CREATED
        );
    }
}
