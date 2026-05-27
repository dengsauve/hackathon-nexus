<?php

namespace App\Models;

use Database\Factories\ProjectEntryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

#[Fillable(['event_id', 'team_id', 'created_by', 'title', 'idea', 'description', 'goal_statement', 'github_repository', 'gitlab_repository', 'status', 'submitted_at'])]
class ProjectEntry extends Model
{
    /** @use HasFactory<ProjectEntryFactory> */
    use HasFactory;

    public const DRAFT = 'draft';

    public const SUBMITTED = 'submitted';

    /**
     * @return BelongsTo<Event, $this>
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * @return BelongsTo<Team, $this>
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * @return HasMany<EntryAsset, $this>
     */
    public function assets(): HasMany
    {
        return $this->hasMany(EntryAsset::class);
    }

    /**
     * @return HasMany<EntryScore, $this>
     */
    public function scores(): HasMany
    {
        return $this->hasMany(EntryScore::class);
    }

    public function totalScore(): int
    {
        return (int) $this->scores()->sum('score');
    }

    protected static function booted(): void
    {
        static::deleting(function (ProjectEntry $entry): void {
            $entry->assets->each(fn (EntryAsset $asset) => Storage::disk($asset->disk)->delete($asset->path));
        });
    }

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
        ];
    }
}
