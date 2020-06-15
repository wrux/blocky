<?php

declare(strict_types=1);

namespace wrux\blocky\twig\nodes;

use Twig\Compiler;
use Twig\Node\Expression\AbstractExpression;
use Twig\Node\Node;

/**
 * Compiles the `{% blocks %}` Twig tag.
 *
 * @package Blocky
 * @since 0.1.0
 */
class BlocksNode extends Node
{
  // Private Properties
  // ===========================================================================

  /**
   * Loop iteration node.
   *
   * @var \wrux\blocky\twig\nodes\BlocksLoopNode
   */
  private BlocksLoopNode $loop;

  // Public Methods
  // ===========================================================================

  /**
   * Instansiate the Node
   *
   * @param \Twig\Node\Expression\AbstractExpression $blocks
   * @param \Twig\Node\Node $body
   * @param bool $skip_empty
   * @param integer $lineno
   * @param string $tag
   */
  public function __construct(AbstractExpression $blocks, Node $body, bool $skip_empty, int $lineno, string $tag = null)
  {
    $this->loop = new BlocksLoopNode($lineno, $tag);
    $body = new Node([$body, $this->loop]);
    $nodes = [
      'body' => $body,
      'blocks' => $blocks,
    ];
    parent::__construct($nodes, ['skip_empty' => $skip_empty], $lineno, $tag);
  }

  /**
   * Compile the node.
   *
   * @param \Twig\Compiler $compiler
   *   Twig compiler object.
   */
  public function compile(Compiler $compiler): void
  {
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
      ->write("if (is_array(\$context['_blocks']) || ")
      ->write("(is_object(\$context['_blocks']) && \$context['_blocks'] ")
      ->write("instanceof \Countable)) {\n")
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
      ->write("\$block_context = \$block->getContext();\n")
      ->indent();

    // Skip blocks that return an empty context.
    if ($this->getAttribute('skip_empty')) {
      $compiler
        ->write("if (empty(\$block_context)) {\n")
          ->indent()
          ->write("continue;\n")
          ->outdent()
        ->write("}\n");
    }

    $compiler
      ->write("\$context['block'] = \$block;\n")
      ->write("\$context['type'] = \$block->getType();\n")
      ->write("\$context['template'] = \$block->getTemplate();\n")
      ->write("\$context['context'] = \$block_context;\n")
      ->subcompile($this->getNode('body'), false)
      ->outdent()
      ->write("}\n\n");
  }
}
