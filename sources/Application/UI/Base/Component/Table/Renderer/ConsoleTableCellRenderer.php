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
 * Class ConsoleTableCellRenderer
 *
 * @package Combodo\iTop\Application\UI\Base\Component\Table\Renderer
 * @since 3.1.0
 */
class ConsoleTableCellRenderer implements iTableCellRenderer
{
	/** @var \Combodo\iTop\Application\UI\Base\Component\Table\Table $oTable */
	private Table $oTable;

	public function __construct(Table $oTable)
	{
		$this->oTable = $oTable;
	}

	/** @inheritDoc */
	public function Render($oData): RenderingOutput
	{
		if (is_string($oData)) {
			return $this->RenderStringData($oData);
		} else if (is_bool($oData)) {
			return $this->RenderBoolData($oData);
		} else if ($oData instanceof iTableData) {
			switch ($oData->GetDataType()) {
				case AttributeData::DATA_TYPE:
					return $this->RenderAttributeData($oData);
				default:
					return $oData->GetValue();
			}
		}
	}

	
	function RenderStringData(string $oData): RenderingOutput
	{
		$oRenderingOutput = new RenderingOutput();
		$oRenderingOutput->AddHtml($oData);

		return $oRenderingOutput;
	}

	function RenderBoolData(bool $oData): RenderingOutput
	{
		$oRenderingOutput = new RenderingOutput();
		if ($oData) {
			$oRenderingOutput->AddHtml('<i class="fas fa-check-circle" style="font-size: 1.6rem;color: #689f38;"></i>');
		} else {
			$oRenderingOutput->AddHtml('');
		}

		return $oRenderingOutput;
	}

	/**
	 * Render attribute data.
	 *
	 * @param \WebPage $oPage
	 *
	 * @return string
	 */
	function RenderAttributeData(AttributeData $oData): RenderingOutput
	{
		try {
			$oRenderingOutput = new RenderingOutput();
			$oRenderingOutput->AddHtml($oData->GetAsHtmlValue());

			return $oRenderingOutput;
		}
		catch (\Exception $e) {
			return '<span class="ibo-pill ibo-is-failure">renderer error</span>'; // create a default cell exception rendereerer
		}
	}


