<?php
/**
 * PureMVC PHP Employee Admin Demo by Sasa Tarbuk <sasa@mindframes.org>
 * PureMVC - Copyright(c) 2006-2010 Futurescale, Inc., Some rights reserved.
 * Your reuse is governed by the Creative Commons Attribution 3.0 Unported License
 */

class UserListComponent
{
	const E_NEW = 'new';
	const E_DELETE = 'delete';
	const E_SELECT = 'select';
	
	public $window;
	public $frame;
	public $selectedUserRefID;
	private $events;
	
	private $statusLabel;
	private $newButton;
	private $delButton;
	
	private $userListTree;
	private $userListStore;
	
	public function __construct ()
	{
		$this->frame = new GtkFrame ();
		$frameBox = new GtkVBox (false, 10);
		$frameBox->set_border_width (10);
		$titleBox = new GtkHBox (false, 10);
		$titleLabel = new GtkLabel ("Users");
		$titleLabel->set_markup ("<span weight='bold'>Users</span>");
		$titleLabel->set_alignment (0, 0.5);
		$this->statusLabel = new GtkLabel ("");
		$this->statusLabel->set_alignment (1, 0.5);
		$titleBox->pack_start ($titleLabel, true, true, 0);
		$titleBox->pack_end ($this->statusLabel, true, true, 0);
		$frameBox->pack_start ($titleBox, false, false, 0);
		$this->frame->add ($frameBox);
		
		$this->userListStore = new GtkRefListStore
		(
			Gobject::TYPE_STRING, Gobject::TYPE_STRING, Gobject::TYPE_STRING,
			Gobject::TYPE_STRING, Gobject::TYPE_STRING
		);
		
		$this->userListTree = new GtkRefTreeView ($this->userListStore);
		$this->userListTree->set_rules_hint (true);

		$usersWindow = new GtkScrolledWindow ();
		$usersWindow->set_policy (Gtk::POLICY_AUTOMATIC, Gtk::POLICY_AUTOMATIC);
		$usersWindow->set_shadow_type (Gtk::SHADOW_ETCHED_IN);
		$usersWindow->add ($this->userListTree);
		$frameBox->pack_start ($usersWindow, true, true, 0);

		$cols = array ('Username', 'First Name', 'Last Name', 'Email', 'Department');
		foreach ($cols as $num => $item)
		{
			$renderer = new GtkCellRendererText ();
			$renderer->set_property ("editable", false);
			$column = new GtkTreeViewColumn ($item, $renderer, "text", $num);
			$column->set_sort_column_id ($num);
			$column->set_expand (true);
			$column->set_resizable (true);
			$this->userListTree->append_column ($column);
		}
		$userSelection = $this->userListTree->get_selection ();
		$userSelection->set_mode (Gtk::SELECTION_SINGLE);
		$userSelection->connect ('changed', array ($this, 'isSelected'));

		$this->newButton = new GtkButton ("New");
		$this->newButton->connect ('clicked', array ($this, 'deSelect'));
		$this->delButton = new GtkButton ("Delete");
		$this->delButton->set_sensitive (false);
		$this->delButton->connect ('clicked', array ($this, 'confirmDeletion'));

		$buttonBox = new GtkHBox (false, 8);
		$buttonBox->pack_end ($this->newButton, false, false, 0);
		$buttonBox->pack_end ($this->delButton, false, false, 0);
		$frameBox->pack_end ($buttonBox, false, false, 0);
	}
	
	public function addEventListener ($event, $obj, $methodName)
	{
		switch ($event)
		{
			case self::E_NEW:
				$this->newButton->connect ('clicked', array ($obj, $methodName));
				break;
			
			case self::E_DELETE:
				$this->events [self::E_DELETE] = array ($obj, $methodName);
				break;
			
			case self::E_SELECT:
				$this->userListTree->get_selection()->connect ('changed', array ($obj, $methodName));
				break;
		}
	}
	
	public function setUsers (ArrayObject $users)
	{
		foreach ($users as $refID => $user)
		{
			$tempStore [$refID] = $this->getUserStoreArray ($user);
		}
		$this->userListStore->refInsert (isset ($tempStore) ? $tempStore : array ());
		$this->statusLabel->set_label (count ($this->userListStore));
	}
	
	public function addUser ($refID, UserVO $user)
	{
		$this->userListStore->refAdd ($refID, $this->getUserStoreArray ($user));
		$this->statusLabel->set_label (count ($this->userListStore));
	}
	
	public function updateUser ($refID, UserVO $user)
	{
		$this->userListStore->refUpdate ($refID, $this->getUserStoreArray ($user));
	}
	
	public function deleteUser ($refID)
	{
		$this->userListStore->refRemove ($refID);
		$this->statusLabel->set_label (count ($this->userListStore));
	}
	
	private function getUserStoreArray (UserVO $user)
	{
		return array
		(
			$user->username,
			$user->fname,
			$user->lname,
			$user->email,
			DeptEnum::getValue ($user->department)
		);
	}
	
	public function confirmDeletion ()
	{
		$dialog = new GtkMessageDialog ($this->window, 0, Gtk::MESSAGE_QUESTION, Gtk::BUTTONS_YES_NO, 'Are you sure?');
		$dialog->set_title ('Confirm Deletion');
		$dialog->run () === Gtk::RESPONSE_YES && call_user_func_array ($this->events [self::E_DELETE], array ());
		$dialog->destroy ();
	}
	
	public function isSelected ($selection)
	{
		if ($selection->count_selected_rows () !== 1)
		{
			$selection->stop_emission ('changed');
			$this->delButton->set_sensitive (false);
		}
		else
		{
			$this->selectedUserRefID = $this->userListTree->getSingleActiveRefID ();
			$this->delButton->set_sensitive (true);
		}
	}
	
	public function deSelect ()
	{
		$this->userListTree->get_selection()->unselect_all ();
		$this->delButton->set_sensitive (false);
	}
}