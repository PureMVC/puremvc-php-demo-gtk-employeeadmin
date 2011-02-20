<?php
/**
 * PureMVC PHP Employee Admin Demo by Sasa Tarbuk <sasa@mindframes.org>
 * PureMVC - Copyright(c) 2006-2010 Futurescale, Inc., Some rights reserved.
 * Your reuse is governed by the Creative Commons Attribution 3.0 Unported License
 */

class UserListMediator extends Mediator
{
	const NAME = 'UserListMediator';
	
	public $userList;
	private $userProxy;
	
	public function __construct (UserListComponent $userList)
	{
		parent::__construct (self::NAME, $userList);
		$this->userList = $userList;
	}
	
	public function onRegister ()
	{
		$this->userList->addEventListener (UserListComponent::E_NEW, $this, 'onNew');
		$this->userList->addEventListener (UserListComponent::E_DELETE, $this, 'onDelete');
		$this->userList->addEventListener (UserListComponent::E_SELECT, $this, 'onSelect');
		$this->userProxy = $this->facade->retrieveProxy (UserProxy::NAME);
		$this->userList->setUsers ($this->userProxy->users);
	}
	
	public function listNotificationInterests ()
	{
		return array
		(
			AppFacade::CANCEL_SELECTED,
			AppFacade::USER_ADDED,
			AppFacade::USER_UPDATED
		);
	}
	
	public function handleNotification (INotification $note)
	{
		switch ($note->getName ())
		{
			case AppFacade::CANCEL_SELECTED:
				$this->userList->deSelect ();
				break;
			
			case AppFacade::USER_ADDED:
				$refID = $this->userProxy->getIndex ($note->getBody ());
				$this->userList->addUser ($refID, $note->getBody ());
				break;
			
			case AppFacade::USER_UPDATED:
				$refID = $this->userProxy->getIndex ($note->getBody ());
				$this->userList->updateUser ($refID, $note->getBody ());
				$this->userList->deSelect ();
				break;
		}
	}
	
	public function setWindow ($window)
	{
		$this->userList->window = $window;
	}
	
	public function onNew ()
	{
		$this->sendNotification (AppFacade::NEW_USER, new UserVO ());
	}
	
	public function onDelete ()
	{
		$this->userList->deleteUser ($this->userList->selectedUserRefID);
		$user = $this->userProxy->users [$this->userList->selectedUserRefID];
		$this->sendNotification (AppFacade::DELETE_USER, $user);
	}
	
	public function onSelect ()
	{
		$user = $this->userProxy->users [$this->userList->selectedUserRefID];
		$this->sendNotification (AppFacade::USER_SELECTED, $user);
	}
}