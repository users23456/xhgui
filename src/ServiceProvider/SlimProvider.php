<?php

namespace XHGui\ServiceProvider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Slim\App;
use Slim\Container as SlimContainer;
use Slim\Flash\Messages;
use Slim\Http\Uri;
use Slim\Views\Twig;
use XHGui\RequestProxy;
use XHGui\Twig\TwigExtension;

class SlimProvider implements ServiceProviderInterface
{
    /** @var App */
    private $app;

    public function __construct(App $app) {
        $this->app = $app;
    }

    /**
     * Create the Slim app
     */
    public function register(Container $container): void
    {
        $container['app'] = function ($c) {
            if ($c['config']['timezone']) {
                date_default_timezone_set($c['config']['timezone']);
            }

            return $this->app;
        };

        $container['flash'] = static function () {
            return new Messages();
        };

        $container['view.class'] = Twig::class;
        $container['view'] = static function (SlimContainer $container) {
            $view = new $container['view.class']($container['template_dir'], [
                'cache' => $container['cache_dir'],
            ]);

            $view->addExtension($container[TwigExtension::class]);

            // set global variables to templates
            $view['date_format'] = $container['date.format'];

            return $view;
        };

        $container[TwigExtension::class] = static function (SlimContainer $container) {
            $router = $container['router'];
            $uri = $container[Uri::class];
            $pathPrefix = $container['path.prefix'];

            return new TwigExtension($router, $uri, $pathPrefix);
        };

        $container[Uri::class] = static function (SlimContainer $container) {
            $env = $container->get('environment');

            return Uri::createFromEnvironment($env);
        };

        $container['request.proxy'] = static function (SlimContainer $container) {
            return new RequestProxy($container['request']);
        };
    }
}
