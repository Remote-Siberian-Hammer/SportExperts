<?php

declare(strict_types=1);

namespace App\Http\Procedures\V1\Tournaments;

use Illuminate\Http\Request;
use Sajya\Server\Procedure;

class TournamentDestroyProcedure extends Procedure
{
    /**
     * The name of the procedure that is used for referencing.
     *
     * @var string
     */
    public static string $name = 'TournamentDestroyProcedure';

    /**
     * Execute the procedure.
     *
     * @param Request $request
     *
     * @return array|string|integer
     */
    public function handle(Request $request)
    {
        // write your code
    }
}
