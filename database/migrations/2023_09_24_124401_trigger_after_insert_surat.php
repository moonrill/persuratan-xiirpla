<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private $triggerName = 'trigger_after_insert_surat';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared(
            "CREATE TRIGGER $this->triggerName
            AFTER INSERT ON surat FOR EACH ROW
            BEGIN
                DECLARE v_username VARCHAR(100);
                DECLARE j_surat VARCHAR(100);
                SELECT username INTO v_username FROM user WHERE id = NEW.id_user;
                SELECT jenis_surat INTO j_surat FROM jenis_surat WHERE id = NEW.id_jenis_surat;
                CALL Logger(v_username, 'INSERT',
                    CONCAT(
                        'id: ', NEW.id,
                        ', jenis_surat: ', j_surat,
                        ', tanggal_surat: ', NEW.tanggal_surat,
                        ', ringkasan: ', NEW.ringkasan,
                        ', file: ', NEW.file
                    )
                );
            END;"
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS $this->triggerName");
    }
};
