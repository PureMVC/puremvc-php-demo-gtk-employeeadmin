<?php
/**
 * PureMVC PHP Employee Admin Demo by Sasa Tarbuk <sasa@mindframes.org>
 * PureMVC - Copyright(c) 2006-2010 Futurescale, Inc., Some rights reserved.
 * Your reuse is governed by the Creative Commons Attribution 3.0 Unported License
 */

class StartupCommand extends MacroCommand
{
	protected function initializeMacroCommand ()
	{
		$this->addSubCommand ('PrepModelCommand');
		$this->addSubCommand ('PrepViewCommand');
	}
}