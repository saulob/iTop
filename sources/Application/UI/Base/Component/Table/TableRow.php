<?php
/**
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\Table;

/**
 * Class TableRow
 *
 * @package Combodo\iTop\Application\UI\Base\Component\Table
 * @since 3.1.0
 */
class TableRow
{
	/** @var array array of data */
	private array $aData;

	/**
	 * Constructor.
	 *
	 */
	public function __construct(array $aData)
	{
		// Retrieve constructor parameters
		$this->aData = $aData;
	}

	/**
	 * Get row data.
	 *
	 * @return array
	 */
	public function GetData(): array
	{
		return $this->aData;
	}

}