<?php

namespace App\Http\Resources\TournamentValues;

use App\Models\Participant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

require_once dirname(__DIR__, 3) . '/Domain/Constants/EntitiesConst.php';
require_once dirname(__DIR__, 3) . '/Domain/Constants/FieldConst.php';

class TournamentValueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $participants_A = Participant::where([FIELD_KEY => $this->participants_A])->first();
        $participants_B = Participant::where([FIELD_KEY => $this->participants_B])->first();
        return [
            FIELD_ID                    => $this->id,
            FIELD_TOURNAMENT_ID         => $this->tournament_id,
            FIELD_PARTICIPANTS_A        => $participants_A,
            FIELD_PARTICIPANTS_B        => $participants_B,
            FIELD_PARTICIPANTS_PASSES   => $this->participants_passes,
            TABLE_USERS                 => [
                $participants_A ? User::find($participants_A->user_id) : null,
                $participants_B ? User::find($participants_B->user_id) : null,
            ],
            FIELD_CREATED_AT            => $this->created_at,
            FIELD_UPDATED_AT            => $this->updated_at,
        ];
    }
}
