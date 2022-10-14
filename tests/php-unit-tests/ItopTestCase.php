<?php
/**
 * Copyright (C) 2013-2019 Combodo SARL
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

namespace Combodo\iTop\Test\UnitTest;
/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 20/11/2017
 * Time: 11:21
 */

use CMDBSource;
use MySQLTransactionNotClosedException;
use PHPUnit\Framework\TestCase;
use SetupUtils;

define('DEBUG_UNIT_TEST', true);

class ItopTestCase extends TestCase
{
	const TEST_LOG_DIR = 'test';

	/** @noinspection UsingInclusionOnceReturnValueInspection avoid errors for approot includes */
	protected function setUp(): void
	{
		@include_once '../approot.inc.php';
		@include_once '../../approot.inc.php';
		@include_once '../../../approot.inc.php';
		@include_once '../../../../approot.inc.php';
		@include_once '../../../../../approot.inc.php';
		@include_once '../../../../../../approot.inc.php';
		@include_once '../../../../../../../approot.inc.php';
		@include_once '../../../../../../../../approot.inc.php';
	}

	/**
	 * @throws \MySQLTransactionNotClosedException see N°5538
	 * @since 2.7.8 3.0.3 3.1.0 N°5538
	 */
	protected function tearDown(): void
	{
		parent::tearDown();

		if (CMDBSource::IsInsideTransaction()) {
			// Nested transactions were opened but not finished !
			throw new MySQLTransactionNotClosedException('Some DB transactions were opened but not closed ! Fix the code by adding ROLLBACK or COMMIT statements !', []);
		}
	}

	protected function debug($sMsg)
	{
		if (DEBUG_UNIT_TEST) {
			if (is_string($sMsg)) {
				echo "$sMsg\n";
			} else {
		        /** @noinspection ForgottenDebugOutputInspection */
		        print_r($sMsg);
	        }
        }
    }

	public function GetMicroTime()
	{
		list($uSec, $sec) = explode(" ", microtime());
		return ((float)$uSec + (float)$sec);
	}

	public function WriteToCsvHeader($sFilename, $aHeader)
	{
		$sResultFile = APPROOT.'log/'.$sFilename;
		if (is_file($sResultFile))
		{
			@unlink($sResultFile);
		}
		SetupUtils::builddir(dirname($sResultFile));
		file_put_contents($sResultFile, implode(';', $aHeader)."\n");
	}

	public function WriteToCsvData($sFilename, $aData)
	{
		$sResultFile = APPROOT.'log/'.$sFilename;
		$file = fopen($sResultFile, 'a');
		fputs($file, implode(';', $aData)."\n");
		fclose($file);
	}

	public function GetTestId()
	{
		$sId = str_replace('"', '', $this->getName());
		$sId = str_replace(' ', '_', $sId);

		return $sId;
	}

	/**
	 * @since 2.7.4 3.0.0
	 */
	public function InvokeNonPublicStaticMethod($sObjectClass, $sMethodName, $aArgs)
	{
		return $this->InvokeNonPublicMethod($sObjectClass, $sMethodName, null, $aArgs);
	}

	/**
	 * @param string $sObjectClass for example DBObject::class
	 * @param string $sMethodName
	 * @param object $oObject
	 * @param array $aArgs
	 *
	 * @return mixed method result
	 *
	 * @throws \ReflectionException
	 *
	 * @since 2.7.4 3.0.0
	 */
	public function InvokeNonPublicMethod($sObjectClass, $sMethodName, $oObject, $aArgs)
	{
		$class = new \ReflectionClass($sObjectClass);
		$method = $class->getMethod($sMethodName);
		$method->setAccessible(true);

		return $method->invokeArgs($oObject, $aArgs);
	}
}