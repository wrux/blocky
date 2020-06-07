<?php declare(strict_types=1);

namespace wrux\blocky;

use ArrayIterator;
use IteratorAggregate;

use Craft;
use craft\base\ElementInterface;
use wrux\blocky\exceptions\BlockTransformerNotFoundException;
use wrux\blocky\exceptions\InvalidBlockException;

/**
 * Blocky block parser
 *
 * The block parser maps out each of the Matrix blocks.
 *
 * @author    Callum Bonnyman
 * @package   Blocky
 * @since     0.0.1
 *
 */
class BlockParser implements IteratorAggregate {
  /**
   * @var array Block config loaded from app/blocks.php.
   */
  private array $config;

  /**
   * @var array Instansiated blocks.
   */
  private array $blocks = [];

  // Public Methods
  // ===========================================================================

  /**
   * Instansiate the class and get the block config values.
   */
  public function __construct() {
    $this->config = Craft::$app->config->getConfigFromFile('blocks');
  }

  /**
   * Returns the instansiated blocks.
   *
   * When looping over the result of `craft.blocks.parseBlocks` this method will
   * get called and return the blocks.
   *
   * In the twig template you can use the following example:
   * ```
   * {% for block in blocks %}
   *   <section class="block {{ 'block--' ~ block.type }}">
   *     {% include block.template ignore missing with block.context only %}
   *   </section>
   * {% endfor %}
   * ```
   *
   * @return ArrayIterator
   */
  public function getIterator(): ArrayIterator {
    return new ArrayIterator($this->blocks);
  }

  /**
   * Adds a block to the parser.
   *
   * @param ElementInterface $block
   * @throws BlockTransformerNotFoundException if no corresponding block parser class is found.
   * @return void
   */
  public function addBlock(ElementInterface $block): void {
    $block_class = $this->getBlockClass($block->type->handle);
    if (!$block_class || !class_exists($block_class)) {
      throw new BlockTransformerNotFoundException(
        sprintf('The block %s could not be found', $block_class)
      );
    }
    $reflect = new \ReflectionClass($block_class);
    if (!$reflect->implementsInterface(BlockInterface::class)) {
      throw new InvalidBlockException(
        sprintf('The block class %s does not implement BlockInterface', $block_class)
      );
    }
    $this->blocks[] = new $block_class($block);
  }

  /**
   * Returns weather the block parser contains any blocks.
   *
   * @return bool
   */
  public function hasBlocks(): bool {
    return count($this->blocks) > 0;
  }

  // Private Methods
  // ===========================================================================

  /**
   * Returns the block class reference from the configuration.
   *
   * @param string $handle
   * @throws BlockTransformerNotFoundException if no corresponding block parser is found in the config
   * @return string
   */
  private function getBlockClass(string $handle): string {
    if (empty($this->config[$handle])) {
      throw new BlockTransformerNotFoundException(
        sprintf('The block handle %s could not be found in the config', $handle)
      );
    }
    return $this->config[$handle];
  }
}
