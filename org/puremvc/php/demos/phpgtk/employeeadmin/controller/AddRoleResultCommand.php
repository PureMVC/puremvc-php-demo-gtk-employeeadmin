<?php
/**
 * PureMVC PHP Employee Admin Demo by Sasa Tarbuk <sasa@mindframes.org>
 * PureMVC - Copyright(c) 2006-2010 Futurescale, Inc., Some rights reserved.
 * Your reuse is governed by the Creative Commons Attribution 3.0 Unported License
 */

class AddRoleResultCommand extends SimpleCommand
{
	public function execute (INotification $note)
	{
		if (!$note->getBody ())
		{
			$layoutMediator = $this->facade->retrieveMediator (LayoutMediator::NAME);
			$layoutMediator->alert ('Add User Role', 'Role already exists for this user!');
		}
	}
}