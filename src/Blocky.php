<?php

namespace wrux\blocky;

use Craft;
use craft\base\Plugin;
use craft\elements\db\MatrixBlockQuery;
use craft\elements\MatrixBlock;
use craft\web\twig\variables\CraftVariable;
use wrux\blocky\twig\BlocksTwigExtension;
use yii\base\Event;
use wrux\blocky\BlockParser;
use wrux\blocky\exceptions\BlockTransformerNotFoundException;
use wrux\blocky\variables\BlockParserVariable;

/**
 * Blocky plugin
 *
 * @package Blocky
 * @since 0.0.1
 *
 */
class Blocky extends Plugin
{
  // Static Properties
  // ===========================================================================

  /**
   * Plugin singelton instance.
   *
   * @var static \wrux\blocky\Blocky $plugin
   */
  public static Blocky $plugin;

  // Public Methods
  // ===========================================================================

  /**
   * Setup the plugin
   */
  public function init()
  {
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

    // Add the `blocks` Twig tag.
    Craft::$app->view->registerTwigExtension(new BlocksTwigExtension());
  }

  /**
   * Parse the Matrix blocks array.
   *
   * @param array|MatrixBlockQuery $blocks
   *
   * @return mixed
   *   Iterable block object.
   */
  public function parseBlocks($blocks)
  {
    // If the matrix block was not eagar loaded then execute the query.
    if ($blocks instanceof MatrixBlockQuery) {
      $blocks = $blocks->all();
    }
    if (!$blocks || count($blocks) === 0) {
      return [];
    }
    $block_parser = new BlockParser;
    foreach ($blocks as $block) {
      if (!$block instanceof MatrixBlock) {
        continue;
      }
      try {
        $block_parser->addBlock($block);
      } catch (BlockTransformerNotFoundException $e) {
        // Block found, but with no corresponding parser class.
        Craft::warning(
            sprintf('Block not found: %s', $e->getMessage()),
            __METHOD__
        );
        continue;
      } catch (\Exception $e) {
        Craft::error(
            sprintf('Block parser error: %s', $e->getMessage()),
            __METHOD__
        );
        return [];
      }
    }
    return $block_parser;
  }
}
