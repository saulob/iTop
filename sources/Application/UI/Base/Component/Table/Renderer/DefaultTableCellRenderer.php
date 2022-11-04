<?php
/**
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\Table\Renderer;

use Combodo\iTop\Application\UI\Base\Component\Table\Data\iTableData;
use Combodo\iTop\Application\UI\Base\Component\Table\Renderer\iTableCellRenderer;
use Combodo\iTop\Application\UI\Base\Component\Table\TableColumn;
use Combodo\iTop\Renderer\RenderingOutput;

/**
 * Class DefaultTableCellRenderer
 *
 * @package Combodo\iTop\Application\UI\Base\Component\Table\Renderer
 * @since 3.1.0
 */
class DefaultTableCellRenderer implements iTableCellRenderer
{
	/** @inheritdoc */
	public function Render($oData): RenderingOutput
	{
		$oRenderingOutput = new RenderingOutput();
		$oRenderingOutput->AddHtml($oData);

		return $oRenderingOutput;
	}
}