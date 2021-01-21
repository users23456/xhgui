<?php

namespace XHGui\ServiceProvider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ConfigProvider implements ServiceProviderInterface
{
    public function register(Container $app): void
    {
        $app['config'] = static function ($app) {
            $config = $app;
            $config['template_dir'] = $app['app.dir'] . '/templates';
            $config['cache_dir'] = $app['app.dir'] . '/cache';

            return $config;
        };
    }
}
