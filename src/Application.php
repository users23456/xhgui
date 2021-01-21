<?php

namespace XHGui;

use Slim\App;
use Slim\Container;
use XHGui\Saver\SaverInterface;

class Application
{
    /** @var App */
    private $app;
    /** @var bool */
    private $booted = false;
    /** @var Container */
    private $container;

    public function run(): void
    {
        $this->boot()->getSlim()->run();
    }

    public function boot(): self
    {
        if (!$this->booted) {
            $container = $this->getContainer();
            $app = $this->getSlim();
            $container->register(new ServiceProvider\RouteProvider($app));
            $container->register(new ServiceProvider\SlimProvider($app));

            $this->booted = true;
        }

        return $this;
    }

    public function getSlim(): App
    {
        if (!$this->app) {
            $this->app = new App($this->getContainer());
        }

        return $this->app;
    }

    public function getSaver(): SaverInterface
    {
        return $this->getContainer()['saver'];
    }

    public function getContainer(): Container
    {
        if (!$this->container) {
            $container = new Container(Config::boot());
            $this->container = $this->register($container);
        }

        return $this->container;
    }

    private function register(Container $container): Container
    {
        $container->register(new ServiceProvider\ServiceProvider());
        $container->register(new ServiceProvider\PdoStorageProvider());
        $container->register(new ServiceProvider\MongoStorageProvider());
        $container->register(new ServiceProvider\ConfigProvider());

        return $container;
    }
}
