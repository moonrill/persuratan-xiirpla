<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    private $triggerName = 'trigger_after_delete_surat';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared(
            "CREATE TRIGGER $this->triggerName
            AFTER DELETE ON surat FOR EACH ROW
            BEGIN
                DECLARE v_username VARCHAR(100);
                DECLARE j_surat VARCHAR(100);

                SELECT username INTO v_username FROM user WHERE id = OLD.id_user;
                SELECT jenis_surat INTO j_surat FROM jenis_surat WHERE id = OLD.id_jenis_surat;

                SET @ringkasan := IFNULL(OLD.ringkasan, 'NULL');
                SET @file := IFNULL(OLD.file, 'NULL');

                CALL Logger(v_username, 'DELETE',
                    CONCAT(
                        'id_surat: ', OLD.id,
                        ', jenis_surat: ', j_surat,
                        ', tanggal_surat: ', OLD.tanggal_surat,
                        ', ringkasan: ', @ringkasan,
                        ', file: ', @file
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
