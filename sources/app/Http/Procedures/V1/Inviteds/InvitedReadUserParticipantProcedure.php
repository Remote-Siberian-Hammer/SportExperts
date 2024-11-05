<?php

declare(strict_types=1);

namespace App\Http\Procedures\V1\Inviteds;

use App\Domain\Abstracts\AbstractProcedure;
use App\Http\Requests\Inviteds\InvitedReadUserParticipantRequest;
use App\Http\Resources\Inviteds\InvitedResource;
use App\Repository\InvitedRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

require_once dirname(__DIR__, 4) . '/Domain/Constants/ProcedureNameConst.php';

class InvitedReadUserParticipantProcedure extends AbstractProcedure
{
    public static string $name = PROCEDURE_INVITE_READ_USER;
    private InvitedRepository $invitedRepository;

    public function __construct(InvitedRepository $invitedRepository)
    {
        $this->invitedRepository = $invitedRepository;
    }

    /**
     * @param InvitedReadUserParticipantRequest $request
     * @return JsonResponse
     */
    public function handle(InvitedReadUserParticipantRequest $request): JsonResponse
    {
        define('ATTRIBUTES', $request->validated());
        $repository = $this->invitedRepository->findByUserId(ATTRIBUTES);

        return new JsonResponse(
            data: [
                FIELD_ID => self::identifier(),
                FIELD_ATTRIBUTES => new InvitedResource($repository),
                ...self::meta($request, ATTRIBUTES)
            ],
            status: Response::HTTP_CREATED
        );
    }
}
