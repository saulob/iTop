<?php
/**
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\Table\Renderer;

use Combodo\iTop\Application\UI\Base\Component\Table\Data\AttributeData;
use Combodo\iTop\Application\UI\Base\Component\Table\Data\iTableData;
use Combodo\iTop\Application\UI\Base\Component\Table\Table;
use Combodo\iTop\Application\UI\Base\Component\Table\TableColumn;
use Combodo\iTop\Renderer\RenderingOutput;

/**
 * Class NumberCellRenderer
 *
 * @package Combodo\iTop\Application\UI\Base\Component\Table\Renderer
 * @since 3.1.0
 */
class NumberCellRenderer implements iTableCellRenderer
{
	/** @var string $sUnit number unit */
	private string $sUnit;

	/** @var int $iNumberDigit number digit */
	private int $iNumberDigit;

	public function __construct(string $sUnit, int $iNumberDigit = 1)
	{
		$this->iNumberDigit = $iNumberDigit;
		$this->sUnit = $sUnit;
	}

	/** @inheritDoc */
	public function Render($oData): RenderingOutput
	{
		$oRenderValue = new RenderingOutput();
		$sValue = sprintf("%.{$this->iNumberDigit}f", $oData);
		$oRenderValue->AddHtml("<span class=\"ibo-field-badge\"><big>{$sValue}</big>&nbsp;{$this->sUnit}</span>");

		return $oRenderValue;
	}

}