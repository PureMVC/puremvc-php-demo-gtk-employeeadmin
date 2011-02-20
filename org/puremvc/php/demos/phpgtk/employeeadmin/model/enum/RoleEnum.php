<?php
/**
 * PureMVC PHP Employee Admin Demo by Sasa Tarbuk <sasa@mindframes.org>
 * PureMVC - Copyright(c) 2006-2010 Futurescale, Inc., Some rights reserved.
 * Your reuse is governed by the Creative Commons Attribution 3.0 Unported License
 */

class RoleEnum
{
	const ADMIN = 0;
	const ACCT_PAY = 1;
	const ACCT_RCV = 2;
	const EMP_BENEFITS = 3;
	const GEN_LEDGER = 4;
	const PAYROLL = 5;
	const INVENTORY = 6;
	const PRODUCTION = 7;
	const QUALITY_CTL = 8;
	const SALES = 9;
	const ORDERS = 10;
	const CUSTOMERS = 11;
	const SHIPPING = 12;
	const RETURNS = 13;
	
	private static $list = array
	(
		RoleEnum::ADMIN => 'Administrator',
		RoleEnum::ACCT_PAY => 'Accounts Payable',
		RoleEnum::ACCT_RCV => 'Accounts Receivable',
		RoleEnum::EMP_BENEFITS => 'Employee Benefits',
		RoleEnum::GEN_LEDGER => 'General Ledger',
		RoleEnum::PAYROLL => 'Payroll',
		RoleEnum::INVENTORY => 'Inventory',
		RoleEnum::PRODUCTION => 'Production',
		RoleEnum::QUALITY_CTL => 'Quality Control',
		RoleEnum::SALES => 'Sales',
		RoleEnum::ORDERS => 'Orders',
		RoleEnum::CUSTOMERS => 'Customers',
		RoleEnum::SHIPPING => 'Shipping',
		RoleEnum::RETURNS => 'Returns'
	);
	
	public static function getList ()
	{
		return self::$list;
	}
	
	public static function getValue ($enum)
	{
		return self::$list [$enum];
	}
}