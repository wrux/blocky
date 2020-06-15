<?php

declare(strict_types=1);

namespace wrux\blocky;

use craft\elements\MatrixBlock;

/**
 * Abstract block class.
 *
 * The BlockParser expects a class to implement BlockInterface.
 *
 * Any block class that extends this just needs to define the $blockTemplate
 * property and getContext() method.
 *
 * @package Blocky
 * @since 0.0.1
 */
abstract class Block implements BlockInterface
{
  /**
   * Block data.
   *
   * @var \craft\elements\MatrixBlock $block
   */
  protected MatrixBlock $block;

  /**
   * Block template.
   *
   * @var string $blockTtemplate
   */
  protected string $blockTemplate;

  /**
   * Instansiate the block.
   *
   * @param \craft\elements\MatrixBlock $block
   */
  public function __construct(MatrixBlock $block)
  {
    $this->block = $block;
  }

  // Public Methods
  // ===========================================================================

  /**
   * Returns the Block type handle.
   *
   * @return string
   *   Name of the block as defined in Craft.
   */
  public function getType(): string
  {
    return $this->block->type->handle;
  }

  /**
   * Returns the Block template.
   *
   * @return string
   *   Name of the block as defined in Craft.
   */
  public function getTemplate(): string
  {
    return $this->blockTemplate;
  }
}
