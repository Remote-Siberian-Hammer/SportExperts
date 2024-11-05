<?php

namespace App\Http\Resources\TournamentAdmin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

require_once dirname(__DIR__, 3) . '/Domain/Constants/FieldConst.php';
require_once dirname(__DIR__, 3) . '/Domain/Constants/EntitiesConst.php';

class TournamentAdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            FIELD_ID                => $this->id,
            FIELD_TOURNAMENT_ID     => $this->tournament_id,
            TABLE_USERS             => User::find($this->user_id),
            FIELD_CREATED_AT        => $this->created_at,
            FIELD_UPDATED_AT        => $this->updated_at,
            FIELD_DELETED_AT        => $this->deleted_at,
        ];
    }
}
