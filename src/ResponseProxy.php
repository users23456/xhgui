<?php

namespace XHGui;

use LogicException;
use Slim\Http\Response;

class ResponseProxy
{
    /** @var Response */
    private $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function body($data): Response
    {
        $this->response->write($data);

        return $this->response;
    }

    public function __set($name, $value): void
    {
        $this->response = $this->response->withHeader($name, $value);
    }

    public function __get($name): void
    {
        throw new LogicException('Unsupported call to __get()');
    }

    public function __isset($name): void
    {
        throw new LogicException('Unsupported call to __isset()');
    }
}
