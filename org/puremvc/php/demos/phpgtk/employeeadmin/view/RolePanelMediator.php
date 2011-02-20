<?php
/**
 * PureMVC PHP Employee Admin Demo by Sasa Tarbuk <sasa@mindframes.org>
 * PureMVC - Copyright(c) 2006-2010 Futurescale, Inc., Some rights reserved.
 * Your reuse is governed by the Creative Commons Attribution 3.0 Unported License
 */

class RolePanelMediator extends Mediator
{
	const NAME = 'RolePanelMediator';
	
	public $rolePanel;
	private $roleProxy;
	
	public function __construct (RolePanelComponent $rolePanel)
	{
		parent::__construct (self::NAME, $rolePanel);
		$this->rolePanel = $rolePanel;
	}
	
	public function onRegister ()
	{
		$this->rolePanel->addEventListener (RolePanelComponent::E_ADD, $this, 'onAddRole');
		$this->rolePanel->addEventListener (RolePanelComponent::E_REMOVE, $this, 'onRemoveRole');
		$this->roleProxy = $this->facade->retrieveProxy (RoleProxy::NAME);
	}
	
	public function listNotificationInterests ()
	{
		return array
		(
			AppFacade::NEW_USER,
			AppFacade::USER_ADDED,
			AppFacade::USER_UPDATED,
			AppFacade::USER_DELETED,
			AppFacade::CANCEL_SELECTED,
			AppFacade::USER_SELECTED,
			AppFacade::ADD_ROLE_RESULT
		);
	}
	
	public function handleNotification (INotification $note)
	{
		switch ($note->getName ())
		{
			case AppFacade::NEW_USER:
				$this->clearForm ();
				break;
				
			case AppFacade::USER_ADDED:
				$this->rolePanel->user = $note->getBody ();
				$this->roleProxy->addItem (new RoleVO ($this->rolePanel->user->username));
				$this->clearForm ();
				break;
				
			case AppFacade::USER_UPDATED:
				$this->clearForm ();
				break;
				
			case AppFacade::USER_DELETED:
				$this->clearForm ();
				break;
				
			case AppFacade::CANCEL_SELECTED:
				$this->clearForm ();
				break;
				
			case AppFacade::USER_SELECTED:
				$this->rolePanel->user = $note->getBody ();
				$this->rolePanel->setUserRoles ($this->roleProxy->getUserRoles ($this->rolePanel->user->username));
				$this->rolePanel->reset ();
				break;
				
			case AppFacade::ADD_ROLE_RESULT:
				$note->getBody () && $this->rolePanel->addUserRole ($note->getType (), RoleEnum::getValue ($note->getType ()));
				$this->rolePanel->reset ();
				break;
		}
	}
	
	public function onAddRole ()
	{
		$this->roleProxy->addRoleToUser ($this->rolePanel->user, $this->rolePanel->selectedRole);
	}
	
	public function onRemoveRole ()
	{
		$this->roleProxy->removeRoleFromUser ($this->rolePanel->user, $this->rolePanel->selectedRole);
		$this->rolePanel->removeActiveRole ();
	}
	
	private function clearForm ()
	{
		$this->rolePanel->user = null;
		$this->rolePanel->setUserRoles (array ());
		$this->rolePanel->reset ();
	}
}