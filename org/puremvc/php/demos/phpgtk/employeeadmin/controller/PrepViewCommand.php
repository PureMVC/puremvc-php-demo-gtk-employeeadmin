<?php
/**
 * PureMVC PHP Employee Admin Demo by Sasa Tarbuk <sasa@mindframes.org>
 * PureMVC - Copyright(c) 2006-2010 Futurescale, Inc., Some rights reserved.
 * Your reuse is governed by the Creative Commons Attribution 3.0 Unported License
 */

class PrepViewCommand extends SimpleCommand
{
	public function execute (INotification $note)
	{
		$layoutMediator = new LayoutMediator (new LayoutComponent ());
		$this->facade->registerMediator ($layoutMediator);
		$layoutMediator->startView ();
	}
}