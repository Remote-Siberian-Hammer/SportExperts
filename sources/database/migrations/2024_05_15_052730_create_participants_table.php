<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->integer('events_id')
                ->nullable(false);
            $table->integer('users_id')
                ->nullable(false);
            $table->uuid('team_key')
                ->nullable();
            $table->uuid('key')
                ->unique()
                ->nullable(false);
            $table->string("first_name", 255)
                ->nullable(false);
            $table->string("last_name", 255)
                ->nullable(false);
            $table->date('birth_date')
                ->nullable();
            $table->string('email', 255)
                ->unique()
                ->nullable();
            $table->string('phone', 18)
                ->unique()
                ->nullable();
            $table->enum('gender', ['Мужчина', 'Женщина'])
                ->nullable();
            $table->string('location', 255)
                ->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')
                ->nullable();
            // Связи
            $table->foreign('events_id')
                ->on('events')
                ->references('id')
                ->onDelete("CASCADE");
            $table->foreign('users_id')
                ->on('users')
                ->references('id')
                ->onDelete("CASCADE");
            $table->foreign('team_key')
                ->on('teams')
                ->references('key')
                ->onDelete("CASCADE");
            // Индексы
            $table->index([
                'id',
                'events_id',
                'users_id',
                'team_key',
                'email',
                'phone',
                'gender',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
