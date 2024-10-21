<?php

namespace App\Repository;

use App\Domain\Interfaces\Repositories\LCRUD_OperationInterface;
use App\Models\Event;
use App\Models\Tournament;
use App\Models\TournamentAdmin;
use App\Repository\Traits\CreateQueryTrait;
use App\Repository\Traits\DestroyQueryTrait;
use App\Repository\Traits\ListQueryTrait;
use App\Repository\Traits\ReadQueryTrait;
use App\Repository\Traits\UpdateQueryTrait;
use Illuminate\Database\Eloquent\Model;

require_once dirname(__DIR__) . '/Domain/Constants/FieldConst.php';

final class TournamentAdminRepository implements LCRUD_OperationInterface
{
    use ListQueryTrait;
    use CreateQueryTrait;
    use ReadQueryTrait;
    use UpdateQueryTrait;
    use DestroyQueryTrait;

    protected Model $model;
    public function __construct(TournamentAdmin $model = new TournamentAdmin()) {
        $this->model = $model;
    }

    public function eventToAdminList(array $attributes) {
        $tournament = Tournament::where($attributes)->first();
        return $this->model::where([FIELD_TOURNAMENT_ID => $tournament->id])->get();
    }



    public function isAdmin(array $attributes) {
        return $this->model::where($attributes)->first();
    }
}
