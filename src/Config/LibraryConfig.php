<?php

declare(strict_types=1);

namespace App\Library\Config;

class LibraryConfig{

    public const DEFAULT_BORROW_DAYS = 14;
    public const DAILY_FINE_RATE = 5.00;
    public const MAX_BORROW_LIMIT = 3;
    public const STATUS_BORROWED = 'borrowed';
    public const STATUS_RETURNED = 'returned';
}