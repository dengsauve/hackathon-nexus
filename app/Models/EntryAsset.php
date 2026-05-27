<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['project_entry_id', 'uploaded_by', 'disk', 'path', 'original_name', 'mime_type', 'size'])]
class EntryAsset extends Model
{
    /**
     * @return BelongsTo<ProjectEntry, $this>
     */
    public function entry(): BelongsTo
    {
        return $this->belongsTo(ProjectEntry::class, 'project_entry_id');
    }
}
