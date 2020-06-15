<?php

declare(strict_types=1);

namespace wrux\blocky\exceptions;

use yii\base\Exception;

/**
 * Block transformer not found.
 *
 * @package Blocky
 * @since 0.0.1
 */
class BlockTransformerNotFoundException extends Exception
{
  // Public Methods
  // ===========================================================================

  /**
   * @inheritdoc
   */
  public function getName(): string
  {
    return 'Block transformer not found';
  }
}
