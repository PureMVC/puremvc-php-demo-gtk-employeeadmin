<?php
/**
 * PureMVC PHP Employee Admin Demo by Sasa Tarbuk <sasa@mindframes.org>
 * PureMVC - Copyright(c) 2006-2010 Futurescale, Inc., Some rights reserved.
 * Your reuse is governed by the Creative Commons Attribution 3.0 Unported License
 */

class LayoutMediator extends Mediator
{
	const NAME = 'LayoutMediator';
	
	public $layout;
	private $userListMediator;
	private $userFormMediator;
	private $rolePanelMediator;
	
	public function __construct (LayoutComponent $layout)
	{
		parent::__construct (self::NAME, $layout);
		$this->layout = $layout;
	}
	
	public function onRegister ()
	{
		$this->userListMediator = new UserListMediator (new UserListComponent ());
		$this->userFormMediator = new UserFormMediator (new UserFormComponent ());
		$this->rolePanelMediator = new RolePanelMediator (new RolePanelComponent ());
		$this->userListMediator->setWindow ($this->layout->window);
		
		$this->facade->registerMediator ($this->userListMediator);
		$this->facade->registerMediator ($this->userFormMediator);
		$this->facade->registerMediator ($this->rolePanelMediator);
	}
	
	public function listNotificationInterests ()
	{
		return array ();
	}
	
	public function handleNotification (INotification $note)
	{
		return;
	}
	
	public function startView ()
	{
		$this->layout->init
		(
			$this->userListMediator->userList->frame,
			$this->userFormMediator->userForm->frame,
			$this->rolePanelMediator->rolePanel->frame
		);
	}
	
	public function alert ($title, $message)
	{
		$this->layout->alert ($title, $message);
	}
}