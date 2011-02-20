<?php
/**
 * PureMVC PHP Employee Admin Demo by Sasa Tarbuk <sasa@mindframes.org>
 * PureMVC - Copyright(c) 2006-2010 Futurescale, Inc., Some rights reserved.
 * Your reuse is governed by the Creative Commons Attribution 3.0 Unported License
 */

class AppFacade extends Facade
{
	const STARTUP = 'startup';
	const NEW_USER = 'newUser';
	const DELETE_USER = 'deleteUser';
	const CANCEL_SELECTED = 'cancelSelected';
	const POPULATE_USERS = 'populateUsers';
	const USER_SELECTED = 'userSelected';
	const USER_ADDED = 'userAdded';
	const USER_UPDATED = 'userUpdated';
	const USER_DELETED = 'userDeleted';
	const ADD_ROLE = 'addRole';
	const ADD_ROLE_RESULT = 'addRoleResult';

	public static function getInstance ()
	{
		if (parent::$instance === null)
		{
			parent::$instance = new AppFacade ();
		}
		return parent::$instance;
	}
	
	public function startup ($params = null)
	{
		$this->sendNotification (self::STARTUP, $params);
	}
	
	protected function initializeController ()
	{
		parent::initializeController ();
		$this->registerCommand (AppFacade::STARTUP, 'StartupCommand');
		$this->registerCommand (AppFacade::DELETE_USER, 'DeleteUserCommand');
		$this->registerCommand (AppFacade::ADD_ROLE_RESULT, 'AddRoleResultCommand');
	}
}