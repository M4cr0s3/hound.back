<?php

use App\Models\Issue;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('issue_assigns', function (Blueprint $table) {
            $table->foreignIdFor(User::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(Issue::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->primary('user_id', 'issue_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('issue_assigns');
    }
};
