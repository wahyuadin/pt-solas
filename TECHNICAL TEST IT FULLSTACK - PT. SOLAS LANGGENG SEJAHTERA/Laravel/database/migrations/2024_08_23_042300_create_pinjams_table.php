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
        Schema::create('pinjams', function (Blueprint $table) {
            $table->uuid('id');
            $table->foreignUuid('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreignUuid('buku_id')->references('id')->on('bukus')->cascadeOnDelete();
            $table->date('tgl_pinjam');
            $table->date('tgl_pengembalian');
            $table->enum('status', ['pending','accept','reject'])->default('pending');
            $table->enum('status_buku', ['dikembalikan','dipinjam','telat'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinjams');
    }
};
