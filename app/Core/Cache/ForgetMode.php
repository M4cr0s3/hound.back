<?php

namespace App\Core\Cache;

enum ForgetMode: string
{
    case ALL = 'all';
    case ID = 'id';
}
