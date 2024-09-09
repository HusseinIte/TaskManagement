<?php

namespace App\Models;

use App\Enums\Priority;
use App\Enums\RoleUser;
use App\Enums\TaskStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'priority',
        'due_date',
        'status',
        'assigned_to',
        'created_by'
    ];
    protected $table = 'user_tasks';
    protected $primaryKey = 'task_id';


    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'updated_on';
    protected function casts(): array
    {
        return [
            'priority' => Priority::class,
            'status' => TaskStatus::class

        ];
    }
    public function setDueDateAttribute($value)
    {
        $this->attributes['due_date'] = $value;
    }
    public function getDueDateAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

}
