<?php

namespace wrux\blocky\twig;

use Twig\Extension\AbstractExtension;
use wrux\blocky\twig\tokenparsers\BlocksTokenParser;

/**
 * Blocky plugin
 *
 * @author    Callum Bonnyman
 * @package   Blocky
 * @since     0.1.0
 *
 */
class BlocksTwigExtension extends AbstractExtension {
  /**
   * Returns the Blocky token parsers.
   *
   * @implements the twig tag {% blocks in ... %}
   * @return void
   */
  public function getTokenParsers() {
    return [new BlocksTokenParser()];
  }
}
