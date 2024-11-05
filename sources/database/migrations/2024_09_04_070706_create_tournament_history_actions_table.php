<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

require_once dirname(__DIR__, 2) . '/app/Domain/Constants/FieldConst.php';
require_once dirname(__DIR__, 2) . '/app/Domain/Constants/EntitiesConst.php';
require_once dirname(__DIR__, 2) . '/app/Domain/Constants/ActionConst.php';


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(TABLE_TOURNAMENT_HISTORY_ACTIONS, function (Blueprint $table) {
            $table->id(FIELD_ID);
            $table->integer(FIELD_TOURNAMENT_ID);
            $table->integer(FIELD_TOURNAMENT_ADMIN_ID);
            $table->enum(FIELD_STATUS, [
                STATUS_REPLACEMENT,
                STATUS_CREATED,
                STATUS_DISQUALIFICATION,
            ]);
            $table->string(FIELD_DESCRIPTION, 255); // DESCRIPTION_REPLACEMENT, DESCRIPTION_CREATED, DESCRIPTION_DISQUALIFICATION
            $table->date(FIELD_CURRENT_DATE)
                ->nullable(false);
            $table->time(FIELD_CURRENT_TIME)
                ->nullable(false);
            $table->timestamp(FIELD_CREATED_AT);
            $table->timestamp(FIELD_UPDATED_AT);
            $table->timestamp(FIELD_DELETED_AT)
                ->nullable();
            // Связи
            $table->foreign(FIELD_TOURNAMENT_ID)
                ->on(TABLE_TOURNAMENTS)
                ->references(FIELD_ID);
            $table->foreign(FIELD_TOURNAMENT_ADMIN_ID)
                ->on(TABLE_TOURNAMENT_ADMINS) // TODO: надо прокинуть в реквесты админа а не юзера
                ->references(FIELD_ID);
            // Индексы
            $table->index([
                FIELD_ID,
                FIELD_TOURNAMENT_ID,
                FIELD_TOURNAMENT_ADMIN_ID
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournament_history_actions');
    }
};
