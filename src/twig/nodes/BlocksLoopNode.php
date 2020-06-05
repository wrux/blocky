<?php

namespace wrux\blocky\twig\nodes;

use Twig\Compiler;
use Twig\Node\Node;

/**
 * Blocky plugin
 *
 * @author    Callum Bonnyman
 * @package   Blocky
 * @since     0.1.0
 *
 */
class BlocksLoopNode extends Node {
  // Public Methods
  // ===========================================================================

  /**
   * Instansiate the Node
   *
   * @param integer $lineno
   * @param string $tag
   */
  public function __construct(int $lineno, string $tag = NULL) {
    parent::__construct([], ['with_loop' => TRUE], $lineno, $tag);
  }

  /**
   * Compile the loop node context
   *
   * @param Compiler $compiler
   * @return void
   */
  public function compile(Compiler $compiler): void {
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
