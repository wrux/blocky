<?php

namespace wrux\blocky;

use Craft;
use craft\base\Plugin;
use craft\web\twig\variables\CraftVariable;
use wrux\blocky\twig\BlocksTwigExtension;
use yii\base\Event;
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
   * @var BlockParser
   */
  public static $plugin;

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
}
