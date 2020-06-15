<?php

declare(strict_types=1);

namespace wrux\blocky\exceptions;

use yii\base\Exception;

/**
 * Error parsing the block.
 *
 * @package Blocky
 * @since 0.0.1
 */
class InvalidBlockException extends Exception
{
  // Public Methods
  // ===========================================================================

  /**
   * @inheritdoc
   */
  public function getName(): string
  {
    return 'Invalid block';
  }
}
