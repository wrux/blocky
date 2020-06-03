<?php declare(strict_types=1);

namespace wrux\blocky;

/**
 * Block Interface defines a common interface to be used for all Block classes.
 */
interface BlockInterface {
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
