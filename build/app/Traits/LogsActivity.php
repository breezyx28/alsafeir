<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->logActivity('إنشاء');
        });

        static::updated(function ($model) {
            $model->logActivity('تعديل');
        });

        static::deleted(function ($model) {
            $model->logActivity('حذف');
        });
    }

    protected function logActivity($action)
    {
        ActivityLog::create([
            'action'      => $action,
            'description' => class_basename($this) . " {$action}",
            'model_type'  => get_class($this),
            'model_id'    => $this->id,
            'user_id'     => Auth::id(),
            
        ]);
    }
}
