<?php

namespace wrux\blocky;

use Craft;
use craft\base\BlockElementInterface;
use craft\base\Plugin;
use craft\elements\db\MatrixBlockQuery;
use craft\web\twig\variables\CraftVariable;
use wrux\blocky\twig\BlocksTwigExtension;
use yii\base\Event;
use wrux\blocky\BlockParser;
use wrux\blocky\exceptions\BlockTransformerNotFoundException;
use wrux\blocky\variables\BlockParserVariable;

/**
 * Blocky plugin
 *
 * @author    Callum Bonnyman
 * @package   Blocky
 * @since     0.0.1
 *
 */
class Blocky extends Plugin {
  // Static Properties
  // ===========================================================================

  /**
   * Plugin singelton instance
   *
   * @var Blocky
   */
  public static Blocky $plugin;

  // Public Methods
  // ===========================================================================

  /**
   * Setup the plugin
   */
  public function init() {
    parent::init();
    self::$plugin = $this;

    if (!Craft::$app->request->getIsSiteRequest()) {
      return;
    }

    // Register the blocky variable.
    Event::on(
      CraftVariable::class,
      CraftVariable::EVENT_INIT,
      function (Event $event) {
        /** @var CraftVariable $variable */
        $variable = $event->sender;
        $variable->set('blocky', BlockParserVariable::class);
      }
    );

    // Add the blocks twig tag.
    Craft::$app->view->registerTwigExtension(new BlocksTwigExtension());
  }

  /**
   * Parse the Matrix blocks array
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
