<?php

use App\Models\Project;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->uuid('event_id');
            $table->foreignIdFor(Project::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->string('environment');
            $table->string('type');
            $table->string('level');
            $table->text('message');
            $table->string('release');
            $table->jsonb('metadata');
            $table->unsignedInteger('count');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
