<?php

namespace Freshbitsweb\LaravelLogEnhancer\Test;

use Illuminate\Log\Logger;

class LogEnhancerTest extends TestCase
{
    /** @test */
    public function it_adds_request_details_to_logs()
    {
        $logger = $this->app[Logger::class];

        $logger->info('hey');

        $handlers = $logger->getHandlers();
        foreach ($handlers as $handler) {
            // Currently, Laravel does not provide access to underlying monolog instance
            // So, we cannot assert whether the processors are added by the package
        }
    }
}