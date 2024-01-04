<?php

namespace ChangesLog\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomChangesLog extends \ChangesLog\Models\ChangesLog
{
// Manual adding relationships to each model
//    public function user(): BelongsTo
//    {
//        return $this->belongsTo(User::class, 'record_id')->withTrashed();
//    }
}
