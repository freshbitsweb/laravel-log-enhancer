<?php

namespace Freshbitsweb\LaravelLogEnhancer;

use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\WebProcessor;

class LogEnhancer
{
    /**
     * Customize the given logger instance.
     *
     * @param  \Illuminate\Log\Logger  $logger
     * @return void
     */
    public function __invoke($logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            if (config('laravel_log_enhancer.log_request_details')) {
                $handler->pushProcessor(new WebProcessor());
            }

            if (config('laravel_log_enhancer.log_memory_usage')) {
                $handler->pushProcessor(new MemoryUsageProcessor());
            }

            $handler->pushProcessor(new RequestDataProcessor());
        }
    }
}
