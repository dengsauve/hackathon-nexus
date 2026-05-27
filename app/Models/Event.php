<?php

namespace App\Models;

use Database\Factories\EventFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'owner_id',
    'slug',
    'summary',
    'description',
    'location',
    'format',
    'status',
    'visibility',
    'qr_code_path',
    'judging_finalized_at',
    'starts_at',
    'ends_at',
    'registration_closes_at',
    'capacity',
])]
class Event extends Model
{
    /** @use HasFactory<EventFactory> */
    use HasFactory;

    public const FORMATS = ['in-person', 'online', 'hybrid'];

    public const PUBLIC_STATUSES = ['published', 'live'];

    public const LIFECYCLE_STATUSES = ['draft', 'published', 'live', 'ended'];

    public const VISIBILITIES = ['public', 'unlisted', 'private'];

    public function isPubliclyViewable(): bool
    {
        return in_array($this->status, self::PUBLIC_STATUSES, true)
            && in_array($this->visibility, ['public', 'unlisted'], true);
    }

    /**
     * @param  Builder<Event>  $query
     * @return Builder<Event>
     */
    public function scopePubliclyDiscoverable(Builder $query): Builder
    {
        return $query
            ->whereIn('status', self::PUBLIC_STATUSES)
            ->where('visibility', 'public');
    }

    /**
     * @return BelongsToMany<User, $this>
     */
    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('status')
            ->withTimestamps();
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * @return BelongsToMany<Team, $this>
     */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class)
            ->withPivot(['registered_by', 'status'])
            ->withTimestamps();
    }

    /**
     * @return HasMany<AssistanceRequest, $this>
     */
    public function assistanceRequests(): HasMany
    {
        return $this->hasMany(AssistanceRequest::class);
    }

    /**
     * @return HasMany<ProjectEntry, $this>
     */
    public function entries(): HasMany
    {
        return $this->hasMany(ProjectEntry::class);
    }

    /**
     * @return HasMany<ScoringRubric, $this>
     */
    public function rubrics(): HasMany
    {
        return $this->hasMany(ScoringRubric::class);
    }

    /**
     * @return HasMany<JudgeAssignment, $this>
     */
    public function judgeAssignments(): HasMany
    {
        return $this->hasMany(JudgeAssignment::class);
    }

    public function publicUrl(): string
    {
        return route('events.show', $this);
    }

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'registration_closes_at' => 'datetime',
            'judging_finalized_at' => 'datetime',
            'capacity' => 'integer',
        ];
    }
}
