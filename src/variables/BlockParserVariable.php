<?php

declare(strict_types=1);

namespace wrux\blocky\variables;

use wrux\blocky\Blocky;

/**
 * Block Parser Variable.
 *
 * This class is available at `{{ craft.blocky }}` in the template.
 *
 * @package Blocky
 * @since 0.1.0
 */
class BlockParserVariable
{
  // Public Methods
  // =========================================================================

  /**
   * Parse the Matrix blocks array.
   *
   * @param array|MatrixBlockQuery $blocks
   *
   * @return IteratorAggregate|array
   *   Iterable block object.
   */
  public function parseBlocks($blocks)
  {
    return Blocky::$plugin->parseBlocks($blocks);
  }
}
