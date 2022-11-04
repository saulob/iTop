<?php
/**
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\Table\Data;

/**
 * Interface iTableData
 *
 * @package Combodo\iTop\Application\UI\Base\Component\Table\Data
 * @since 3.1.0
 */
interface iTableData
{
	/**
	 * Return data type.
	 *
	 * @return string
	 */
	public static function GetDataType(): string;

	/**
	 * @return mixed
	 */
	public function GetValue();
}