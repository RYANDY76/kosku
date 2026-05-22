<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('kamars', function (Blueprint $table) {
            $table->string('kode_kamar', 40)->nullable()->after('tipe_kamar');
            $table->unsignedInteger('lantai')->default(1)->after('kode_kamar');
            $table->decimal('luas_kamar', 5, 2)->nullable()->after('lantai');
            $table->unsignedInteger('harga_harian')->nullable()->after('harga');
            $table->unsignedInteger('harga_tahunan')->nullable()->after('harga_harian');
            $table->string('foto')->nullable()->after('catatan');
        });
    }

    public function down(): void
    {
        Schema::table('kamars', function (Blueprint $table) {
            $table->dropColumn(['kode_kamar','lantai','luas_kamar','harga_harian','harga_tahunan','foto']);
        });
    }
};
