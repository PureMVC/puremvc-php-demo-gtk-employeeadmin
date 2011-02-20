<?php
/**
 * PureMVC PHP Employee Admin Demo by Sasa Tarbuk <sasa@mindframes.org>
 * PureMVC - Copyright(c) 2006-2010 Futurescale, Inc., Some rights reserved.
 * Your reuse is governed by the Creative Commons Attribution 3.0 Unported License
 */

class UserFormMediator extends Mediator
{
	const NAME = 'UserFormMediator';
	
	public $userForm;
	private $userProxy;
	
	public function __construct (UserFormComponent $userForm)
	{
		parent::__construct (self::NAME, $userForm);
		$this->userForm = $userForm;
	}
	
	public function onRegister ()
	{
		$this->userForm->addEventListener (UserFormComponent::E_ADD, $this, 'onAdd');
		$this->userForm->addEventListener (UserFormComponent::E_UPDATE, $this, 'onUpdate');
		$this->userForm->addEventListener (UserFormComponent::E_CANCEL, $this, 'onCancel');
		$this->userProxy = $this->facade->retrieveProxy (UserProxy::NAME);
	}
	
	public function listNotificationInterests ()
	{
		return array
		(
			AppFacade::NEW_USER,
			AppFacade::USER_DELETED,
			AppFacade::USER_SELECTED
		);
	}
	
	public function handleNotification (INotification $note)
	{
		switch ($note->getName ())
		{
			case AppFacade::NEW_USER:
				$this->userForm->setUser ($note->getBody (), UserFormComponent::MODE_ADD);
				break;
				
			case AppFacade::USER_DELETED:
				$this->userForm->reset ();
				break;
				
			case AppFacade::USER_SELECTED:
				$this->userForm->setUser ($note->getBody (), UserFormComponent::MODE_EDIT);
				break;
		}
	}
	
	public function onAdd ()
	{
		$this->userProxy->addItem ($this->userForm->user);
		$this->sendNotification (AppFacade::USER_ADDED, $this->userForm->user);
		$this->userForm->reset ();
	}
	
	public function onUpdate ()
	{
		$this->userProxy->updateItem ($this->userForm->user);
		$this->sendNotification (AppFacade::USER_UPDATED, $this->userForm->user);
		$this->userForm->reset ();
	}
	
	public function onCancel ()
	{
		$this->sendNotification (AppFacade::CANCEL_SELECTED);
		$this->userForm->reset ();
	}
}