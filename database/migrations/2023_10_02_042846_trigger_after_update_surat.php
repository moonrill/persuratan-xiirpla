<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private $triggerName = 'trigger_after_update_surat';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared(
            "CREATE TRIGGER $this->triggerName
            AFTER UPDATE ON surat FOR EACH ROW
            BEGIN
                DECLARE v_username VARCHAR(100);
                DECLARE j_surat VARCHAR(100);
                DECLARE old_j_surat VARCHAR(100);

                SELECT username INTO v_username FROM user WHERE id = NEW.id_user;
                SELECT jenis_surat INTO j_surat FROM jenis_surat WHERE id = NEW.id_jenis_surat;
                SELECT jenis_surat INTO old_j_surat FROM jenis_surat WHERE id = OLD.id_jenis_surat;

                SET @ringkasan := IFNULL(NEW.ringkasan, 'NULL');
                SET @file := IFNULL(NEW.file, 'NULL');

                CALL Logger(v_username, 'UPDATE',
                    CONCAT(
                        'from: ', '{',
                        'id_surat: ', OLD.id,
                        ', jenis_surat: ', old_j_surat,
                        ', tanggal_surat: ', OLD.tanggal_surat,
                        ', ringkasan: ', OLD.ringkasan,
                        ', file: ', OLD.file,
                        '} ',
                        'to: ', '{',
                        'id_surat: ', NEW.id,
                        ', jenis_surat: ', j_surat,
                        ', tanggal_surat: ', NEW.tanggal_surat,
                        ', ringkasan: ', @ringkasan,
                        ', file: ', @file,
                        '}'
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
