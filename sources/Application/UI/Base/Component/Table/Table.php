<?php
/**
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\Table;

use Combodo\iTop\Application\UI\Base\Component\Table\Editor\DefaultTableCellEditor;
use Combodo\iTop\Application\UI\Base\Component\Table\Editor\iTableCellEditor;
use Combodo\iTop\Application\UI\Base\Component\Table\Implementation\DatatableJSImplementation;
use Combodo\iTop\Application\UI\Base\Component\Table\Implementation\iTableJSImplementation;
use Combodo\iTop\Application\UI\Base\Component\Table\Renderer\DefaultTableCellRenderer;
use Combodo\iTop\Application\UI\Base\Component\Table\Renderer\iTableCellRenderer;
use Combodo\iTop\Application\UI\Base\UIBlock;

/**
 * Class Table
 *
 * @package Combodo\iTop\Application\UI\Base\Component\Table
 * @since 3.1.0
 */
class Table extends UIBlock
{
	// Overloaded constants
	public const BLOCK_CODE                            = 'ibo-table';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH        = 'base/components/table/layout';
	public const DEFAULT_JS_TEMPLATE_REL_PATH          = 'base/components/table/layout';
	public const DEFAULT_JS_ON_READY_TEMPLATE_REL_PATH = 'base/components/table/layout';
	public const DEFAULT_JS_FILES_REL_PATH             = [
		'node_modules/datatables.net/js/jquery.dataTables.min.js',
		'node_modules/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js',
		'node_modules/datatables.net-responsive/js/dataTables.responsive.min.js',
		'node_modules/datatables.net-scroller/js/dataTables.scroller.min.js',
		'node_modules/datatables.net-select/js/dataTables.select.min.js',
	];

	/** @var bool $bEditionModeEnable table is in editing mode (only column with edit mode set to true) */
	private bool $bEditionModeEnable;

	/** @var array array of TableColumn */
	private array $aColumns;

	/** @var array array of TableRow */
	private array $aRows;

	/** @var iTableCellRenderer */
	private iTableCellRenderer $oTableCellRenderer;

	/** @var iTableCellEditor $oTableCellEditor */
	private iTableCellEditor $oTableCellEditor;

	/** @var array aRenderedOutputs */
	private array $aRenderedOutputs;


	/**
	 * Constructor.
	 *
	 * @param string|null $sId table block identifier
	 */
	public function __construct(?string $sId = null)
	{
		parent::__construct($sId);

		// Retrieve constructor parameters

		// Initialization
		$this->Init();
	}

	/**
	 * Initialization.
	 *
	 * @return void
	 */
	private function Init()
	{
		$this->bEditionModeEnable = false;
		$this->aColumns = array();
		$this->aRows = array();
//		$this->oTableJSImplementation = new DatatableJSImplementation($this);
		$this->oTableCellRenderer = new DefaultTableCellRenderer();
		$this->oTableCellEditor = new DefaultTableCellEditor();
	}

	/**
	 * Enable edition mode.
	 *
	 * @return $this
	 */
	public function EnableEditionMode(): Table
	{
		$this->bEditionModeEnable = true;

		return $this;
	}

	/**
	 * Disable edition mode.
	 *
	 * @return $this
	 */
	public function DisableEditionMode(): Table
	{
		$this->bEditionModeEnable = false;

		return $this;
	}

	/**
	 * Return edition mode state.
	 *
	 * @return bool
	 */
	public function IsEditionModeEnabled(): bool
	{
		return $this->bEditionModeEnable;
	}

	/**
	 * Add a column to the table.
	 *
	 * @param \Combodo\iTop\Application\UI\Base\Component\Table\TableColumn $oColumn
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Table\Table
	 */
	public function AddColumn(TableColumn $oColumn): Table
	{
		$this->aColumns[] = $oColumn;

		return $this;
	}

	/**
	 * Add an array of columns to the table.
	 *
	 * @param array $aColumns
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Table\Table
	 */
	public function AddColumns(array $aColumns): Table
	{
		$this->aColumns = array_merge($this->aColumns, $aColumns);

		return $this;
	}

