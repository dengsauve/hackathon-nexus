<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['project_entry_id', 'scoring_rubric_id', 'judge_id', 'score', 'notes'])]
class EntryScore extends Model
{
    /**
     * @return BelongsTo<ProjectEntry, $this>
     */
    public function entry(): BelongsTo
    {
        return $this->belongsTo(ProjectEntry::class, 'project_entry_id');
    }
}
