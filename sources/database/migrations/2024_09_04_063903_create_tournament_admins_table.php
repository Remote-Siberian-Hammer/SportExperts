<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

require_once dirname(__DIR__, 2) . '/app/Domain/Constants/FieldConst.php';
require_once dirname(__DIR__, 2) . '/app/Domain/Constants/EntitiesConst.php';


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(TABLE_TOURNAMENT_ADMINS, function (Blueprint $table) {
            $table->id(FIELD_ID);
            $table->integer(FIELD_TOURNAMENT_ID);
            $table->integer(FIELD_USER_ID);
            $table->timestamp(FIELD_CREATED_AT);
            $table->timestamp(FIELD_UPDATED_AT);
            $table->timestamp(FIELD_DELETED_AT)
                ->nullable();
            // Связи
            $table->foreign(FIELD_TOURNAMENT_ID)
                ->on(TABLE_TOURNAMENTS)
                ->references(FIELD_ID);
            $table->foreign(FIELD_USER_ID)
                ->on(TABLE_USERS)
                ->references(FIELD_ID);
            // Индексы
            $table->index([
                FIELD_ID,
                FIELD_TOURNAMENT_ID,
                FIELD_USER_ID
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournament_admins');
    }
};
