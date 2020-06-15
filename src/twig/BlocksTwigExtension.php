<?php

declare(strict_types=1);

namespace wrux\blocky\twig;

use Twig\Extension\AbstractExtension;
use wrux\blocky\twig\tokenparsers\BlocksTokenParser;

/**
 * Blocky custom Twig functionality.
 *
 * @package Blocky
 * @since 0.1.0
 */
class BlocksTwigExtension extends AbstractExtension
{
  // Public Methods
  // =========================================================================

  /**
   * Returns the Blocky token parsers.
   *
   * Implements the twig tag {% blocks in ... %}
   *
   * @return array
   *   Custom Twig tag token parsers.
   */
  public function getTokenParsers(): array
  {
    return [new BlocksTokenParser()];
  }
}
