<?php

namespace App\Repository;

use App\Domain\Interfaces\Repositories\LCRUD_OperationInterface;
use App\Models\Option;
use App\Repository\Traits\CreateQueryTrait;
use App\Repository\Traits\DestroyQueryTrait;
use App\Repository\Traits\FindByEventKeyTrait;
use App\Repository\Traits\FindByUserIdTrait;
use App\Repository\Traits\GetByKeyTrait;
use App\Repository\Traits\ListQueryTrait;
use App\Repository\Traits\ReadQueryTrait;
use App\Repository\Traits\UpdateQueryTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

final class OptionRepository implements LCRUD_OperationInterface
{
    use ListQueryTrait;
    use CreateQueryTrait;
    use ReadQueryTrait;
    use UpdateQueryTrait;
    use DestroyQueryTrait;
    use GetByKeyTrait;
    use FindByEventKeyTrait;
    use FindByUserIdTrait;

    protected Model $model;
    public function __construct(Option $model = new Option())
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function queryExists(array $attributes, string $mode='one'): mixed
    {
        $query = $this->model::where($attributes);
        return  $mode === 'one' ? $query->first() : $query->get();
    }
}
