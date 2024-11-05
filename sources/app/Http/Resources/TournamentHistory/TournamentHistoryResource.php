<?php

namespace App\Http\Resources\TournamentHistory;

use App\Http\Resources\Users\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TournamentHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            FIELD_ID            => $this->id,
            FIELD_TOURNAMENT_ID => $this->tournament_id,
            FIELD_ADMINS        => new UserResource(User::find($this->tournament_admin_id)),
            FIELD_STATUS        => $this->status,
            FIELD_DESCRIPTION   => $this->description,
            FIELD_CURRENT_DATE  => $this->current_date,
            FIELD_CURRENT_TIME  => $this->current_time,
            FIELD_CREATED_AT    => $this->created_at,
            FIELD_UPDATED_AT    => $this->updated_at,
            FIELD_DELETED_AT    => $this->deleted_at,
        ];
    }
}
