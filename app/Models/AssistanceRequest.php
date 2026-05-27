<?php

namespace App\Models;

use Database\Factories\AssistanceRequestFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['event_id', 'team_id', 'requested_by', 'responded_by', 'subject', 'message', 'status', 'responded_at'])]
class AssistanceRequest extends Model
{
    /** @use HasFactory<AssistanceRequestFactory> */
    use HasFactory;

    public const OPEN = 'open';

    public const IN_PROGRESS = 'in_progress';

    public const RESOLVED = 'resolved';

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
     * @return BelongsTo<User, $this>
     */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    protected function casts(): array
    {
        return [
            'responded_at' => 'datetime',
        ];
    }
}
