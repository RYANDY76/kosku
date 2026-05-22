<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nama_kos', 150);
            $table->enum('tipe_kos', ['putra', 'putri', 'campur'])->default('campur');
            $table->text('alamat');
            $table->string('lokasi_area', 100)->nullable();
            $table->text('deskripsi');
            $table->unsignedInteger('harga');
            $table->string('no_wa', 20);
            $table->string('foto')->nullable();
            $table->enum('status', ['tersedia', 'penuh'])->default('tersedia');
            $table->boolean('premium')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kos');
    }
};
