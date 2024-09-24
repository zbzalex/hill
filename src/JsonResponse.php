<?php

namespace Neon;

/**
 * Json response class
 */
class JsonResponse extends Response
{
  /**
   * @param array $data
   */
  public function __construct(array $data, array $headers = [])
  {
    parent::__construct(json_encode($data));

    $headers['content-type'] = 'application/json';
    
    $this->addHeaders($headers);
  }
}
