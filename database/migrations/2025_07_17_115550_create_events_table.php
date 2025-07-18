<?php

use App\Enums\EventStatus;
use App\Models\Event;
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
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            $table->text('title');
            $table->text('description')->nullable();
            $table->dateTime('start_time');
            $table->text('location')->nullable();
            $table->text('organizer')->nullable();
            $table->integer('capacity')->default(0);
            $table->boolean('is_public')->default(true); 
            $table->enum('status', array_column(EventStatus::cases(), 'value'))->default(EventStatus::PENDING->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
