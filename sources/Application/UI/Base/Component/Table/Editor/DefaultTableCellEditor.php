<?php
/**
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\Table\Editor;

use Combodo\iTop\Application\UI\Base\Component\Table\Data\iTableData;
use Combodo\iTop\Application\UI\Base\Component\Table\TableColumn;
use Combodo\iTop\Renderer\RenderingOutput;

/**
 * Class DefaultTableCellEditor
 *
 * @package Combodo\iTop\Application\UI\Base\Component\Table\Editor
 * @since 3.1.0
 */
class DefaultTableCellEditor implements iTableCellEditor
{

	/** @inheritdoc */
	public function Render($oData): RenderingOutput
	{
		$oRenderingOutput = new RenderingOutput();
		$oRenderingOutput->AddHtml("<input type=\"text\" value=\"{$oData->GetValue()}\">");

		return $oRenderingOutput;
	}
}