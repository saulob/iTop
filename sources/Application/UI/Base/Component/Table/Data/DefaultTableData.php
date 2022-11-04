<?php
/**
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\Table\Data;

/**
 * Class DefaultTableData
 *
 * @package Combodo\iTop\Application\UI\Base\Component\Table\Data
 * @since 3.1.0
 */
class DefaultTableData extends AbstractTableData
{
	public const DATA_TYPE = 'default';

	/** @var Object $oValue */
	private object $oValue;

	/** @inheritdoc * */
	public static function GetDataType(): string
	{
		return self::DATA_TYPE;
	}

	/**
	 * Constructor.
	 *
	 * @param Object $oValue
	 */
	public function __construct(object $oValue)
	{
		$this->oValue = $oValue;
	}

	/** @inheritDoc */
	public function GetValue()
	{
		$this->oValue;
	}
}