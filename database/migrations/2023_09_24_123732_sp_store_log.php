<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private $spName = 'Logger';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared(
            "CREATE PROCEDURE $this->spName
            (
                Username VARCHAR(100),
                Action ENUM('INSERT', 'UPDATE', 'DELETE'),
                Log TEXT
            )
            MODIFIES SQL DATA
            BEGIN
                INSERT INTO log (username, action, log)
                VALUES (Username, Action, Log);
            END;"
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS $this->spName");
    }
};
