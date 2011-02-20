<?php
/**
 * PureMVC PHP Employee Admin Demo by Sasa Tarbuk <sasa@mindframes.org>
 * PureMVC - Copyright(c) 2006-2010 Futurescale, Inc., Some rights reserved.
 * Your reuse is governed by the Creative Commons Attribution 3.0 Unported License
 */

class UserProxy extends Proxy
{
	const NAME = 'UserProxy';
	public $users;
	
	public function __construct ()
	{
		parent::__construct (self::NAME, new ArrayObject ());
		$this->users = &$this->data;
	}
	
	public function getIndex ($user)
	{
		return array_search ($user, $this->users->getArrayCopy (), true);
	}
	
	public function addItem ($user)
	{
		$this->users->offsetSet (null, $user);
	}
	
	public function updateItem ($user)
	{
		foreach ($this->users as $u => $userVO)
		{
			if ($userVO->username === $user->username)
			{
				$this->users->offsetSet ($u, $user);
				return;
			}
		}
	}
	
	public function deleteItem ($user)
	{
		foreach ($this->users as $u => $userVO)
		{
			if ($userVO->username === $user->username)
			{
				$this->users->offsetUnset ($u);
				return;
			}
		}
	}
}