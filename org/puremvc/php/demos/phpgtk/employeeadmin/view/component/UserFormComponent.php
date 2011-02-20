<?php
/**
 * PureMVC PHP Employee Admin Demo by Sasa Tarbuk <sasa@mindframes.org>
 * PureMVC - Copyright(c) 2006-2010 Futurescale, Inc., Some rights reserved.
 * Your reuse is governed by the Creative Commons Attribution 3.0 Unported License
 */

class UserFormComponent
{
	const E_ADD = 'add';
	const E_UPDATE = 'update';
	const E_CANCEL = 'cancel';
	
	const MODE_ADD = 'modeAdd';
	const MODE_EDIT = 'modeEdit';
	
	public $frame;
	public $user;
	public $mode = UserFormComponent::MODE_EDIT;
	private $events = array ();
	
	private $entryFname;
	private $entryLname;
	private $entryEmail;
	private $entryUsername;
	private $entryPassword;
	private $entryConfirm;
	private $comboDept;
	private $submitButton;
	private $cancelButton;
	private $statusLabel;
	
	private $deptComboStore;
	private $deptComboRefs = array ();
	
	private $userFormTable;
	
	public function __construct ()
	{
		$this->frame = new GtkFrame ();
		$frameBox = new GtkVBox (false, 10);
		$frameBox->set_border_width (10);
		$titleBox = new GtkHBox (false, 10);
		$titleLabel = new GtkLabel ("User Profile");
		$titleLabel->set_markup ("<span weight='bold'>User Profile</span>");
		$titleLabel->set_alignment (0, 0.5);
		$this->statusLabel = new GtkLabel ("");
		$this->statusLabel->set_alignment (1, 0.5);
		$titleBox->pack_start ($titleLabel, true, true, 0);
		$titleBox->pack_end ($this->statusLabel, true, true, 0);
		$frameBox->pack_start ($titleBox, false, false, 0);
		$this->frame->add ($frameBox);
		$this->userFormTable = new GtkTable (3, 7);
		
		list ($labelFname, $this->entryFname) = $this->createLabelText ('First Name', true);
		list ($labelLname, $this->entryLname) = $this->createLabelText ('Last Name', true);
		list ($labelEmail, $this->entryEmail) = $this->createLabelText ('Email', true);
		list ($labelUsername, $this->entryUsername) = $this->createLabelText ('Username', true);
		list ($labelPassword, $this->entryPassword) = $this->createLabelText ('Password', false);
		list ($labelConfirm, $this->entryConfirm) = $this->createLabelText ('Confirm Password', false);
		
		$this->deptComboStore = new GtkRefListStore (Gobject::TYPE_STRING);
		$this->deptComboStore->refInsert (DeptEnum::getList ());
		$this->comboDept = new GtkRefComboBox ($this->deptComboStore);
		
		$cellDept = new GtkCellRendererText ();
		$cellDept->set_property ('ellipsize', Pango::ELLIPSIZE_END);
		$this->comboDept->pack_start ($cellDept);
		$this->comboDept->set_attributes ($cellDept, 'text', 0);
		$labelDept = new GtkLabel ('Department');
		$labelDept->set_alignment (1, 0.5);
		
		$this->attachLabelText ($labelFname, $this->entryFname, false, 0);
		$this->attachLabelText ($labelLname, $this->entryLname, false, 1);
		$this->attachLabelText ($labelEmail, $this->entryEmail, false, 2);
		$this->attachLabelText ($labelUsername, $this->entryUsername, true, 3);
		$this->attachLabelText ($labelPassword, $this->entryPassword, true, 4);
		$this->attachLabelText ($labelConfirm, $this->entryConfirm, true, 5);
		$this->attachLabelText ($labelDept, $this->comboDept, true, 6);
		
		$this->entryUsername->connect ('key-release-event', array ($this, 'enableSubmit'));
		$this->entryPassword->connect ('key-release-event', array ($this, 'enableSubmit'));
		$this->entryConfirm->connect ('key-release-event', array ($this, 'enableSubmit'));
		$this->comboDept->connect ('changed', array ($this, 'enableSubmit'));

		$subFrame = new GtkFrame ();
		$subFrameBox = new GtkVBox (false, 0);
		$subFrameBox->set_border_width (12);
		$subFrame->add ($subFrameBox);
		$subFrameBox->pack_start ($this->userFormTable, false, false, 0);
		$frameBox->pack_start ($subFrame, false, false, 0);

		$buttonBox = new GtkHBox (false, 8);
		$this->submitButton = new GtkButton ($this->mode == self::MODE_ADD ? 'Add User' : 'Update Profile');
		$this->submitButton->connect ('clicked', array ($this, 'submit'));
		$this->cancelButton = new GtkButton ("Cancel");
		$buttonBox->pack_end ($this->cancelButton, false, false, 0);
		$buttonBox->pack_end ($this->submitButton, false, false, 0);
		$frameBox->pack_end ($buttonBox, true, true, 0);
		$this->enableForm (false);
	}
	
