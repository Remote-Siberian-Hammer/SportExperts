<?php

namespace App\Http\Resources\Tournaments;

use App\Http\Resources\TournamentValues\TournamentValueCollection;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

require_once dirname(__DIR__, 3) . '/Domain/Constants/EntitiesConst.php';
require_once dirname(__DIR__, 3) . '/Domain/Constants/FieldConst.php';

class TournamentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $tournament_values = DB::table(TABLE_TOURNAMENT_VALUES)->where([
            FIELD_TOURNAMENT_ID => $this->id
        ])->get();
        $collectData = new TournamentValueCollection($tournament_values);

        return [
            FIELD_ID                => $this->id,
            FIELD_KEY               => $this->key,
            FIELD_EVENT_KEY         => $this->event_key,
            TABLE_EVENTS            => Event::where([FIELD_KEY => $this->event_key])->first(),
            FIELD_STAGE             => $this->stage,
            TABLE_TOURNAMENT_VALUES => $collectData->resource,
            FIELD_CREATED_AT        => $this->created_at,
            FIELD_UPDATED_AT        => $this->updated_at,
            FIELD_DELETED_AT        => $this->deleted_at,
        ];
    }
}
