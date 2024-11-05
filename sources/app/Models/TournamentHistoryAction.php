<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

require_once dirname(__DIR__, 2) . '/app/Domain/Constants/FieldConst.php';
require_once dirname(__DIR__, 2) . '/app/Domain/Constants/EntitiesConst.php';


class TournamentHistoryAction extends Model
{
    use HasFactory;

    protected $table = TABLE_TOURNAMENT_HISTORY_ACTIONS;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        FIELD_TOURNAMENT_ID,
        FIELD_TOURNAMENT_ADMIN_ID,
        FIELD_STATUS,
        FIELD_DESCRIPTION,
        FIELD_CURRENT_DATE,
        FIELD_CURRENT_TIME,
        FIELD_CREATED_AT,
        FIELD_UPDATED_AT,
        FIELD_DELETED_AT,
    ];
}