	public static function GetFormElementForField($oPage, $sClass, $sAttCode, $oAttDef, $value = '', $sDisplayValue = '', $iId = '', $sNameSuffix = '', $iFlags = 0, $aArgs = array(), $bPreserveCurrentValue = true, &$sInputType = '')
	{
		$sFormPrefix = isset($aArgs['formPrefix']) ? $aArgs['formPrefix'] : '';
		$sFieldPrefix = isset($aArgs['prefix']) ? $sFormPrefix.$aArgs['prefix'] : $sFormPrefix;
		if ($sDisplayValue == '') {
			$sDisplayValue = $value;
		}

		if (isset($aArgs[$sAttCode]) && empty($value)) {
			// default value passed by the context (either the app context of the operation)
			$value = $aArgs[$sAttCode];
		}

		if (!empty($iId)) {
			$iInputId = $iId;
		} else {
			$iInputId = utils::GetUniqueId();
		}

		$sHTMLValue = '';
		if (!$oAttDef->IsExternalField()) {
			$bMandatory = 'false';
			if ((!$oAttDef->IsNullAllowed()) || ($iFlags & OPT_ATT_MANDATORY)) {
				$bMandatory = 'true';
			}
			$sValidationSpan = "<span class=\"form_validation ibo-field-validation\" id=\"v_{$iId}\"></span>";
			$sReloadSpan = "<span class=\"field_status\" id=\"fstatus_{$iId}\"></span>";
			$sHelpText = utils::EscapeHtml($oAttDef->GetHelpOnEdition());

			// mandatory field control vars
			$aEventsList = array(); // contains any native event (like change), plus 'validate' for the form submission
			$sNullValue = $oAttDef->GetNullValue(); // used for the ValidateField() call in js/forms-json-utils.js
			$sFieldToValidateId = $iId; // can be different than the displayed field (for example in TagSet)

			// List of attributes that depend on the current one
			// Might be modified depending on the current field
			$sWizardHelperJsVarName = "oWizardHelper{$sFormPrefix}";
			$aDependencies = MetaModel::GetDependentAttributes($sClass, $sAttCode);

			switch ($oAttDef->GetEditClass()) {
				case 'Date':
					$sInputType = self::ENUM_INPUT_TYPE_SINGLE_INPUT;
					$aEventsList[] = 'validate';
					$aEventsList[] = 'keyup';
					$aEventsList[] = 'change';

					$sPlaceholderValue = 'placeholder="'.utils::EscapeHtml(AttributeDate::GetFormat()->ToPlaceholder()).'"';
					$sDisplayValueForHtml = utils::EscapeHtml($sDisplayValue);
					$sHTMLValue = <<<HTML
<div class="field_input_zone field_input_date ibo-input-wrapper ibo-input-date-wrapper" data-validation="untouched">
	<input title="$sHelpText" class="date-pick ibo-input ibo-input-date" type="text" {$sPlaceholderValue} name="attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}" value="{$sDisplayValueForHtml}" id="{$iId}" autocomplete="off" />
</div>{$sValidationSpan}{$sReloadSpan}
HTML;
					break;

				case 'DateTime':
					$sInputType = self::ENUM_INPUT_TYPE_SINGLE_INPUT;
					$aEventsList[] = 'validate';
					$aEventsList[] = 'keyup';
					$aEventsList[] = 'change';

					$sPlaceholderValue = 'placeholder="'.utils::EscapeHtml(AttributeDateTime::GetFormat()->ToPlaceholder()).'"';
					$sDisplayValueForHtml = utils::EscapeHtml($sDisplayValue);
					$sHTMLValue = <<<HTML
<div class="field_input_zone field_input_datetime ibo-input-wrapper ibo-input-datetime-wrapper" data-validation="untouched">
	<input title="{$sHelpText}" class="datetime-pick ibo-input ibo-input-datetime" type="text" size="19" {$sPlaceholderValue} name="attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}" value="{$sDisplayValueForHtml}" id="{$iId}" autocomplete="off" />
</div>{$sValidationSpan}{$sReloadSpan}
HTML;
					break;

				case 'Duration':
					$sInputType = self::ENUM_INPUT_TYPE_MULTIPLE_INPUTS;
					$aEventsList[] = 'validate';
					$aEventsList[] = 'change';
					$oPage->add_ready_script("$('#{$iId}_d').on('keyup change', function(evt, sFormId) { return UpdateDuration('$iId'); });");
					$oPage->add_ready_script("$('#{$iId}_h').on('keyup change', function(evt, sFormId) { return UpdateDuration('$iId'); });");
					$oPage->add_ready_script("$('#{$iId}_m').on('keyup change', function(evt, sFormId) { return UpdateDuration('$iId'); });");
					$oPage->add_ready_script("$('#{$iId}_s').on('keyup change', function(evt, sFormId) { return UpdateDuration('$iId'); });");
					$aVal = AttributeDuration::SplitDuration($value);
					$sDays = "<input class=\"ibo-input ibo-input-duration\" title=\"$sHelpText\" type=\"text\" size=\"3\" name=\"attr_{$sFieldPrefix}{$sAttCode}[d]{$sNameSuffix}\" value=\"{$aVal['days']}\" id=\"{$iId}_d\"/>";
					$sHours = "<input class=\"ibo-input ibo-input-duration\" title=\"$sHelpText\" type=\"text\" size=\"2\" name=\"attr_{$sFieldPrefix}{$sAttCode}[h]{$sNameSuffix}\" value=\"{$aVal['hours']}\" id=\"{$iId}_h\"/>";
					$sMinutes = "<input class=\"ibo-input ibo-input-duration\" title=\"$sHelpText\" type=\"text\" size=\"2\" name=\"attr_{$sFieldPrefix}{$sAttCode}[m]{$sNameSuffix}\" value=\"{$aVal['minutes']}\" id=\"{$iId}_m\"/>";
					$sSeconds = "<input class=\"ibo-input ibo-input-duration\" title=\"$sHelpText\" type=\"text\" size=\"2\" name=\"attr_{$sFieldPrefix}{$sAttCode}[s]{$sNameSuffix}\" value=\"{$aVal['seconds']}\" id=\"{$iId}_s\"/>";
					$sHidden = "<input type=\"hidden\" id=\"{$iId}\" value=\"".utils::EscapeHtml($value)."\"/>";
					$sHTMLValue = Dict::Format('UI:DurationForm_Days_Hours_Minutes_Seconds', $sDays, $sHours, $sMinutes, $sSeconds).$sHidden."&nbsp;".$sValidationSpan.$sReloadSpan;
					$oPage->add_ready_script("$('#{$iId}').on('update', function(evt, sFormId) { return ToggleDurationField('$iId'); });");
					break;

				case 'Password':
					$sInputType = self::ENUM_INPUT_TYPE_PASSWORD;
					$aEventsList[] = 'validate';
					$aEventsList[] = 'keyup';
					$aEventsList[] = 'change';
					$sHTMLValue = "<div class=\"field_input_zone field_input_password ibo-input-wrapper ibo-input-password-wrapper\" data-validation=\"untouched\"><input class=\"ibo-input ibo-input-password\" title=\"$sHelpText\" type=\"password\" name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}\" value=\"".utils::EscapeHtml($value)."\" id=\"$iId\"/></div>{$sValidationSpan}{$sReloadSpan}";
					break;

				case 'OQLExpression':
				case 'Text':
					$sInputType = self::ENUM_INPUT_TYPE_TEXTAREA;
					$aEventsList[] = 'validate';
					$aEventsList[] = 'keyup';
					$aEventsList[] = 'change';

					$sEditValue = $oAttDef->GetEditValue($value);
					$sEditValueForHtml = utils::EscapeHtml($sEditValue);
					$sFullscreenLabelForHtml = utils::EscapeHtml(Dict::S('UI:ToggleFullScreen'));

					$aStyles = array();
					$sStyle = '';
					$sWidth = $oAttDef->GetWidth();
					if (!empty($sWidth)) {
						$aStyles[] = 'width:'.$sWidth;
					}
					$sHeight = $oAttDef->GetHeight();
					if (!empty($sHeight)) {
						$aStyles[] = 'height:'.$sHeight;
					}
					if (count($aStyles) > 0) {
						$sStyle = 'style="'.implode('; ', $aStyles).'"';
					}

					$aTextareaCssClasses = [];

					if ($oAttDef->GetEditClass() == 'OQLExpression') {
						$aTextareaCssClasses[] = 'ibo-query-oql';
						$aTextareaCssClasses[] = 'ibo-is-code';
						// N°3227 button to open predefined queries dialog
						$sPredefinedBtnId = 'predef_btn_'.$sFieldPrefix.$sAttCode.$sNameSuffix;
						$sSearchQueryLbl = Dict::S('UI:Edit:SearchQuery');
						$oPredefQueryButton = ButtonUIBlockFactory::MakeIconAction(
							'fas fa-search',
							$sSearchQueryLbl,
							null,
							null,
							false,
							$sPredefinedBtnId
						);
						$oPredefQueryButton->AddCSSClass('ibo-action-button')
							->SetOnClickJsCode(
								<<<JS
	oACWidget_{$iId}.Search();
JS
							);
						$oPredefQueryRenderer = new BlockRenderer($oPredefQueryButton);
						$sAdditionalStuff = $oPredefQueryRenderer->RenderHtml();
						$oPage->add_ready_script($oPredefQueryRenderer->RenderJsInline($oPredefQueryButton::ENUM_JS_TYPE_ON_INIT));

						$oPage->add_ready_script(<<<JS
// noinspection JSAnnotator
oACWidget_{$iId} = new ExtKeyWidget('$iId', 'QueryOQL', 'SELECT QueryOQL WHERE is_template = \'yes\'', '$sSearchQueryLbl', true, null, null, true, true, 'oql');
// noinspection JSAnnotator
oACWidget_{$iId}.emptyHtml = "<div style=\"background: #fff; border:0; text-align:center; vertical-align:middle;\"><p>Use the search form above to search for objects to be added.</p></div>";

if ($('#ac_dlg_{$iId}').length == 0)
{
	$('body').append('<div id="ac_dlg_{$iId}"></div>');
	$('#ac_dlg_{$iId}').dialog({ 
			width: $(window).width()*0.8, 
			height: $(window).height()*0.8, 
			autoOpen: false, 
			modal: true, 
			title: '$sSearchQueryLbl', 
			resizeStop: oACWidget_{$iId}.UpdateSizes, 
			close: oACWidget_{$iId}.OnClose 
		});
}
JS
						);

						// test query link
						$sTestResId = 'query_res_'.$sFieldPrefix.$sAttCode.$sNameSuffix; //$oPage->GetUniqueId();
						$sBaseUrl = utils::GetAbsoluteUrlAppRoot().'pages/run_query.php?expression=';
						$sTestQueryLbl = Dict::S('UI:Edit:TestQuery');
						$oTestQueryButton = ButtonUIBlockFactory::MakeIconAction(
							'fas fa-play',
							$sTestQueryLbl,
							null,
							null,
							false,
							$sTestResId
						);
						$oTestQueryButton->AddCSSClass('ibo-action-button')
							->SetOnClickJsCode(
								<<<JS
var sQueryRaw = $("#$iId").val(),
sQueryEncoded = encodeURI(sQueryRaw);
window.open('$sBaseUrl' + sQueryEncoded, '_blank');
JS
							);
						$oTestQueryRenderer = new BlockRenderer($oTestQueryButton);
						$sAdditionalStuff .= $oTestQueryRenderer->RenderHtml();
						$oPage->add_ready_script($oTestQueryRenderer->RenderJsInline($oTestQueryButton::ENUM_JS_TYPE_ON_INIT));
					} else {
						$sAdditionalStuff = '';
					}

					// Ok, the text area is drawn here
					$sTextareCssClassesAsString = implode(' ', $aTextareaCssClasses);
					$sHTMLValue = <<<HTML
{$sAdditionalStuff}
<div class="field_input_zone field_input_text ibo-input-wrapper ibo-input-text-wrapper" data-validation="untouched">
	<div class="f_i_text_header">
		<span class="fullscreen_button" title="{$sFullscreenLabelForHtml}"></span>
	</div>
	<textarea class="ibo-input ibo-input-text {$sTextareCssClassesAsString}" title="{$sHelpText}" name="attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}" rows="8" cols="40" id="{$iId}" {$sStyle} >{$sEditValueForHtml}</textarea>
</div>
{$sValidationSpan}{$sReloadSpan}
HTML;
					$oPage->add_ready_script(
						<<<EOF
                        $('#$iId').closest('.field_input_text').find('.fullscreen_button').on('click', function(oEvent){
                            var oOriginField = $('#$iId').closest('.field_input_text');
                            var oClonedField = oOriginField.clone();
                            oClonedField.addClass('fullscreen').appendTo('body');
                            oClonedField.find('.fullscreen_button').on('click', function(oEvent){
                                // Copying value to origin field
                                oOriginField.find('textarea').val(oClonedField.find('textarea').val());
                                oClonedField.remove();
                                // Triggering change event
                                oOriginField.find('textarea').triggerHandler('change');
                            });
                        });
EOF
					);
					break;

				// In 3.0 not used for activity panel but kept  for bulk modify and bulk-event extension
				case 'CaseLog':
					$sInputType = self::ENUM_INPUT_TYPE_HTML_EDITOR;
					$aStyles = array();
					$sStyle = '';
					$sWidth = $oAttDef->GetWidth();
					if (!empty($sWidth)) {
						$aStyles[] = 'width:'.$sWidth;
					}
					$sHeight = $oAttDef->GetHeight();
					if (!empty($sHeight)) {
						$aStyles[] = 'height:'.$sHeight;
					}
					if (count($aStyles) > 0) {
						$sStyle = 'style="'.implode('; ', $aStyles).'"';
					}

					$sHeader = '<div class="ibo-caselog-entry-form--actions"><div class="""ibo-caselog-entry-form--actions" data-role="ibo-caselog-entry-form--action-buttons--extra-actions"></div></div>'; // will be hidden in CSS (via :empty) if it remains empty
					$sEditValue = is_object($value) ? $value->GetModifiedEntry('html') : '';
					$sPreviousLog = is_object($value) ? $value->GetAsHTML($oPage, true /* bEditMode */, array('AttributeText', 'RenderWikiHtml')) : '';
					$iEntriesCount = is_object($value) ? count($value->GetIndex()) : 0;
					$sHidden = "<input type=\"hidden\" id=\"{$iId}_count\" value=\"$iEntriesCount\"/>"; // To know how many entries the case log already contains

					$sHTMLValue = "$sHeader<div class=\"ibo-caselog-entry-form--text-input\" $sStyle data-role=\"ibo-caselog-entry-form--text-input\">";
					$sHTMLValue .= "<textarea class=\"htmlEditor ibo-input-richtext-placeholder\" style=\"border:0;width:100%\" title=\"$sHelpText\" name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}\" rows=\"8\" cols=\"40\" id=\"$iId\">".utils::EscapeHtml($sEditValue)."</textarea>";
					$sHTMLValue .= "$sPreviousLog</div>{$sValidationSpan}{$sReloadSpan}$sHidden";

					// Note: This should be refactored for all types of attribute (see at the end of this function) but as we are doing this for a maintenance release, we are scheduling it for the next main release in to order to avoid regressions as much as possible.
					$sNullValue = $oAttDef->GetNullValue();
					if (!is_numeric($sNullValue)) {
						$sNullValue = "'$sNullValue'"; // Add quotes to turn this into a JS string if it's not a number
					}
					$sOriginalValue = ($iFlags & OPT_ATT_MUSTCHANGE) ? json_encode($value->GetModifiedEntry('html')) : 'undefined';

					$oPage->add_ready_script("$('#$iId').on('keyup change validate', function(evt, sFormId) { return ValidateCaseLogField('$iId', $bMandatory, sFormId, $sNullValue, $sOriginalValue) } );"); // Custom validation function

					// Replace the text area with CKEditor
					// To change the default settings of the editor,
					// a) edit the file /js/ckeditor/config.js
					// b) or override some of the configuration settings, using the second parameter of ckeditor()
					$aConfig = utils::GetCkeditorPref();
					$aConfig['placeholder'] = Dict::S('UI:CaseLogTypeYourTextHere');

					// - Final config
					$sConfigJS = json_encode($aConfig);

					WebResourcesHelper::EnableCKEditorToWebPage($oPage);
					$oPage->add_ready_script("$('#$iId').ckeditor(function() { /* callback code */ }, $sConfigJS);"); // Transform $iId into a CKEdit

					$oPage->add_ready_script(
						<<<EOF
$('#$iId').on('update', function(evt){
	BlockField('cke_$iId', $('#$iId').attr('disabled'));
	//Delayed execution - ckeditor must be properly initialized before setting readonly
	var retryCount = 0;
	var oMe = $('#$iId');
	var delayedSetReadOnly = function () {
		if (oMe.data('ckeditorInstance').editable() == undefined && retryCount++ < 10) {
			setTimeout(delayedSetReadOnly, retryCount * 100); //Wait a while longer each iteration
		}
		else
		{
			oMe.data('ckeditorInstance').setReadOnly(oMe.prop('disabled'));
		}
	};
	setTimeout(delayedSetReadOnly, 50);
});
EOF
					);
					break;

				case 'HTML':
					$sInputType = self::ENUM_INPUT_TYPE_HTML_EDITOR;
					$sEditValue = $oAttDef->GetEditValue($value);
					$oWidget = new UIHTMLEditorWidget($iId, $oAttDef, $sNameSuffix, $sFieldPrefix, $sHelpText,
						$sValidationSpan.$sReloadSpan, $sEditValue, $bMandatory);
					$sHTMLValue = $oWidget->Display($oPage, $aArgs);
					break;

				case 'LinkedSet':
					$sInputType = self::ENUM_INPUT_TYPE_LINKEDSET;
					if ($oAttDef->IsIndirect()) {
						$oWidget = new UILinksWidget($sClass, $sAttCode, $iId, $sNameSuffix,
							$oAttDef->DuplicatesAllowed());
					} else {
						$oWidget = new UILinksWidgetDirect($sClass, $sAttCode, $iId, $sNameSuffix);
					}
					$aEventsList[] = 'validate';
					$aEventsList[] = 'change';
					$oObj = isset($aArgs['this']) ? $aArgs['this'] : null;
					$sHTMLValue = $oWidget->Display($oPage, $value, array(), $sFormPrefix, $oObj);
					break;

				case 'Document':
					$sInputType = self::ENUM_INPUT_TYPE_DOCUMENT;
					$aEventsList[] = 'validate';
					$aEventsList[] = 'change';
					$oDocument = $value; // Value is an ormDocument object

					$sFileName = '';
					if (is_object($oDocument)) {
						$sFileName = $oDocument->GetFileName();
					}
					$sFileNameForHtml = utils::EscapeHtml($sFileName);
					$bHasFile = !empty($sFileName);

					$iMaxFileSize = utils::ConvertToBytes(ini_get('upload_max_filesize'));
					$sRemoveBtnLabelForHtml = utils::EscapeHtml(Dict::S('UI:Button:RemoveDocument'));
					$sExtraCSSClassesForRemoveButton = $bHasFile ? '' : 'ibo-is-hidden';

					$sHTMLValue = <<<HTML
<div class="field_input_zone field_input_document">
	<input type="hidden" name="MAX_FILE_SIZE" value="{$iMaxFileSize}" />
	<input type="hidden" id="do_remove_{$iId}" name="attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}[remove]" value="0"/>
	<input name="attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}[filename]" type="hidden" id="{$iId}" value="{$sFileNameForHtml}"/>
	<span id="name_{$iInputId}" >{$sFileNameForHtml}</span>&#160;&#160;
	<button id="remove_attr_{$iId}" class="ibo-button ibo-is-alternative ibo-is-danger {$sExtraCSSClassesForRemoveButton}" data-role="ibo-button" type="button" data-tooltip-content="{$sRemoveBtnLabelForHtml}" onClick="$('#file_{$iId}').val(''); UpdateFileName('{$iId}', '');">
		<span class="fas fa-trash"></span>
	</button>
</div>
<input title="{$sHelpText}" name="attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}[fcontents]" type="file" id="file_{$iId}" onChange="UpdateFileName('{$iId}', this.value)"/>
{$sValidationSpan}{$sReloadSpan}
HTML;

					if ($sFileName == '') {
						$oPage->add_ready_script("$('#remove_attr_{$iId}').addClass('ibo-is-hidden');");
					}
					break;

				case 'Image':
					$sInputType = self::ENUM_INPUT_TYPE_IMAGE;
					$aEventsList[] = 'validate';
					$aEventsList[] = 'change';
					$oPage->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/edit_image.js');
					$oDocument = $value; // Value is an ormDocument objectm
					$sDefaultUrl = $oAttDef->Get('default_image');
					if (is_object($oDocument) && !$oDocument->IsEmpty()) {
						$sUrl = 'data:'.$oDocument->GetMimeType().';base64,'.base64_encode($oDocument->GetData());
					} else {
						$sUrl = null;
					}

					$sHTMLValue = "<div class=\"field_input_zone ibo-input-image-wrapper\"><div id=\"edit_$iInputId\" class=\"ibo-input-image\"></div></div>\n";
					$sHTMLValue .= "{$sValidationSpan}{$sReloadSpan}\n";

					$aEditImage = array(
						'input_name'        => 'attr_'.$sFieldPrefix.$sAttCode.$sNameSuffix,
						'max_file_size'     => utils::ConvertToBytes(ini_get('upload_max_filesize')),
						'max_width_px'      => $oAttDef->Get('display_max_width'),
						'max_height_px'     => $oAttDef->Get('display_max_height'),
						'current_image_url' => $sUrl,
						'default_image_url' => $sDefaultUrl,
						'labels'            => array(
							'reset_button'  => utils::EscapeHtml(Dict::S('UI:Button:ResetImage')),
							'remove_button' => utils::EscapeHtml(Dict::S('UI:Button:RemoveImage')),
							'upload_button' => !empty($sHelpText) ? $sHelpText : utils::EscapeHtml(Dict::S('UI:Button:UploadImage')),
						),
					);
					$sEditImageOptions = json_encode($aEditImage);
					$oPage->add_ready_script("$('#edit_$iInputId').edit_image($sEditImageOptions);");
					break;

				case 'StopWatch':
					$sHTMLValue = "The edition of a stopwatch is not allowed!!!";
					break;

				case 'List':
					// Not editable for now...
					$sHTMLValue = '';
					break;

				case 'One Way Password':
					$sInputType = self::ENUM_INPUT_TYPE_PASSWORD;
					$aEventsList[] = 'validate';
					$oWidget = new UIPasswordWidget($sAttCode, $iId, $sNameSuffix);
					$sHTMLValue = $oWidget->Display($oPage, $aArgs);
					// Event list & validation is handled  directly by the widget
					break;

				case 'ExtKey':
					/** @var \AttributeExternalKey $oAttDef */
					$aEventsList[] = 'validate';
					$aEventsList[] = 'change';

					if ($bPreserveCurrentValue) {
						$oAllowedValues = MetaModel::GetAllowedValuesAsObjectSet($sClass, $sAttCode, $aArgs, '', $value);
					} else {
						$oAllowedValues = MetaModel::GetAllowedValuesAsObjectSet($sClass, $sAttCode, $aArgs);
					}
					$sFieldName = $sFieldPrefix.$sAttCode.$sNameSuffix;
					$aExtKeyParams = $aArgs;
					$aExtKeyParams['iFieldSize'] = $oAttDef->GetMaxSize();
					$aExtKeyParams['iMinChars'] = $oAttDef->GetMinAutoCompleteChars();
					$sHTMLValue = UIExtKeyWidget::DisplayFromAttCode($oPage, $sAttCode, $sClass, $oAttDef->GetLabel(),
						$oAllowedValues, $value, $iId, $bMandatory, $sFieldName, $sFormPrefix, $aExtKeyParams, false, $sInputType);
					$sHTMLValue .= "<!-- iFlags: $iFlags bMandatory: $bMandatory -->\n";

					$bHasExtKeyUpdatingRemoteClassFields = (
						array_key_exists('replaceDependenciesByRemoteClassFields', $aArgs)
						&& ($aArgs['replaceDependenciesByRemoteClassFields'])
					);
					if ($bHasExtKeyUpdatingRemoteClassFields) {
						// On this field update we need to update all the corresponding remote class fields
						// Used when extkey widget is in a linkedset indirect
						$sWizardHelperJsVarName = $aArgs['wizHelperRemote'];
						$aDependencies = $aArgs['remoteCodes'];
					}

					break;

				case 'RedundancySetting':
					$sHTMLValue .= '<div id="'.$iId.'">';
					$sHTMLValue .= $oAttDef->GetDisplayForm($value, $oPage, true);
					$sHTMLValue .= '</div>';
					$sHTMLValue .= '<div>'.$sValidationSpan.$sReloadSpan.'</div>';
					$oPage->add_ready_script("$('#$iId :input').on('keyup change validate', function(evt, sFormId) { return ValidateRedundancySettings('$iId',sFormId); } );"); // Custom validation function
					break;

				case 'CustomFields':
					$sHTMLValue .= '<div id="'.$iId.'_console_form">';
					$sHTMLValue .= '<div id="'.$iId.'_field_set">';
					$sHTMLValue .= '</div></div>';
					$sHTMLValue .= '<div>'.$sReloadSpan.'</div>'; // No validation span for this one: it does handle its own validation!
					$sHTMLValue .= "<input name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}\" type=\"hidden\" id=\"$iId\" value=\"\"/>\n";

					$oForm = $value->GetForm($sFormPrefix);
					$oPredefQueryRenderer = new ConsoleFormRenderer($oForm);
					$aRenderRes = $oPredefQueryRenderer->Render();

					$aFieldSetOptions = array(
						'field_identifier_attr' => 'data-field-id',
						// convention: fields are rendered into a div and are identified by this attribute
						'fields_list'           => $aRenderRes,
						'fields_impacts'        => $oForm->GetFieldsImpacts(),
						'form_path'             => $oForm->GetId(),
					);
					$sFieldSetOptions = json_encode($aFieldSetOptions);
					$aFormHandlerOptions = array(
						'wizard_helper_var_name' => 'oWizardHelper'.$sFormPrefix,
						'custom_field_attcode'   => $sAttCode,
					);
					$sFormHandlerOptions = json_encode($aFormHandlerOptions);
					$oPage->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/form_handler.js');
					$oPage->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/console_form_handler.js');
					$oPage->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/field_set.js');
					$oPage->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/form_field.js');
					$oPage->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/subform_field.js');
					$oPage->add_ready_script(
						<<<JS
$('#{$iId}_field_set').field_set($sFieldSetOptions);

$('#{$iId}_console_form').console_form_handler($sFormHandlerOptions);
$('#{$iId}_console_form').console_form_handler('alignColumns');
$('#{$iId}_console_form').console_form_handler('option', 'field_set', $('#{$iId}_field_set'));
// field_change must be processed to refresh the hidden value at anytime
$('#{$iId}_console_form').on('value_change', function() { $('#{$iId}').val(JSON.stringify($('#{$iId}_field_set').triggerHandler('get_current_values'))); });
// Initialize the hidden value with current state
// update_value is triggered when preparing the wizard helper object for ajax calls
$('#{$iId}').on('update_value', function() { $(this).val(JSON.stringify($('#{$iId}_field_set').triggerHandler('get_current_values'))); });
// validate is triggered by CheckFields, on all the input fields, once at page init and once before submitting the form
$('#{$iId}').on('validate', function(evt, sFormId) {
    $(this).val(JSON.stringify($('#{$iId}_field_set').triggerHandler('get_current_values')));
    return ValidateCustomFields('$iId', sFormId); // Custom validation function
});
JS
					);
					break;

				case 'Set':
				case 'TagSet':
					$sInputType = self::ENUM_INPUT_TYPE_TAGSET;
					$oPage->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/selectize.min.js');
					$oPage->add_linked_stylesheet(utils::GetAbsoluteUrlAppRoot().'css/selectize.default.css');
					$oPage->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/jquery.itop-set-widget.js');

					$oPage->add_dict_entry('Core:AttributeSet:placeholder');

					/** @var \ormSet $value */
					$sJson = $oAttDef->GetJsonForWidget($value, $aArgs);
					$sEscapedJson = utils::EscapeHtml($sJson);
					$sSetInputName = "attr_{$sFormPrefix}{$sAttCode}";

					// handle form validation
					$aEventsList[] = 'change';
					$aEventsList[] = 'validate';
					$sNullValue = '';
					$sFieldToValidateId = $sFieldToValidateId.AttributeSet::EDITABLE_INPUT_ID_SUFFIX;

					// generate form HTML output
					$sValidationSpan = "<span class=\"form_validation ibo-field-validation\" id=\"v_{$sFieldToValidateId}\"></span>";
					$sHTMLValue = '<div class="field_input_zone field_input_set ibo-input-wrapper ibo-input-tagset-wrapper" data-validation="untouched"><input id="'.$iId.'" name="'.$sSetInputName.'" type="hidden" value="'.$sEscapedJson.'"></div>'.$sValidationSpan.$sReloadSpan;
					$sScript = "$('#$iId').set_widget({inputWidgetIdSuffix: '".AttributeSet::EDITABLE_INPUT_ID_SUFFIX."'});";
					$oPage->add_ready_script($sScript);

					break;

				case 'String':
				default:
					$aEventsList[] = 'validate';
					// #@# todo - add context information (depending on dimensions)
					$aAllowedValues = $oAttDef->GetAllowedValues($aArgs);
					$iFieldSize = $oAttDef->GetMaxSize();
					if ($aAllowedValues !== null) {
						// Discrete list of values, use a SELECT or RADIO buttons depending on the config
						$sDisplayStyle = $oAttDef->GetDisplayStyle();
						switch ($sDisplayStyle) {
							case 'radio':
							case 'radio_horizontal':
							case 'radio_vertical':
								$sInputType = self::ENUM_INPUT_TYPE_RADIO;
								$aEventsList[] = 'change';
								$sHTMLValue = "<div class=\"field_input_zone field_input_{$sDisplayStyle}\">";
								$bVertical = ($sDisplayStyle != 'radio_horizontal');
								$sHTMLValue .= $oPage->GetRadioButtons($aAllowedValues, $value, $iId,
									"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}", $bMandatory, $bVertical, '');
								$sHTMLValue .= "</div>{$sValidationSpan}{$sReloadSpan}\n";
								break;

							case 'select':
							default:
								$sInputType = self::ENUM_INPUT_TYPE_DROPDOWN_RAW;
								$aEventsList[] = 'change';
								$sHTMLValue = "<div class=\"field_input_zone field_input_string ibo-input-wrapper ibo-input-select-wrapper\" data-validation=\"untouched\"><select class=\"ibo-input ibo-input-select\" title=\"$sHelpText\" name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}\" id=\"$iId\">\n";
								$sHTMLValue .= "<option value=\"\">".Dict::S('UI:SelectOne')."</option>\n";
								foreach ($aAllowedValues as $key => $display_value) {
									if ((count($aAllowedValues) == 1) && ($bMandatory == 'true')) {
										// When there is only once choice, select it by default
										if ($value != $key) {
											$oPage->add_ready_script(
												<<<EOF
$('#$iId').attr('data-validate','dependencies');
EOF
											);
										}
										$sSelected = ' selected';
									} else {
										$sSelected = ($value == $key) ? ' selected' : '';
									}
									$sHTMLValue .= "<option value=\"$key\"$sSelected>$display_value</option>\n";
								}
								$sHTMLValue .= "</select></div>{$sValidationSpan}{$sReloadSpan}\n";
								break;
						}
					} else {
						$sInputType = self::ENUM_INPUT_TYPE_SINGLE_INPUT;
						$sDisplayValueForHtml = utils::EscapeHtml($sDisplayValue);

						// Adding tooltip so we can read the whole value when its very long (eg. URL)
						$sTip = '';
						if (!empty($sDisplayValue)) {
							$sTip = 'data-tooltip-content="'.$sDisplayValueForHtml.'"';
							$oPage->add_ready_script(<<<JS
								$('#{$iId}').on('keyup', function(evt, sFormId){ 
									let sVal = $('#{$iId}').val();
									const oTippy = this._tippy;
									
									if(sVal === '')
									{
										oTippy.hide();
										oTippy.disable(); 
									}
									else
									{
										oTippy.enable(); 
									}
									oTippy.setContent(sVal);
								});
JS
							);
						}


						$sHTMLValue = <<<HTML
<div class="field_input_zone ibo-input-wrapper ibo-input-string-wrapper" data-validation="untouched">
	<input class="ibo-input ibo-input-string" title="{$sHelpText}" type="text" maxlength="{$iFieldSize}" name="attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}" value="{$sDisplayValueForHtml}" id="{$iId}" {$sTip} />
</div>
{$sValidationSpan}{$sReloadSpan}
HTML;
						$aEventsList[] = 'keyup';
						$aEventsList[] = 'change';

					}
					break;
			}
			$sPattern = addslashes($oAttDef->GetValidationPattern()); //'^([0-9]+)$';
			if (!empty($aEventsList)) {
				if (!is_numeric($sNullValue)) {
					$sNullValue = "'$sNullValue'"; // Add quotes to turn this into a JS string if it's not a number
				}
				$sOriginalValue = ($iFlags & OPT_ATT_MUSTCHANGE) ? json_encode($value) : 'undefined';
				$sEventList = implode(' ', $aEventsList);
				$oPage->add_ready_script(<<<JS
$('#$sFieldToValidateId')
	.on('$sEventList',  
		function(evt, sFormId) {
			// Bind to a custom event: validate
			return ValidateField('$sFieldToValidateId', '$sPattern', $bMandatory, sFormId, $sNullValue, $sOriginalValue);
		} 
	); 
JS
				);
			}

			// handle dependent fields updates (init for WizardHelper JS object)
			if (count($aDependencies) > 0) {
				//--- Add an event handler to launch a custom event: validate
				// * Unbind first to avoid duplicate event handlers in case of reload of the whole (or part of the) form
				// * We were using off/on directly on the node before, but that was causing an issue when adding dynamically new nodes
				//   indeed the events weren't attached on the of the new nodes !
				//   So we're adding the handler on a node above, and we're using a selector to catch only the event we're interested in !
				$sDependencies = implode("','", $aDependencies);

				$oPage->add_ready_script(<<<JS
$('div#field_{$iId}')
	.off('change.dependencies', '#$iId') 
	.on('change.dependencies', '#$iId', 
		function(evt, sFormId) { 
			return $sWizardHelperJsVarName.UpdateDependentFields(['$sDependencies']); 
		} 
	);
JS
				);
			}
		}
		$oPage->add_dict_entry('UI:ValueMustBeSet');
		$oPage->add_dict_entry('UI:ValueMustBeChanged');
		$oPage->add_dict_entry('UI:ValueInvalidFormat');

		// N°3750 refresh container data-input-type attribute if in an Ajax context
		// indeed in such a case we're only returning the field value content and not the parent container, so we need to update it !
		if (utils::IsXmlHttpRequest()) {
			// We are refreshing the data attribute only with the .attr() method
			// So any consumer that want to get this attribute value MUST use `.attr()` and not `.data()`
			// Actually the later uses a dedicated memory (that is initialized by the DOM values on page loading)
			// Whereas `.attr()` uses the DOM directly
			$oPage->add_init_script('$("[data-input-id=\''.$iId.'\']").attr("data-input-type", "'.$sInputType.'");');
		}

		//TODO 3.0 remove the data-attcode attribute (either because it's has been moved to .field_container in 2.7 or even better because the admin. console has been reworked)
		return "<div id=\"field_{$iId}\" class=\"field_value_container\"><div class=\"attribute-edit\" data-attcode=\"$sAttCode\">{$sHTMLValue}</div></div>";
	}
}