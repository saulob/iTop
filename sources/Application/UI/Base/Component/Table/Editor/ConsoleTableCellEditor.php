<?php
/**
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\Table\Editor;

use Combodo\iTop\Application\UI\Base\Component\Table\Data\AttributeData;
use Combodo\iTop\Application\UI\Base\Component\Table\Data\iTableData;
use Combodo\iTop\Application\UI\Base\Component\Table\Editor\iTableCellEditor;
use Combodo\iTop\Application\UI\Base\Component\Table\Table;
use Combodo\iTop\Application\UI\Base\Component\Table\TableColumn;
use Combodo\iTop\Application\UI\Base\Component\Table\TableWebPage;
use Combodo\iTop\Renderer\Bootstrap\FieldRenderer\BsSimpleFieldRenderer;
use Combodo\iTop\Renderer\Console\FieldRenderer\ConsoleSelectObjectFieldRenderer;
use Combodo\iTop\Renderer\RenderingOutput;
use PHPUnit\Util\Exception;

/**
 * Class ConsoleTableCellEditor
 *
 * @package Combodo\iTop\Application\UI\Base\Component\Table\Editor
 * @since 3.1.0
 */
class ConsoleTableCellEditor implements iTableCellEditor
{
	private Table $oTable;

	/**
	 * Constructor.
	 *
	 * @param Table $oTable
	 */
	public function __construct(Table $oTable)
	{
		$this->oTable = $oTable;
	}

	/** @inheritDoc */
	public function Render($oData): RenderingOutput
	{
		switch ($oData->GetDataType()) {
			case AttributeData::DATA_TYPE:
				return $this->RenderAttributeData($oData);
			default:
				return $oData->GetValue();
		}
	}

	/**
	 * Render attribute data.
	 **
	 *
	 * @return RenderingOutput
	 */
	function RenderAttributeData(AttributeData $oData): RenderingOutput
	{
		try {
			return $this->RenderWithLegacy($oData);
		}
		catch (\Exception $e) {
			return '<span class="ibo-pill ibo-is-failure">editor error</span>'; // create a default cell exception renderer
		}
	}


	private function RenderWithFieldRenderer(AttributeData $oData): RenderingOutput
	{
		$oField = $oData->GetAttributeDef()->MakeFormField($oData->GetObject());

		$oRenderer = new ConsoleSelectObjectFieldRenderer($oField);

		return $oRenderer->Render();
	}

	private function RenderWithLegacy(AttributeData $oData): RenderingOutput
	{
		$oWebPage = new \CaptureWebPage();
		$oRenderingOutput = new RenderingOutput();

		$oRenderingOutput->AddHtml('<div class="field_container" style="border:none;"><div class="field_data"><div class="field_value">'
			.\cmdbAbstractObject::GetFormElementForField(
				$oWebPage,
				$oData->GetObjectClass(),
				$oData->GetFieldCode(),
				$oData->GetAttributeDef(),
				$oData->GetValue(),
				$oData->GetEditValue(),
				$this->GetId($oData),
				"]",
				0,
				$oData->GetArgs()
			)
			.'</div></div></div>');

		$oRenderingOutput->AddJs($oWebPage->GetReadyJS());

		return $oRenderingOutput;
	}


	/**
	 * Return field id.
	 *
	 * @param \Combodo\iTop\Application\UI\Base\Component\Table\Data\AttributeData $oData
	 * @param bool $bSafe
	 *
	 * @return string
	 */
	public function GetId(AttributeData $oData, bool $bSafe = true): string
	{
		$sFieldId = "{$this->oTable->GetId()}_{$oData->GetFieldCode()}[{$oData->GetKey()}]";

		return ($bSafe) ? \utils::GetSafeId($sFieldId) : $sFieldId;
	}
}