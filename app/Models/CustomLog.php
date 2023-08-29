<?php

namespace App\Models;

use Monolog\Logger;
use Psr\Log\LoggerTrait;

class CustomLog extends Logger
{
    use LoggerTrait;
    public function __construct(){
        parent::__construct('',[],[],null);
    }
    public function info($message, array $context = []): void
    {
        $this->addRecord(static::INFO, (string) $message, $context);
    }
}
