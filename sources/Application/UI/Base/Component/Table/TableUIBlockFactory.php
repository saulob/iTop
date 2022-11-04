<?php
/**
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\DataTable;

use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Table\Table;


/**
 * Class TableUIBlockFactory
 *
 * @api
 * @since 3.1.0
 * @package Combodo\iTop\Application\UI\Base\Component\Table
 */
class TableUIBlockFactory extends AbstractUIBlockFactory
{
	/** @inheritDoc */
	public const TWIG_TAG_NAME = 'UITable';
	/** @inheritDoc */
	public const UI_BLOCK_CLASS_NAME = Table::class;

	/**
	 * Make a table for indirect links.
	 *
	 * @param string $sId
	 * @param array $aColumns
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Table\Table
	 */
	public static function Make(string $sId, array $aColumns): Table
	{
		$oTable = new Table($sId);
		$oTable->AddColumns($aColumns);

		return $oTable;
	}

}