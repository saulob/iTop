<?php
/**
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\Table\Data;

/**
 * Class AttributeData
 *
 * @package Combodo\iTop\Application\UI\Base\Component\Table\Data
 * @since 3.1.0
 */
class AttributeData extends AbstractTableData
{
	public const DATA_TYPE = 'model:attribute';

	/** @var Object $oObject object */
	private object $oObject;

	/** @var string $sFieldCode field code */
	private string $sFieldCode;

	/** @var \AttributeDefinition attribute definition */
	private \AttributeDefinition $oAttributeDefinition;

	/** @var array $aArgs */
	private array $aArgs;

	/** @inheritdoc * */
	public static function GetDataType(): string
	{
		return self::DATA_TYPE;
	}

	/**
	 * Constructor.
	 *
	 * @param Object $oObject
	 * @param string $sFieldCode
	 *
	 * @throws \Exception
	 */
	public function __construct(object $oObject, string $sFieldCode)
	{
		// Retrieve constructor parameters
		$this->oObject = $oObject;
		$this->sFieldCode = $sFieldCode;

		// Initialization
		$this->Init();
	}

	/**
	 * @throws \Exception
	 */
	private function Init()
	{
		$this->oAttributeDefinition = \MetaModel::GetAttributeDef($this->GetObjectClass(), $this->GetFieldCode());
	}

	/**
	 * Return object class attribute belongs to.
	 *
	 * @return string
	 */
	public function GetObjectClass(): string
	{
		return get_class($this->GetObject());
	}

	/**
	 * Return object attribute belongs to.
	 *
	 * @return Object
	 */
	public function GetObject(): object
	{
		return $this->oObject;
	}

	/**
	 * Return attribute field code.
	 *
	 * @return string
	 */
	public function GetFieldCode(): string
	{
		return $this->sFieldCode;
	}

	/**
	 * Extract attribute definition.
	 *
	 * @return \AttributeDefinition
	 */
	public function GetAttributeDef(): \AttributeDefinition
	{
		return $this->oAttributeDefinition;
	}

	/**
	 * Get edit value.
	 *
	 * @return mixed
	 */
	public function GetEditValue()
	{
		return $this->oObject->GetEditValue($this->sFieldCode);
	}

	/**
	 * Get edit value.
	 *
	 * @return mixed
	 */
	public function GetAsHtmlValue()
	{
		return $this->oAttributeDefinition->GetAsHTML($this->oObject->Get($this->GetFieldCode()), $this->oObject, true);
	}

	/**
	 * Return object key.
	 *
	 * @return mixed
	 */
	public function GetKey()
	{
		return $this->oObject->GetKey();
	}

	/** @inheritdoc */
	public function GetValue()
	{
		return $this->oObject->Get($this->sFieldCode);
	}

	public function InitArgs($args, $oCurrentObj, \DBObject $oLinkedObj, $linkObjOrId, string $sAttCode, string $sSuffix, string $sInputId, string $sRemoteClass, string $sExtKeyToRemote, bool $bReadOnly)
	{
		$this->aArgs = $args;

		if (is_object($linkObjOrId) && (!$linkObjOrId->IsNew())) {
			$iKey = $linkObjOrId->GetKey();
			$this->aArgs['prefix'] = "{$sAttCode}{$sSuffix}[$iKey][";
			$this->aArgs['wizHelper'] = "oWizardHelper{$sInputId}{$iKey}";
			$this->aArgs['this'] = $linkObjOrId;
		}

		if ($this->GetFieldCode() === $sExtKeyToRemote) {
			// current field is the lnk extkey to the remote class
			$this->aArgs['replaceDependenciesByRemoteClassFields'] = true;
			$this->aArgs['wizHelperRemote'] = $this->aArgs['wizHelper'].'_remote';
			$aRemoteAttDefs = \MetaModel::GetZListAttDefsFilteredForIndirectRemoteClass($sRemoteClass);
			$aRemoteCodes = array_map(
				function ($value) {
					return $value->GetCode();
				},
				$aRemoteAttDefs
			);
			$this->aArgs['remoteCodes'] = $aRemoteCodes;
		} else {
			$this->aArgs['replaceDependenciesByRemoteClassFields'] = false;
		}
	}


	public function GetArgs()
	{
		return $this->aArgs;
	}
}