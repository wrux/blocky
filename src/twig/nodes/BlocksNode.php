<?php

namespace wrux\blocky\twig\nodes;

use Twig\Compiler;
use Twig\Node\Expression\AbstractExpression;
use Twig\Node\Node;

/**
 * Blocky plugin
 *
 * @author    Callum Bonnyman
 * @package   Blocky
 * @since     0.1.0
 *
 */
class BlocksNode extends Node {
  // Private Properties
  // ===========================================================================

  /**
   * Loop iteration node
   *
   * @var BlocksLoopNode
   */
  private BlocksLoopNode $loop;

  // Public Methods
  // ===========================================================================

  /**
   * Instansiate the Node
   *
   * @param AbstractExpression $blocks
   * @param Node $body
   * @param integer $lineno
   * @param string $tag
   */
  public function __construct(AbstractExpression $blocks, Node $body, int $lineno, string $tag = null) {
    $this->loop = new BlocksLoopNode($lineno, $tag);
    $body = new Node([$body, $this->loop]);
    $nodes = [
      'body' => $body,
      'blocks' => $blocks,
    ];
    parent::__construct($nodes, ['with_loop' => true], $lineno, $tag);
  }

  /**
   * Compile the node
   *
   * @param Compiler $compiler
   * @return void
   */
  public function compile(Compiler $compiler): void {
    $compiler
      ->addDebugInfo($this)
      ->write("\$context['_parent'] = \$context;\n")
      ->write("\$context['_blocks'] = twig_ensure_traversable(")
      ->subcompile($this->getNode('blocks'))
      ->raw(");\n");

    $compiler
      ->write("\$context['parsed_blocks'] = \wrux\blocky\Blocky::\$plugin->parseBlocks(\$context['_blocks']); ");

    $compiler
      ->write("\$context['loop'] = [\n")
      ->write("  'parent' => \$context['_parent'],\n")
      ->write("  'index0' => 0,\n")
      ->write("  'index'  => 1,\n")
      ->write("  'first'  => true,\n")
      ->write("];\n");

    $compiler
      ->write("if (is_array(\$context['_blocks']) || (is_object(\$context['_blocks']) && \$context['_blocks'] instanceof \Countable)) {\n")
      ->indent()
      ->write("\$length = count(\$context['_blocks']);\n")
      ->write("\$context['loop']['revindex0'] = \$length - 1;\n")
      ->write("\$context['loop']['revindex'] = \$length;\n")
      ->write("\$context['loop']['length'] = \$length;\n")
      ->write("\$context['loop']['last'] = 1 === \$length;\n")
      ->outdent()
      ->write("}\n");

    $compiler
      ->write("foreach (\$context['parsed_blocks'] as \$block) {\n")
      ->indent()
      ->write("\$context['block'] = \$block; \n")
      ->subcompile($this->getNode('body'), false)
      ->outdent()
      ->write("}\n\n");
  }
}
