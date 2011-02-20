<?php
/**
 * PureMVC PHP Employee Admin Demo by Sasa Tarbuk <sasa@mindframes.org>
 * PureMVC - Copyright(c) 2006-2010 Futurescale, Inc., Some rights reserved.
 * Your reuse is governed by the Creative Commons Attribution 3.0 Unported License
 */

class LayoutComponent
{
	public $window;
	
	public function __construct ()
	{
		$this->window = new GtkWindow (Gtk::WINDOW_TOPLEVEL);
		$this->window->set_border_width (10);
		$this->window->set_title ("Employee Admin: PureMVC PHP Demo");
		$this->window->set_position (Gtk::WIN_POS_CENTER);
		$this->window->set_default_size (750, 650);
		$this->window->connect_simple ('destroy', array ('Gtk', 'main_quit'));
		$this->window->show_all ();
	}
	
	public function init (GtkFrame $userList, GtkFrame $userForm, GtkFrame $rolePanel)
	{
		$mainVBox = new GtkVBox (false, 10);
		$mainHBox = new GtkHBox (true, 10);

		$mainVBox->pack_start ($userList, true, true, 0);
		$mainHBox->pack_start ($userForm, true, true, 0);
		$mainHBox->pack_start ($rolePanel, true, true, 0);
		$mainVBox->pack_start ($mainHBox, false, false, 0);

		$this->window->add ($mainVBox);
		$this->window->show_all ();
		Gtk::main ();
	}
	
	public function alert ($title, $message)
	{
		$dialog = new GtkMessageDialog ($this->window, 0, Gtk::MESSAGE_INFO, Gtk::BUTTONS_OK, $message);
		$dialog->set_title ($title);
		$dialog->run ();
		$dialog->destroy ();
	}
}
