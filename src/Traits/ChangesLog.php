<?php

namespace ChangesLog\Traits;

use ChangesLog\Models\ChangesLog as ChangesLogModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait ChangesLog
{
    /**
     * @return void
     */
    public static function bootChangesLog(): void
    {
        static::created(function ($model) {
            $changes = $model->attributes;
            $updated = $changes['updated_at'] ?? formatDate('now');
            unset($changes['updated_at']);
            unset($changes['created_at']);

            ChangesLogModel::create([
                'action' => 'create',
                'model' => get_class($model),
                'record_id' => $model->id,
                'changes' => $changes,
                'time' => $updated,
                'changed_by' => getUser()->id ?? null,
            ]);
        });

        static::updated(function ($model) {
            $changes = $model->changes;

            if (array_key_exists('deleted_at', $changes)) {
                return;
            }

            $updated = $changes['updated_at'] ?? formatDate('now');
            unset($changes['updated_at']);

            ChangesLogModel::create([
                'action' => 'edit',
                'model' => get_class($model),
                'record_id' => $model->id,
                'changes' => $changes,
                'time' => $updated,
                'changed_by' => getUser()->id ?? null,
            ]);
        });

        static::deleted(function ($model) {
            $updated = $model['deleted_at'] ?? formatDate('now');

            ChangesLogModel::create([
                'action' => 'deleted',
                'model' => get_class($model),
                'record_id' => $model->id,
                'changes' => [],
                'time' => $updated,
                'changed_by' => getUser()->id ?? null,
            ]);
        });

        static::restored(function ($model) {
            $updated = formatDate('now');

            ChangesLogModel::create([
                'action' => 'restored',
                'model' => get_class($model),
                'record_id' => $model->id,
                'changes' => [],
                'time' => $updated,
                'changed_by' => getUser()->id ?? null,
            ]);
        });
    }

    /**
     * @return HasMany
     */
    public function changesLogs(): HasMany
    {
        return $this->hasMany(ChangesLogModel::class, 'record_id')
            ->with('changedBy')
            ->orderBy('time', 'DESC');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeChangesLogFilter($query): mixed
    {
        return $query;
    }
}
