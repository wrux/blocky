<?php

declare(strict_types=1);

namespace wrux\blocky;

use ArrayIterator;
use IteratorAggregate;

use Craft;
use craft\base\Element;
use wrux\blocky\exceptions\BlockTransformerNotFoundException;
use wrux\blocky\exceptions\InvalidBlockException;

/**
 * Block parser.
 *
 * Handles the logic to parse in each of the Matrix blocks and returns a
 * mapped iterable object.
 *
 * @package Blocky
 * @since 0.0.1
 */
class BlockParser implements IteratorAggregate
{
  /**
   * Block config loaded from `app/blocks.php`.
   *
   * @var array
   */
  private array $config;

  /**
   * Instansiated blocks.
   *
   * @var array
   */
  private array $blocks = [];

  // Public Methods
  // ===========================================================================

  /**
   * Instansiate the class and get the block config values.
   */
  public function __construct()
  {
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
   *   <section class="block {{ 'block--' ~ type }}">
   *     {% include template ignore missing with context only %}
   *   </section>
   * {% endfor %}
   * ```
   *
   * @return ArrayIterator
   */
  public function getIterator(): ArrayIterator
  {
    return new ArrayIterator($this->blocks);
  }

  /**
   * Adds a block to the parser.
   *
   * @param \craft\base\Element $block Matrix block.
   *
   * @throws \wrux\blocky\exceptions\BlockTransformerNotFoundException
   *   If no corresponding block parser class is found.
   */
  public function addBlock(Element $block): void
  {
    $block_class = $this->getBlockClass($block->type->handle);
    if (!$block_class || !class_exists($block_class)) {
      throw new BlockTransformerNotFoundException(
          sprintf('The block %s could not be found', $block_class)
      );
    }
    $reflect = new \ReflectionClass($block_class);
    if (!$reflect->implementsInterface(BlockInterface::class)) {
      throw new InvalidBlockException(
          sprintf(
              'The block class %s does not implement BlockInterface',
              $block_class
          )
      );
    }
    $this->blocks[] = new $block_class($block);
  }

  /**
   * Returns weather the block parser contains any blocks.
   *
   * @return bool
   */
  public function hasBlocks(): bool
  {
    return count($this->blocks) > 0;
  }

  // Private Methods
  // =========================================================================

  /**
   * Returns the block class reference from the configuration.
   *
   * @param string $handle The Matrix block handle.
   *
   * @throws \wrux\blocky\exceptions\BlockTransformerNotFoundException
   *   If no corresponding block parser is found in the config
   * @return string
   *   Block class namespace.
   */
  private function getBlockClass(string $handle): string
  {
    if (empty($this->config[$handle])) {
      throw new BlockTransformerNotFoundException(
          sprintf(
              'The block handle %s could not be found in the config',
              $handle
          )
      );
    }
    return $this->config[$handle];
  }
}
