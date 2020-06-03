<?php declare(strict_types=1);

namespace wrux\blocky\variables;

use Craft;
use craft\base\BlockElementInterface;
use craft\elements\db\MatrixBlockQuery;
use wrux\blocky\BlockParser;
use wrux\blocky\exceptions\BlockTransformerNotFoundException;

/**
 * Block Parser Variable
 *
 * Craft allows plugins to provide their own template variables, accessible from
 * the {{ craft }} global variable (e.g. {{ craft.blocks }}).
 *
 * https://craftcms.com/docs/plugins/variables
 *
 * @author    Callum Bonnyman
 * @package   BlockParser
 * @since     0.0.1
 */
class BlockParserVariable {
    // Public Methods
    // =========================================================================

    /**
     * Parse the Matrix blocks array
     *
     *
     *
     * @param array|MatrixBlockQuery $blocks
     * @return void
     */
    public function parseBlocks($blocks) {
      // If the matrix block was not egar loaded then execute the query.
      if ($blocks instanceof MatrixBlockQuery) {
        $blocks = $blocks->all();
      }
      if (!$blocks || count($blocks) === 0) {
        return [];
      }
      $block_parser = new BlockParser;
      foreach ($blocks as $block) {
        if (!$block instanceof BlockElementInterface) {
          continue;
        }
        try {
          $block_parser->addBlock($block);
        }
        catch  (BlockTransformerNotFoundException $e) {
          // The array contains data for a block with no corresponding parser class.
          Craft::warning(
            Craft::t(
              'blocky',
              'Block transformer not found: {erorr}',
              ['error' => $e->getMessage()]
            ),
            __METHOD__
          );
          continue;
        }
        catch (\Exception $e) {
          Craft::error(
            Craft::t(
              'blocky',
              'There was an error parsing the blocks: {erorr}',
              ['error' => $e->getMessage()]
            ),
            __METHOD__
          );
          return [];
        }
      }
      return $block_parser;
    }

}
