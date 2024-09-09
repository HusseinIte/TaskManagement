<?php

namespace App\Models;

use App\Enums\Priority;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'priority',
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

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
