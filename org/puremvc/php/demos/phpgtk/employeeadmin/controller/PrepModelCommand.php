<?php
/**
 * PureMVC PHP Employee Admin Demo by Sasa Tarbuk <sasa@mindframes.org>
 * PureMVC - Copyright(c) 2006-2010 Futurescale, Inc., Some rights reserved.
 * Your reuse is governed by the Creative Commons Attribution 3.0 Unported License
 */

class PrepModelCommand extends SimpleCommand
{
	public function execute (INotification $note)
	{
		$userProxy = new UserProxy ();
		$roleProxy = new RoleProxy ();
		
		$userProxy->addItem
		(
			new UserVO
			(
				"lstooge",
				"Larry",
				"Stooge",
				"larry@stooges.com",
				"ijk456",
				DeptEnum::ACCT
			)
		);
		$userProxy->addItem
		(
			new UserVO
			(
				"cstooge",
				"Curly",
				"Stooge",
				"curly@stooges.com",
				"xyz987",
				DeptEnum::SALES
			)
		);
		$userProxy->addItem
		(
			new UserVO
			(
				"mstooge",
				"Moe",
				"Stooge",
				"moe@stooges.com",
				"abc123",
				DeptEnum::PLANT
			)
		);
		
		$roleProxy->addItem
		(
			new RoleVO
			(
				"lstooge",
				array
				(
					RoleEnum::PAYROLL,
					RoleEnum::EMP_BENEFITS
				)
			)
		);
		
		$roleProxy->addItem
		(
			new RoleVO
			(
				"cstooge",
				array
				(
					RoleEnum::ACCT_PAY,
					RoleEnum::ACCT_RCV,
					RoleEnum::GEN_LEDGER
				)
			)
		);
		
		$roleProxy->addItem
		(
			new RoleVO
			(
				"mstooge",
				array
				(
					RoleEnum::INVENTORY,
					RoleEnum::PRODUCTION,
					RoleEnum::SALES,
					RoleEnum::SHIPPING
				)
			)
		);
		
		$this->facade->registerProxy ($userProxy);
		$this->facade->registerProxy ($roleProxy);
	}
}