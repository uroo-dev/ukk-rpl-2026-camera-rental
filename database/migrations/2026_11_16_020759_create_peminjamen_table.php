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
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->date('tgl_pinjam');
            $table->date('tgl_kembali');
            $table->enum('status', ['Menunggu', 'Diaprove', 'Dipinjam', 'Dikembalikan', 'Dibatalkan'])->default('Menunggu');
            $table->decimal('subtotal')->nullable();
            $table->string('nama_bukti')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
