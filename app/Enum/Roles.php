<?php

namespace App\Enum;

enum Roles: string
{
    case Admin = 'admin';
    case Customer = 'customer';
    case Editor = 'editor';
}
