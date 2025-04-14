<?php

use App\Models\HealthCheckEndpoint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('health_check_results', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(HealthCheckEndpoint::class)->constrained()->cascadeOnDelete();
            $table->float('response_time');
            $table->integer('status_code');
            $table->boolean('success');
            $table->text('response_body')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('health_check_results');
    }
};
