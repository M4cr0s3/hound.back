<?php

use App\Models\Project;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_hourly_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Project::class)->constrained()->cascadeOnDelete();
            $table->dateTime('hour');
            $table->unsignedInteger('total_events')->default(0);
            $table->unsignedInteger('error_count')->default(0);
            $table->unsignedInteger('warning_count')->default(0);

            $table->unique(['project_id', 'hour']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_hourly_stats');
    }
};
