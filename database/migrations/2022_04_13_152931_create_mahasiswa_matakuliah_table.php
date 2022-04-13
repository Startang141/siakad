<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMahasiswaMatakuliahTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mahasiswa_matakuliah', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mahasiswa_id'); //menambahkan kolom mahasiwa_id
            $table->foreign('mahasiswa_id')->references('id_mahasiswa')->on('mahasiswa');//menambahkan foreign key di kolom mahasiswa_id
            $table->unsignedBigInteger('matakuliah_id'); //menambahkan kolom matakuliah_id
            $table->foreign('matakuliah_id')->references('id')->on('matakuliah'); //menambahkan foreign key di kolom matakuliah_id
            $table->string('nilai', 1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mahasiswa_matakuliah', function (Blueprint $table){
            $table->dropForeign(['mahasiswa_id']);
            $table->dropForeign(['matakuliah_id']);
        });
    }
}