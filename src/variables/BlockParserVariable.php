<?php declare(strict_types=1);

namespace wrux\blocky\variables;

use wrux\blocky\Blocky;

/**
 * Block Parser Variable
 *
 * Craft allows plugins to provide their own template variables, accessible from
 * the {{ craft }} global variable (e.g. {{ craft.blocks }}).
 *
 * https://craftcms.com/docs/plugins/variables
 *
 * @author    Callum Bonnyman
 * @package   Blocky
 * @since     0.0.1
 */
class BlockParserVariable {
  // Public Methods
  // =========================================================================

  public function parseBlocks($blocks) {
    return Blocky::$plugin->parseBlocks($blocks);
  }
}
