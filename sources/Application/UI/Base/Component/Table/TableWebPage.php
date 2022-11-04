<?php

namespace Combodo\iTop\Application\UI\Base\Component\Table;

class TableWebPage extends \WebPage
{

	public function __construct(string $s_title, bool $bPrintable = false)
	{
		parent::__construct($s_title, $bPrintable);
	}

	public function GetReadyScriptsString()
	{
		$test = implode('', $this->GetReadyScripts());

		$this->EmptyReadyScripts();

		return $test;
	}
}