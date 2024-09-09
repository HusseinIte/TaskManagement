<?php

namespace App\Enums;

enum TaskStatus: string
{
    case NEW = 'New';
    case IN_PROGRESS = 'In Progress';
    case COMPLETED = 'Completed';

    // Returns the next state in the sequence
    public function next()
    {
        return match ($this) {
            TaskStatus::NEW => TaskStatus::IN_PROGRESS,
            TaskStatus::IN_PROGRESS => TaskStatus::COMPLETED,
            TaskStatus::COMPLETED => null, // No next state
        };
    }
}
