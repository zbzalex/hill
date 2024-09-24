<?php

namespace Neon;

class Cookie
{
  public $key;
  public $value;
  public $expires;
  public $path;
  public $httpOnly;

  public function __construct($key, $value, $expires, $path = '/', $httpOnly = false)
  {
    $this->key = $key;
    $this->value = $value;
    $this->expires = $expires;
    $this->path = $path;
    $this->httpOnly = $httpOnly;
  }

  public function __toString()
  {
    $chain = [];
    $chain[] = sprintf('%s=%s', $this->key, $this->value);
    $chain[] = sprintf('expires=%s', gmdate("D, d M Y H:i:s T", $this->expires));
    $chain[] = sprintf('max-age=%d', $this->expires - time());
    $chain[] = sprintf('path=%s', $this->path);
    if ($this->httpOnly) {
      $chain[] = 'httpOnly';
    }

    return implode("; ", $chain);
  }
}
