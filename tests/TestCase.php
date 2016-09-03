<?php

use JeroenNoten\LaravelPoll\ServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    public function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);
    }

    public function setUp()
    {
        parent::setUp();

        $this->artisan('migrate');

        $this->withFactories(__DIR__ . '/../database/factories');
    }
}
