<?php

namespace App\Enums;

enum RoleUser: string
{
    case User = 'User';
    case Manager = 'Manager';
    case Admin = 'Admin';
}
