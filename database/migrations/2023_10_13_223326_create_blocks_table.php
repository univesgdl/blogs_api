<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blocks', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['text', 'quote', 'image', 'video'])->default('text');
            $table->text('content')->nullable();
            $table->string('width')->nullable();
            $table->string('height')->nullable();
            $table->enum('align', ['start', 'center', 'end'])->default('start');
            $table->integer('position')->default(0);
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('blocks');
    }
};
