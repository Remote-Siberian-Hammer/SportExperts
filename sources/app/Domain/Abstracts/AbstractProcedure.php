<?php

namespace App\Domain\Abstracts;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Sajya\Server\Procedure;

abstract class AbstractProcedure extends Procedure
{
    /**
     * @return string
     */
    public static function identifier(): string
    {
        return Str::uuid()->toString();
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return array[]
     */
    public static function meta(Request $request, array $attributes): array
    {
        return [
            'meta' => [
                'copyright' => 'Remote Sibberian Hammer Студия разработки ПО © 2024',
                'request'   => [
                    'identifier'    => Str::uuid()->toString(),
                    'method'        => $request->method(),
                    'path'          => $request->decodedPath(),
                    'attributes'    => $attributes,
                    'timestamp'     => date(DATE_RFC2822),
                ]
            ]
        ];
    }
}