	private function createLabelText ($label, $entryVisible = true)
	{
		$labelObj = new GtkLabel ($label);
		$labelObj->set_alignment (1, 0.5);
		$entryObj = new GtkEntry ();
		$entryObj->set_visibility ($entryVisible);
		return array ($labelObj, $entryObj);
	}
	
	private function attachLabelText (GtkLabel $labelObj, GtkWidget $widgetObj, $isMandatory, $rowNum)
	{
		$this->userFormTable->attach ($labelObj, 0, 1, $rowNum, $rowNum + 1, Gtk::FILL, Gtk::FILL, 2, 2);
		$isMandatory && $this->userFormTable->attach ($this->getAstLabel (), 1, 2, $rowNum, $rowNum + 1, Gtk::SHRINK, Gtk::SHRINK, 1, 1);
		$this->userFormTable->attach ($widgetObj, 2, 3, $rowNum, $rowNum + 1, Gtk::FILL|Gtk::EXPAND, Gtk::FILL|Gtk::EXPAND, 2, 2);
	}
	
	private function getAstLabel ()
	{
		$mandatory = new GtkLabel ();
		$mandatory->set_markup('<span color="red">*</span>');
		$mandatory->set_width_chars (1);
		return $mandatory;
	}
	
	public function addEventListener ($event, $obj, $methodName)
	{
		switch ($event)
		{
			case self::E_ADD:
			case self::E_UPDATE:
				$this->events [$event] = array ($obj, $methodName);
				break;
			
			case self::E_CANCEL:
				$this->cancelButton->connect ('clicked', array ($obj, $methodName));
				break;
		}
	}
	
	public function submit ()
	{
		$this->user = new UserVO
		(
			$this->entryUsername->get_text (),
			$this->entryFname->get_text (),
			$this->entryLname->get_text (),
			$this->entryEmail->get_text (),
			$this->entryPassword->get_text (),
			$this->comboDept->getActiveRefID ()
		);
		
		if ($this->user->isValid ())
		{
			call_user_func_array
			(
				$this->events [$this->mode == self::MODE_ADD ? self::E_ADD : self::E_UPDATE],
				array ()
			);
		}
	}
	
	public function setUser (UserVO $user, $mode)
	{
		$this->enableForm (true);
		$this->statusLabel->set_label ($user->username);
		$this->entryFname->set_text ($user->fname);
		$this->entryLname->set_text ($user->lname);
		$this->entryEmail->set_text ($user->email);
		$this->entryUsername->set_text ($user->username);
		$this->entryUsername->set_sensitive ($mode == self::MODE_ADD ? true : false);
		$this->entryPassword->set_text ($user->password);
		$this->entryConfirm->set_text ($user->password);
		$this->comboDept->setActiveRefID ($user->department);
		$this->enableSubmit ();
		
		$this->user = $user;
		$this->mode = $mode;
		$this->submitButton->set_label ($mode == self::MODE_ADD ? 'Add User' : 'Update Profile');
	}
	
	public function reset ()
	{
		$this->statusLabel->set_label ('');
		$this->entryFname->set_text ('');
		$this->entryLname->set_text ('');
		$this->entryEmail->set_text ('');
		$this->entryUsername->set_text ('');
		$this->entryPassword->set_text ('');
		$this->entryConfirm->set_text ('');
		$this->comboDept->set_active (-1);
		$this->enableForm (false);
	}
	
	public function enableForm ($is)
	{
		$this->entryFname->set_sensitive ($is);
		$this->entryLname->set_sensitive ($is);
		$this->entryEmail->set_sensitive ($is);
		$this->entryUsername->set_sensitive ($is);
		$this->entryPassword->set_sensitive ($is);
		$this->entryConfirm->set_sensitive ($is);
		$this->comboDept->set_sensitive ($is);
		$this->submitButton->set_sensitive ($is);
		$this->cancelButton->set_sensitive ($is);
	}
	
	public function enableSubmit ()
	{
		if
		(
			$this->entryUsername->get_text () != "" &&
			$this->entryPassword->get_text () != "" &&
			$this->entryPassword->get_text () == $this->entryConfirm->get_text () &&
			$this->comboDept->get_active () > -1
		)
		{
			$this->submitButton->set_sensitive (true);
		}
		else
		{
			$this->submitButton->set_sensitive (false);
		}
	}
}