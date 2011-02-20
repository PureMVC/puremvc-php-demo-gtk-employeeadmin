<?php
/**
 * PureMVC PHP Employee Admin Demo by Sasa Tarbuk <sasa@mindframes.org>
 * PureMVC - Copyright(c) 2006-2010 Futurescale, Inc., Some rights reserved.
 * Your reuse is governed by the Creative Commons Attribution 3.0 Unported License
 */

class DeleteUserCommand extends SimpleCommand
{
	public function execute (INotification $note)
	{
		$user = $note->getBody ();
		$userProxy = $this->facade->retrieveProxy (UserProxy::NAME);
		$roleProxy = $this->facade->retrieveProxy (RoleProxy::NAME);
		$userProxy->deleteItem ($user);
		$roleProxy->deleteItem ($user);
		$this->sendNotification (AppFacade::USER_DELETED);
	}
}