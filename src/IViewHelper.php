<?php

namespace Neon;

/**
 * View helper interface
 */
interface IViewHelper
{
  /**
   * Returns the helper name
   * 
   * @return string
   */
  public function getName(): string;
}
