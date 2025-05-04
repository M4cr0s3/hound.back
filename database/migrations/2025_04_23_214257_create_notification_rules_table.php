<?php

use App\Models\Project;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Project::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->string('event_type');
            $table->string('trigger_type');
            $table->json('trigger_params');
            $table->json('channels');
            $table->timestamps();

            $table->unique(['project_id', 'event_type', 'trigger_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_rules');
    }
};
