<?php

namespace App\Models;

use Database\Factories\EventFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'slug',
    'summary',
    'description',
    'location',
    'format',
    'status',
    'visibility',
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

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'registration_closes_at' => 'datetime',
            'capacity' => 'integer',
        ];
    }
}
