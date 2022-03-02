<?php

namespace Freshbitsweb\LaravelLogEnhancer\Test;

use Freshbitsweb\LaravelLogEnhancer\RequestDataProcessor;
use Illuminate\Log\Logger;
use LogicException;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\WebProcessor;

class LogEnhancerTest extends TestCase
{
    /** @test */
    public function it_adds_all_processor_details_to_the_logs()
    {
        config(['laravel_log_enhancer.log_memory_usage' => true]);
        config(['laravel_log_enhancer.log_request_details' => true]);
        $logger = $this->app[Logger::class];

        $handlers = $logger->getHandlers();
        foreach ($handlers as $handler) {
            $this->assertInstanceOf(RequestDataProcessor::class, $handler->popProcessor());

            if (config('laravel_log_enhancer.log_memory_usage')) {
                $this->assertInstanceOf(MemoryUsageProcessor::class, $handler->popProcessor());
            }

            if (config('laravel_log_enhancer.log_request_details')) {
                $this->assertInstanceOf(WebProcessor::class, $handler->popProcessor());
            }
        }
    }

    /** @test */
    public function it_will_not_adds_request_details_to_logs()
    {
        config(['laravel_log_enhancer.log_memory_usage' => true]);
        config(['laravel_log_enhancer.log_request_details' => false]);
        $logger = $this->app[Logger::class];

        $handlers = $logger->getHandlers();
        foreach ($handlers as $handler) {
            $this->assertInstanceOf(RequestDataProcessor::class, $handler->popProcessor());
            $this->assertInstanceOf(MemoryUsageProcessor::class, $handler->popProcessor());

            $this->expectException(LogicException::class);
            $handler->popProcessor();
        }
    }

    /** @test */
    public function it_will_not_adds_memory_usage_details_to_logs()
    {
        config(['laravel_log_enhancer.log_memory_usage' => false]);
        config(['laravel_log_enhancer.log_request_details' => true]);
        $logger = $this->app[Logger::class];

        $handlers = $logger->getHandlers();
        foreach ($handlers as $handler) {
            $this->assertInstanceOf(RequestDataProcessor::class, $handler->popProcessor());
            $this->assertInstanceOf(WebProcessor::class, $handler->popProcessor());

            $this->expectException(LogicException::class);
            $handler->popProcessor();
        }
    }

    /** @test */
    public function it_skips_input_details_as_per_the_configuration()
    {
        $record = [];

        config(['laravel_log_enhancer.log_input_data' => false]);

        $requestDataProcessor = new RequestDataProcessor;
        $record = $requestDataProcessor($record);

        $this->assertArrayNotHasKey('headers', $record['extra']);
    }

    /** @test */
    public function it_adds_other_details_as_per_the_configuration()
    {
        $record = [];

        config(['laravel_log_enhancer.log_request_headers' => rand(0, 1)]);
        config(['laravel_log_enhancer.log_session_data' => rand(0, 1)]);

        $requestDataProcessor = new RequestDataProcessor;
        $record = $requestDataProcessor($record);

        if (config('laravel_log_enhancer.log_request_headers')) {
            $this->assertArrayHasKey('headers', $record['extra']);
        } else {
            $this->assertArrayNotHasKey('headers', $record['extra']);
        }

        if (config('laravel_log_enhancer.log_session_data')) {
            $this->assertArrayHasKey('session', $record['extra']);
        } else {
            $this->assertArrayNotHasKey('session', $record['extra']);
        }

        if (config('laravel_log_enhancer.log_git_data')) {
            $this->assertArrayHasKey('git', $record['extra']);
        } else {
            $this->assertArrayNotHasKey('git', $record['extra']);
        }

        if (config('laravel_log_enhancer.log_app_details')) {
            $this->assertArrayHasKey('Application Details', $record['extra']);
        } else {
            $this->assertArrayNotHasKey('Application Details', $record['extra']);
        }
    }
}
