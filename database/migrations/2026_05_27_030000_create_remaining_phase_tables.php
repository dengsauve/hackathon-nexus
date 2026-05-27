<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->json('notification_preferences')->nullable()->after('avatar_url');
            $table->string('status')->default('active')->after('notification_preferences')->index();
        });

        Schema::create('project_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('idea')->nullable();
            $table->text('description')->nullable();
            $table->text('goal_statement')->nullable();
            $table->string('github_repository')->nullable();
            $table->string('gitlab_repository')->nullable();
            $table->string('status')->default('draft')->index();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->unique(['event_id', 'team_id']);
        });

        Schema::create('entry_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_entry_id')->constrained()->cascadeOnDelete();
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
            $table->string('disk')->default('local');
            $table->string('path');
            $table->string('original_name');
            $table->string('mime_type');
            $table->unsignedBigInteger('size');
            $table->timestamps();
        });

        Schema::create('scoring_rubrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->unsignedTinyInteger('max_score')->default(10);
            $table->timestamps();
        });

        Schema::create('judge_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('judge_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['event_id', 'judge_id']);
        });

        Schema::create('entry_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_entry_id')->constrained()->cascadeOnDelete();
            $table->foreignId('scoring_rubric_id')->constrained()->cascadeOnDelete();
            $table->foreignId('judge_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('score');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['project_entry_id', 'scoring_rubric_id', 'judge_id']);
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action');
            $table->string('auditable_type')->nullable();
            $table->unsignedBigInteger('auditable_id')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['auditable_type', 'auditable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('entry_scores');
        Schema::dropIfExists('judge_assignments');
        Schema::dropIfExists('scoring_rubrics');
        Schema::dropIfExists('entry_assets');
        Schema::dropIfExists('project_entries');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['notification_preferences', 'status']);
        });
    }
};
