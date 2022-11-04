<?php
/**
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\Table;

use Combodo\iTop\Application\UI\Base\Component\Table\Editor\iTableCellEditor;
use Combodo\iTop\Application\UI\Base\Component\Table\Renderer\iTableCellRenderer;

/**
 * Class TableColumn
 *
 * @package Combodo\iTop\Application\UI\Base\Component\Table
 * @since 3.1.0
 */
class TableColumn
{
	/** @var string $sName column name */
	private string $sName;

	/** @var string $sLabel column label */
	private string $sLabel;

	/** @var bool $bIsEditable */
	private bool $bIsEditable;

	/** @var bool $bIsSortable */
	private bool $bIsSortable;

	/** @var ?iTableCellRenderer */
	private ?iTableCellRenderer $oTableCellRenderer;

	/** @var ?iTableCellEditor $oTableCellEditor */
	private ?iTableCellEditor $oTableCellEditor;

	/**
	 * Constructor.
	 *
	 * @param string $sName
	 * @param string $sLabel
	 */
	public function __construct(string $sName, string $sLabel)
	{
		// Retrieve constructor parameters
		$this->sName = $sName;
		$this->sLabel = $sLabel;

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
		$this->bIsEditable = false;
		$this->bIsSortable = false;
		$this->oTableCellRenderer = null;
		$this->oTableCellEditor = null;
	}

	/**
	 * Get column name.
	 *
	 * @return string
	 */
	public function GetName()
	{
		return $this->sName;
	}

	/**
	 * Get column label.
	 *
	 * @return string
	 */
	public function GetLabel()
	{
		return $this->sLabel;
	}

	/**
	 * Return editable state.
	 *
	 * @return bool
	 */
	public function IsEditable()
	{
		return $this->bIsEditable;
	}

	/**
	 * Set column editable state.
	 *
	 * @param bool $bIsEditable
	 *
	 * @return $this
	 */
	public function SetEditable(bool $bIsEditable): TableColumn
	{
		$this->bIsEditable = $bIsEditable;

		return $this;
	}

	/**
	 * Return sortable state.
	 *
	 * @return bool
	 */
	public function IsSortable(): bool
	{
		return $this->bIsSortable;
	}

	/**
	 * Set column sortable state.
	 *
	 * @param bool $bIsSortable
	 *
	 * @return $this
	 */
	public function SetSortable(bool $bIsSortable): TableColumn
	{
		$this->bIsSortable = $bIsSortable;

		return $this;
	}

	/**
	 * Return table cell renderer.
	 *
	 * @return ?iTableCellRenderer
	 */
	public function GetCellRenderer(): ?iTableCellRenderer
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
	public function SetCellRenderer(iTableCellRenderer $oCellRenderer): TableColumn
	{
		$this->oTableCellRenderer = $oCellRenderer;

		return $this;
	}

	/**
	 * Return table cell editor.
	 *
	 * @return ?iTableCellEditor
	 */
	public function GetCellEditor(): ?iTableCellEditor
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
	 * Maker.
	 *
	 * @param string $sName
	 * @param string $sLabel
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Table\TableColumn
	 */
	public static function Make(string $sName, string $sLabel)
	{
		return new TableColumn($sName, $sLabel);
	}
}