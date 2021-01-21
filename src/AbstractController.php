<?php

namespace XHGui;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\App;
use Slim\Flash\Messages;
use Slim\Router;
use Slim\Views\Twig;

abstract class AbstractController
{
    /**
     * @var App
     */
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    protected function render(string $template, array $data = []): void
    {
        /** @var ContainerInterface $container */
        $container = $this->app->getContainer();
        /** @var ResponseInterface $response */
        $response = $container->get('response');
        /** @var Twig $renderer */
        $renderer = $container->get('view');

        $renderer->render($response, $template, $data);
    }

    protected function urlFor(string $name, array $params = []): string
    {
        /** @var ContainerInterface $container */
        $container = $this->app->getContainer();
        /** @var Router $router */
        $router = $container->get('router');

        return $router->pathFor($name, $params);
    }

    protected function redirect($url, $status = 302): void
    {
        /** @var ContainerInterface $container */
        $container = $this->app->getContainer();
        /** @var ResponseInterface $response */
        $response = $container->get('response');

        $response->withStatus($status)->withHeader('Location', $url);
    }

    protected function flash(string $key, $value): void
    {
        /** @var ContainerInterface $container */
        $container = $this->app->getContainer();
        /** @var Messages $flash */
        $flash = $container->get('flash');

        $flash->addMessage($key, $value);
    }

    protected function config(string $key)
    {
        return $this->app->getContainer()->get($key);
    }
}
