<?php

namespace Hill;

//
// Запрос.
//
class Request
{
    /**
     * @var string string
     */
    public $method = RequestMethod::GET;

    /**
     * @var string $uri
     */
    public $uri;

    /**
     * @var array
     */
    public $headers = [];

    /**
     * @var array
     */
    public $attributes = [];
    
    public $query;
    public $data;
    public $cookies;
    public $files;
    public $secure;

    public $type;
    public $body;

    public $length;

    /**
     * 
     */
    public function __construct(
        $method,
        $uri,
        array $headers = [],
        array $query = [],
        array $data = [],
        array $cookies = [],
        array $files = [],
        array $attributes = []
    ) {
        $this->method = $method;
        $this->uri = $uri;
        $this->headers = $headers;
        $this->attributes = $attributes;

        $this->query = $query;
        $this->data  = $data;
        $this->cookies = $cookies;
        $this->files = $files;
        $this->type = isset($this->headers['content-type'])
            ? $this->headers['content-type']
            : 'text/html';
        $this->secure = self::getScheme() == 'https';
        $this->body = $this->getBody();
        $this->length = isset($this->headers['content-length'])
            ? $this->headers['content-length']
            : -1;
    }

    /**
     * @return Request
     */
    public static function createFromGlobals()
    {
        return new Request(
            $_SERVER['REQUEST_METHOD'],
            rtrim(str_replace('@', '%40', $_SERVER['REQUEST_URI']), "/") . "/",
            self::resolveGlobalHeaders(),
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES
        );
    }

    private static function resolveGlobalHeaders()
    {
        $headers = [];

        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) != 'HTTP_')
                continue;
            $header = substr($key, 5);
            $header = str_replace("_", "-", $header);
            $header = strtolower($header);

            $headers[$header] = $value;
        }

        return $headers;
    }

    private function getBody()
    {
        $body = null;
        if (
            'POST'   === $this->method
            || 'PUT'    === $this->method
            || 'DELETE' === $this->method
            || 'PATCH'  === $this->method
        ) {
            $body = file_get_contents('php://input');
        }

        return $body;
    }

    private static function getScheme()
    {
        if (isset($_SERVER['HTTPS']) && 'on' === strtolower($_SERVER['HTTPS'])) {
            return 'https';
        }

        return 'http';
    }
}
