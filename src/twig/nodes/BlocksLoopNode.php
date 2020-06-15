<?php

declare(strict_types=1);

namespace wrux\blocky\twig\nodes;

use Twig\Compiler;
use Twig\Node\Node;

/**
 * Blocks node iterator.
 *
 * @package Blocky
 * @since 0.1.0
 *
 */
class BlocksLoopNode extends Node
{
  // Public Methods
  // ===========================================================================

  /**
   * Instansiate the Node.
   *
   * @param integer $lineno
   * @param string $tag
   */
  public function __construct(int $lineno, string $tag = null)
  {
    parent::__construct([], ['with_loop' => true], $lineno, $tag);
  }

  /**
   * Compile the loop node context.
   *
   * @param Compiler $compiler
   */
  public function compile(Compiler $compiler): void
  {
    $compiler
      ->write("++\$context['loop']['index0'];\n")
      ->write("++\$context['loop']['index'];\n")
      ->write("\$context['loop']['first'] = false;\n")
      ->write("if (isset(\$context['loop']['length'])) {\n")
      ->indent()
      ->write("--\$context['loop']['revindex0'];\n")
      ->write("--\$context['loop']['revindex'];\n")
      ->write("\$context['loop']['last'] = 0 === \$context['loop']['revindex0'];\n")
      ->outdent()
      ->write("}\n");
  }
}