	/**
	 * Return columns of the table.
	 *
	 * @return array
	 */
	public function GetColumns(): array
	{
		return $this->aColumns;
	}

	/**
	 * Set columns of the table.
	 *
	 * @param array $aColumns columns of the table
	 *
	 * @return Table
	 */
	public function SetColumns(array $aColumns): Table
	{
		$this->aColumns = $aColumns;

		return $this;
	}

	/**
	 * @param string $sColumnName
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Table\TableColumn|null
	 */
	public function GetColumn(string $sColumnName): ?TableColumn
	{
		/** @var \Combodo\iTop\Application\UI\Base\Component\Table\TableColumn $oColumn */
		foreach ($this->aColumns as $oColumn) {
			if ($oColumn->GetName() === $sColumnName) {
				return $oColumn;
			}
		}

		return null;
	}

	/**
	 * Return rows of the table.
	 *
	 * @return array
	 */
	public function GetRows()
	{
		return $this->aRows;
	}

	/**
	 * Set rows of the table.
	 *
	 * @param array $aRows rows of the table
	 *
	 * @return Table
	 */
	public function SetRows(array $aRows): Table
	{
		$this->aRows = $aRows;

		return $this;
	}

	/**
	 * Add row to the table.
	 *
	 * @param TableRow $aRow row of the table
	 *
	 * @return Table
	 */
	public function AddRow(TableRow $aRow): Table
	{
		$this->aRows[] = $aRow;

		return $this;
	}

	/**
	 * Return table JS implementation.
	 *
	 * @return DatatableJSImplementation
	 */
	public function CreateDatatableJSImplementation(): DatatableJSImplementation
	{
		return new DatatableJSImplementation($this);
	}

	/**
	 * Return table cell renderer.
	 *
	 * @return iTableCellRenderer
	 */
	public function GetCellRenderer(): iTableCellRenderer
	{
		return $this->oTableCellRenderer;
	}

	/**
	 * Set table cell renderer.
	 *
	 * @param iTableCellRenderer $oCellRenderer
	 *
	 * @return Table
	 */
	public function SetCellRenderer(iTableCellRenderer $oCellRenderer): Table
	{
		$this->oTableCellRenderer = $oCellRenderer;

		return $this;
	}

	/**
	 * Return table cell editor.
	 *
	 * @return iTableCellEditor
	 */
	public function GetCellEditor(): iTableCellEditor
	{
		return $this->oTableCellEditor;
	}

	/**
	 * Set table cell editor.
	 *
	 * @param iTableCellEditor $oCellEditor
	 *
	 * @return Table
	 */
	public function SetCellEditor(iTableCellEditor $oCellEditor): Table
	{
		$this->oTableCellEditor = $oCellEditor;

		return $this;
	}

	/**
	 *
	 * @param \WebPage $oPage
	 *
	 * @return void
	 */
	public function RenderData()
	{
		$this->aRenderedOutputs = array();

		$oTableCellRenderer = $this->GetCellRenderer();
		$oTableCellEditor = $this->GetCellEditor();

		// iterate throw rows...
		/** @var \Combodo\iTop\Application\UI\Base\Component\Table\TableRow $oRow */
		foreach ($this->GetRows() as $oRow) {
			$aRenderedRowData = array();
			foreach ($oRow->GetData() as $sName => $oData) {
				// data column
				$oColumn = $this->GetColumn($sName);
				// cell renderer
				$oCellRenderer = $oColumn->GetCellRenderer() != null ? $oColumn->GetCellRenderer() : $oTableCellRenderer;
				$oCellEditor = $oColumn->GetCellEditor() != null ? $oColumn->GetCellEditor() : $oTableCellEditor;
				// render data
				if ($this->IsEditionModeEnabled() && $this->GetColumn($sName)->IsEditable()) {
					$aRenderedRowData[$sName] = $oCellEditor->Render($oData);
				} else {
					$aRenderedRowData[$sName] = $oCellRenderer->Render($oData);
				}
			}
			$this->aRenderedOutputs[] = $aRenderedRowData;
		}
	}

	/**
	 * @return array
	 */
	public function GetRenderedOutputs(): array
	{
		return $this->aRenderedOutputs;
	}
}