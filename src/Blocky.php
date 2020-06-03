<?php

namespace wrux\blocky;

use Craft;
use craft\base\Plugin;
use craft\web\twig\variables\CraftVariable;
use yii\base\Event;
use wrux\blocky\variables\BlockParserVariable;

/**
 * Blocky plugin
 *
 * @author    Callum Bonnyman
 * @package   BlockParser
 * @since     0.0.1
 *
 */
class Blocky extends Plugin {
    // Static Properties
    // =========================================================================

    /**
     * Plugin singelton instance
     *
     * @var BlockParser
     */
    public static $plugin;

    // Public Methods
    // =========================================================================

    /**
     * Set our $plugin static property to this class so that it can be accessed via
     * BlockParser::$plugin
     *
     * Called after the plugin class is instantiated; do any one-time initialization
     * here such as hooks and events.
     *
     * If you have a '/vendor/autoload.php' file, it will be loaded for you automatically;
     * you do not need to load it in your init() method.
     *
     */
    public function init() {
      parent::init();
      self::$plugin = $this;

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
    }
}
