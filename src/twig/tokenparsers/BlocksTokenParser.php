<?php

namespace wrux\blocky\twig\tokenparsers;

use Twig\Node\Node;
use Twig\TokenParser\AbstractTokenParser;
use Twig\Node\Expression\AssignNameExpression;
use Twig\Node\PrintNode;
use Twig\Token;
use wrux\blocky\twig\nodes\BlocksNode;

/**
 * Blocky plugin
 *
 * @author    Callum Bonnyman
 * @package   Blocky
 * @since     0.1.0
 *
 */
class BlocksTokenParser extends AbstractTokenParser {
  // Public Methods
  // ===========================================================================

  /**
   * @inheritDoc
   */
  public function parse(Token $token) {
    $lineno = $token->getLine();
    $stream = $this->parser->getStream();
    $stream->expect(Token::OPERATOR_TYPE, 'in');
    $blocks = $this->parser->getExpressionParser()->parseExpression();

    if ($stream->nextIf(Token::BLOCK_END_TYPE)) {
      $body = $this->parser->subparse([$this, 'decideBlocksEnd'], true);
      if ($token = $stream->nextIf(Token::NAME_TYPE)) {
        $value = $token->getValue();
      }
    } else {
      $body = new Node([
        new PrintNode($this->parser->getExpressionParser()->parseExpression(), $lineno),
      ]);
    }
    $stream->expect(Token::BLOCK_END_TYPE);

    return new BlocksNode($blocks, $body, $lineno, $this->getTag());
  }

  /**
   * @inheritDoc
   */
  public function getTag() {
    return 'blocks';
  }

  /**
   * Checks for the blocks closing tag
   *
   * @param Token $token
   * @return bool
   */
  public function decideBlocksEnd(Token $token): bool {
    return $token->test('endblocks');
  }
}
