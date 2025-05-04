<?php

use App\Models\Issue;
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
        Schema::create('issue_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Issue::class)->constrained()->cascadeOnDelete();
            $table->morphs('assignable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issue_assignments');
    }
};
