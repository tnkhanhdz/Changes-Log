<?php

namespace ChangesLog\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChangesLog extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'action',
        'model',
        'record_id',
        'changes',
        'changed_by',
        'time',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'changes' => 'array',
        'time' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(config('changeslog.user'), 'changed_by')->withTrashed();
    }

    /**
     * @param $relation
     * @param $model
     * @return LengthAwarePaginator
     */
    public function getHistoryList($relation, $model): LengthAwarePaginator
    {
        return $this->with('changedBy', $relation)
            ->where('model', $model)
            ->whereHas($relation, function ($query) {
                $query->withTrashed()->changesLogFilter();
            })
            ->orderBy('time', 'DESC')
            ->paginate(config('changeslog.pagination_limit'));
    }
}
