<?php

namespace Freshbitsweb\LaravelLogEnhancer\Test;

use Illuminate\Log\Logger;
use Monolog\Processor\GitProcessor;
use Monolog\Processor\WebProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Freshbitsweb\LaravelLogEnhancer\RequestDataProcessor;

class LogEnhancerTest extends TestCase
{
    /** @test */
    public function it_adds_request_details_to_logs()
    {
        $logger = $this->app[Logger::class];

        $handlers = $logger->getHandlers();
        foreach ($handlers as $handler) {
            if (config('laravel_log_enhancer.log_git_data')) {
                $this->assertInstanceOf(GitProcessor::class, $handler->popProcessor());
            }

            if (config('laravel_log_enhancer.log_memory_usage')) {
                $this->assertInstanceOf(MemoryUsageProcessor::class, $handler->popProcessor());
            }

            $this->assertInstanceOf(RequestDataProcessor::class, $handler->popProcessor());

            if (config('laravel_log_enhancer.log_request_details')) {
                $this->assertInstanceOf(WebProcessor::class, $handler->popProcessor());
            }
        }
    }
}
