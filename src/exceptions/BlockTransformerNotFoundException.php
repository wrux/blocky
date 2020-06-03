<?php declare(strict_types=1);

namespace wrux\blocky\exceptions;

use Craft;
use yii\base\Exception;

class BlockTransformerNotFoundException extends Exception {
  // Public Methods
  // ===========================================================================

	/**
	 * @inheritdoc
	 */
	public function getName() {
    return Craft::t('blocky', 'Block transformer not found.');
	}
}
