<?php
/**
 * PureMVC PHP Employee Admin Demo by Sasa Tarbuk <sasa@mindframes.org>
 * PureMVC - Copyright(c) 2006-2010 Futurescale, Inc., Some rights reserved.
 * Your reuse is governed by the Creative Commons Attribution 3.0 Unported License
 */

class DeptEnum
{
	const ACCT = 0;
	const SALES = 1;
	const PLANT = 2;
	const SHIPPING = 3;
	const QC = 4;
	
	private static $list = array
	(
		DeptEnum::ACCT => 'Accounting',
		DeptEnum::SALES => 'Sales',
		DeptEnum::PLANT => 'Plant',
		DeptEnum::SHIPPING => 'Shipping',
		DeptEnum::QC => 'Quality Control'
	);
	
	public static function getList ()
	{
		return array
		(
			DeptEnum::ACCT => self::$list [DeptEnum::ACCT],
			DeptEnum::SALES => self::$list [DeptEnum::SALES],
			DeptEnum::PLANT => self::$list [DeptEnum::PLANT]
		);
	}
	
	public static function getValue ($enum)
	{
		return self::$list [$enum];
	}
}