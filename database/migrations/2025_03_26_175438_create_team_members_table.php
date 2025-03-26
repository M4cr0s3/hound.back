<?php

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('team_members', function (Blueprint $table) {
            $table->foreignIdFor(Team::class)
                ->constrained('teams')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignIdFor(User::class)
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
