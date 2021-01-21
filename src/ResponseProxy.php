<?php

namespace XHGui;

use ArrayAccess;
use LogicException;
use Slim\Http\Response;

class ResponseProxy implements ArrayAccess
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

    public function setStatus($code): Response
    {
        $this->response = $this->response->withStatus($code);

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

    public function offsetSet($name, $value): void
    {
        $this->response = $this->response->withHeader($name, $value);
    }

    public function offsetExists($offset): void
    {
        throw new LogicException('Unsupported call to offsetExists()');
    }

    public function offsetGet($offset)
    {
        throw new LogicException('Unsupported call to offsetGet()');
    }

    public function offsetUnset($offset): void
    {
        throw new LogicException('Unsupported call to offsetUnset()');
    }
}
