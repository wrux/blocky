<?php

declare(strict_types=1);

namespace wrux\blocky;

/**
 * Block interface.
 *
 * Block Interface defines a common interface to be used for all Block classes.
 *
 * @package Blocky
 * @since 0.0.1
 */
interface BlockInterface
{
  /**
   * Return the block template.
   *
   * @return string
   */
  public function getTemplate(): string;

  /**
   * Return the block type handle.
   *
   * @return string
   */
  public function getType(): string;

  /**
   * Return the block context.
   *
   * @return array
   */
  public function getContext(): array;
}
