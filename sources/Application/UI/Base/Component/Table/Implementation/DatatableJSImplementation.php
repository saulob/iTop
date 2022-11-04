<?php
/**
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\Table\Implementation;

use Combodo\iTop\Application\UI\Base\Component\Table\Table;

/**
 * Class DatatableJSImplementation
 *
 * @package Combodo\iTop\Application\UI\Base\Component\Table\Implementation
 * @since 3.1.0
 */
class DatatableJSImplementation
{
	/** @var \Combodo\iTop\Application\UI\Base\Component\Table\Table $oTable */
	private Table $oTable;

	/**
	 * Constructor.
	 *
	 * @param Table $oTable
	 */
	public function __construct(Table $oTable)
	{
		// Retrieve constructor parameters
		$this->oTable = $oTable;
	}

	public function GetColumns()
	{
		$result = array();

		// iterate throw columns...
		/** @var \Combodo\iTop\Application\UI\Base\Component\Table\TableColumn $oColumn */
		foreach ($this->oTable->GetColumns() as $oColumn) {
			// convert to datatable representation
			$result[] = [
				'data'      => $oColumn->GetName(),
				'name'      => $oColumn->GetName(),
				'title'     => $oColumn->GetLabel(),
				'orderable' => $oColumn->IsSortable(),
			];
		}

		return $result;
	}

	public function GetData()
	{
		$oRenderOutputs = $this->oTable->GetRenderedOutputs();
		$aData = array();
		/** @var array $aRow */
		foreach ($oRenderOutputs as $aRow) {
			$aNewRow = array();
			/**
			 * @var  $sKey string
			 * @var  $oRendererOutput \Combodo\iTop\Renderer\RenderingOutput
			 */
			foreach ($aRow as $sKey => $oRendererOutput) {
				$aNewRow[$sKey] = $oRendererOutput->GetHtml();
			}
			$aData[] = $aNewRow;
		}

		return $aData;
	}

	public function GetAllJS()
	{
		$oRenderOutputs = $this->oTable->GetRenderedOutputs();
		$sResult = '';
		/** @var array $aRow */
		foreach ($oRenderOutputs as $aRow) {
			/**
			 * @var  $sKey string
			 * @var  $oRendererOutput \Combodo\iTop\Renderer\RenderingOutput
			 */
			foreach ($aRow as $sKey => $oRendererOutput) {
				$sResult .= $oRendererOutput->GetJs();
			}
		}

		return $sResult;
	}
}