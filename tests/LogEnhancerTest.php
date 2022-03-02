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
            $this->assertInstanceOf(MemoryUsageProcessor::class, $handler->popProcessor());
            $this->assertInstanceOf(WebProcessor::class, $handler->popProcessor());
        }
    }

    /** @test */
    public function it_will_not_add_any_processor_to_the_logs()
    {
        config(['laravel_log_enhancer.log_memory_usage' => false]);
        config(['laravel_log_enhancer.log_request_details' => false]);
        $logger = $this->app[Logger::class];

        $handlers = $logger->getHandlers();
        foreach ($handlers as $handler) {
            $this->assertInstanceOf(RequestDataProcessor::class, $handler->popProcessor());

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

        config(['laravel_log_enhancer.log_request_headers' => true]);
        config(['laravel_log_enhancer.log_session_data' => true]);
        config(['laravel_log_enhancer.log_git_data' => true]);
        config(['laravel_log_enhancer.log_app_details' => true]);

        $requestDataProcessor = new RequestDataProcessor;
        $record = $requestDataProcessor($record);

        $this->assertArrayHasKey('headers', $record['extra']);

        $this->assertArrayHasKey('session', $record['extra']);

        $this->assertArrayHasKey('git', $record['extra']);

        $this->assertArrayHasKey('Application Details', $record['extra']);
    }

    /** @test */
    public function it_will_not_add_other_details_as_per_the_configuration()
    {
        $record = [];

        config(['laravel_log_enhancer.log_request_headers' => false]);
        config(['laravel_log_enhancer.log_session_data' => false]);
        config(['laravel_log_enhancer.log_git_data' => false]);
        config(['laravel_log_enhancer.log_app_details' => false]);

        $requestDataProcessor = new RequestDataProcessor;
        $record = $requestDataProcessor($record);

        $this->assertArrayNotHasKey('headers', $record['extra']);

        $this->assertArrayNotHasKey('session', $record['extra']);

        $this->assertArrayNotHasKey('git', $record['extra']);

        $this->assertArrayNotHasKey('Application Details', $record['extra']);
    }
}
