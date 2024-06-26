<?php

declare(strict_types=1);

namespace App\Http\Procedures\V1\Options;

use App\Http\Requests\Options\StoreOptionRequest;
use App\Http\Resources\Options\OptionResource;
use App\Repository\OptionRepository;
use Illuminate\Http\JsonResponse;
use Sajya\Server\Procedure;

class OptionStoreProcedure extends Procedure
{
    /**
     * The name of the procedure that is used for referencing.
     *
     * @var string
     */
    public static string $name = 'OptionStoreProcedure';

    private OptionRepository $operation;

    public function __construct(OptionRepository $operation) {
        $this->operation = $operation;
    }


    public function handle(StoreOptionRequest $request)
    {
        return new JsonResponse(
            data: new OptionResource(
                $this->operation->store(
                    $request->validated()
                )
            ),
            status: 201
        );
    }
}
