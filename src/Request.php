<?php

namespace Neon;

/**
 * Request class
 */
class Request
{
  /**
   * @var string $method Request method
   */
  public $method = RequestMethod::GET;

  /**
   * @var string $uri Request uri
   */
  public $uri;

  /**
   * @var array $headers Request headers array
   */
  public $headers = [];

  /**
   * @var array $attributes Attributes array
   */
  public $attributes = [];

  /**
   * @var array $query _GET array
   */
  public $query;

  /**
   * @var array $data  _POST array
   */
  public $data;

  /**
   * @var array $cookies Cookies array
   */
  public $cookies;

  /**
   * @var array $files Files array
   */
  public $files;

  /**
   * @var bool $secure Secure flag
   */
  public $secure;

  /**
   * @var string $type Request content type
   */
  public $type;

  /**
   * @var string $body Request content body
   */
  public $body;

  /**
   * @var int $length Request content length
   */
  public $length;

  /**
   * Constructor
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
    return self::create(
      rtrim(str_replace('@', '%40', $_SERVER['REQUEST_URI']), "/") . "/",
      $_SERVER['REQUEST_METHOD'],
      self::resolveGlobalHeaders(),
      $_GET,
      $_POST,
      $_COOKIE,
      $_FILES
    );
  }
  
  public static function create(
    $path,
    $method = RequestMethod::GET,
    array $headers = [],
    array $query = [],
    array $request = [],
    array $cookies = [],
    array $files = []
  )
  {
    return new Request(
      $method,
      $path,
      $headers,
      $query,
      $request,
      $cookies,
      $files
    );
  }

  /**
   * @return array
   */
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

  /**
   * @return string
   */
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

  /**
   * @return string
   */
  private static function getScheme()
  {
    if (isset($_SERVER['HTTPS']) && 'on' === strtolower($_SERVER['HTTPS'])) {
      return 'https';
    }

    return 'http';
  }

  /**
   * @param int $component
   * 
   * @return array|string
   */
  public function getUrl($component = -1)
  {
    return parse_url($this->uri, $component);
  }
}
