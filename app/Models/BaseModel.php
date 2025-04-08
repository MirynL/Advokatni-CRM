<?php

namespace App\Models;

use Nette\Database\Explorer;
use App\Logger\ActivityLogger;

abstract class BaseModel
{
    public function __construct(
        protected Explorer $db,
        protected ActivityLogger $logger
    ) {}
}