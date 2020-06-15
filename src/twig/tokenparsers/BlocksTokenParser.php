<?php

declare(strict_types=1);

namespace wrux\blocky\twig\tokenparsers;

use Twig\Node\Node;
use Twig\TokenParser\AbstractTokenParser;
use Twig\Node\PrintNode;
use Twig\Token;
use wrux\blocky\twig\nodes\BlocksNode;

/**
 * Implement the `{% blocks %}` Twig tag.
 *
 * @package Blocky
 * @since 0.1.0
 */
class BlocksTokenParser extends AbstractTokenParser
{
  // Public Methods
  // ===========================================================================

  /**
   * @inheritDoc
   *
   * @return wrux\blocky\twig\nodes\BlocksNode
   */
  public function parse(Token $token): BlocksNode
  {
    $lineno = $token->getLine();
    $stream = $this->parser->getStream();
    $stream->expect(Token::OPERATOR_TYPE, 'in');
    $blocks = $this->parser->getExpressionParser()->parseExpression();

    $skip_empty = false;
    if ($stream->nextIf(Token::NAME_TYPE, 'skip')) {
      $stream->expect(Token::NAME_TYPE, 'empty');
      $skip_empty = true;
    }

    if ($stream->nextIf(Token::BLOCK_END_TYPE)) {
      $body = $this->parser->subparse([$this, 'decideBlocksEnd'], true);
      if ($token = $stream->nextIf(Token::NAME_TYPE)) {
        $value = $token->getValue();
      }
    } else {
      $body = new Node(
          [
            new PrintNode(
                $this->parser->getExpressionParser()->parseExpression(),
                $lineno
            ),
          ]
      );
    }
    $stream->expect(Token::BLOCK_END_TYPE);

    return new BlocksNode($blocks, $body, $skip_empty, $lineno, $this->getTag());
  }

  /**
   * @inheritDoc
   */
  public function getTag(): string
  {
    return 'blocks';
  }

  /**
   * Checks for the blocks closing tag.
   *
   * @param \Twig\Token $token
   *  Twig token.
   *
   * @return bool
   *   True if `{% endblocks %}` is found.
   */
  public function decideBlocksEnd(Token $token): bool
  {
    return $token->test('endblocks');
  }
}
