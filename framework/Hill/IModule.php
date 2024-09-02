<?php

namespace Hill;

/**
 * Module interface
 */
interface IModule
{
  /**
   * Creates module config by options
   * 
   * @param array $options Module options
   * 
   * @return array
   */
  public static function create(array $options = []): array;
}
