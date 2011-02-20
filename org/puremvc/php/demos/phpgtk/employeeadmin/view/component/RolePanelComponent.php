<?php
/**
 * PureMVC PHP Employee Admin Demo by Sasa Tarbuk <sasa@mindframes.org>
 * PureMVC - Copyright(c) 2006-2010 Futurescale, Inc., Some rights reserved.
 * Your reuse is governed by the Creative Commons Attribution 3.0 Unported License
 */

class RolePanelComponent
{
	const E_ADD = 'add';
	const E_REMOVE = 'remove';
	
	public $frame;
	public $user;
	public $selectedRole;

	private $roleListTree;
	private $roleListStore;
	
	private $roleCombo;
	private $roleComboStore;
	
	private $addButton;
	private $removeButton;
	private $statusLabel;
	
	public function __construct ()
	{
		$this->frame = new GtkFrame ();
		$frameBox = new GtkVBox (false, 10);
		$frameBox->set_border_width (10);
		$titleBox = new GtkHBox (false, 10);
		$titleLabel = new GtkLabel ("User Roles");
		$titleLabel->set_markup ("<span weight='bold'>User Roles</span>");
		$titleLabel->set_alignment (0, 0.5);
		$this->statusLabel = new GtkLabel ("");
		$this->statusLabel->set_alignment (1, 0.5);
		$titleBox->pack_start ($titleLabel, true, true, 0);
		$titleBox->pack_end ($this->statusLabel, true, true, 0);
		$frameBox->pack_start ($titleBox, false, false, 0);
		$this->frame->add ($frameBox);
		
		$this->roleListStore = new GtkRefListStore (Gobject::TYPE_STRING);
		$this->roleListTree = new GtkRefTreeView ($this->roleListStore);
		$this->roleListTree->set_headers_visible (false);
		$roleListSelection = $this->roleListTree->get_selection ();
		$roleListSelection->set_mode (Gtk::SELECTION_SINGLE);
		$roleListSelection->connect ('changed', array ($this, 'selectRoleToRemove'));
		
		$rolesWindow = new GtkScrolledWindow ();
		$rolesWindow->set_policy (Gtk::POLICY_AUTOMATIC, Gtk::POLICY_AUTOMATIC);
		$rolesWindow->set_shadow_type (Gtk::SHADOW_ETCHED_IN);
		$rolesWindow->add ($this->roleListTree);
		$frameBox->pack_start ($rolesWindow, true, true, 0);

		$renderer = new GtkCellRendererText ();
		$renderer->set_property ("editable", false);
		$column = new GtkTreeViewColumn ('Role', $renderer, "text", 0);
		$column->set_expand (true);
		$column->set_resizable (false);
		$this->roleListTree->append_column ($column);
		
		$this->roleComboStore = new GtkRefListStore (Gobject::TYPE_STRING);
		$this->roleComboStore->refInsert (RoleEnum::getList());
		$this->roleCombo = new GtkRefComboBox ($this->roleComboStore);
		$roleCell = new GtkCellRendererText ();
		$roleCell->set_property ('ellipsize', Pango::ELLIPSIZE_END);
		$this->roleCombo->pack_start ($roleCell);
		$this->roleCombo->set_attributes ($roleCell, 'text', 0);
		$this->roleCombo->connect ('changed', array ($this, 'selectRoleToAdd'));

		$this->addButton = new GtkButton ("Add");
		$this->addButton->set_sensitive (false);
		$this->removeButton = new GtkButton ("Remove");
		$this->removeButton->set_sensitive (false);
		$this->enableForm (false);

		$buttonBox = new GtkHBox (false, 8);
		$buttonBox->pack_end ($this->removeButton, false, false, 0);
		$buttonBox->pack_end ($this->addButton, false, false, 0);
		$buttonBox->pack_end ($this->roleCombo, true, true, 0);
		$frameBox->pack_end ($buttonBox, false, false, 0);
	}
	
	public function addEventListener ($event, $obj, $methodName)
	{
		switch ($event)
		{
			case self::E_ADD:
				$this->addButton->connect ('clicked', array ($obj, $methodName));
				break;
			
			case self::E_REMOVE:
				$this->removeButton->connect ('clicked', array ($obj, $methodName));
				break;
		}
	}
	
	public function setUserRoles (array $roles)
	{
		$this->roleListStore->refClear ();
		
		if (count ($roles) > 0)
		{
			foreach ($roles as $roleEnum)
			{
				$userRoles [$roleEnum] = RoleEnum::getValue ($roleEnum);
			}
			$this->roleListStore->refInsert (isset ($userRoles) ? $userRoles : array ());
		}
	}
	
	public function addUserRole ($enum, $value)
	{
		$this->roleListStore->refAdd ($enum, $value);
	}
	
	public function removeActiveRole ()
	{
		$activeRole = $this->roleListTree->getSingleActiveRefID ();
		$this->roleListStore->refRemove ($activeRole);
	}
	
	public function selectRoleToRemove ()
	{
		if ($this->roleListTree->get_selection()->count_selected_rows () > 0)
		{
			$this->selectedRole = $this->roleListTree->getSingleActiveRefID ();
			$this->roleCombo->set_active (-1);
			$this->removeButton->set_sensitive (true);
		}
		else
		{
			$this->removeButton->set_sensitive (false);
		}
	}
	
	public function selectRoleToAdd ()
	{
		if ($this->roleCombo->get_active () > -1)
		{
			$this->selectedRole = $this->roleCombo->getActiveRefID ();
			$this->roleListTree->get_selection()->unselect_all ();
			$this->addButton->set_sensitive (true);
		}
		else
		{
			$this->addButton->set_sensitive (false);
		}
	}
	
	public function enableForm ($is)
	{
		$this->roleCombo->set_sensitive ($is);
		$this->roleListTree->set_sensitive ($is);
	}
	
	public function reset ()
	{
		$this->roleCombo->set_active (-1);
		$this->addButton->set_sensitive (false);
		$this->removeButton->set_sensitive (false);
		$this->enableForm ($this->user ? true : false);
		$this->statusLabel->set_label ($this->user ? $this->user->getGivenName () : '');
	}
}