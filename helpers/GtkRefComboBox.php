<?php
/**
 * Author: Sasa Tarbuk <sasa@mindframes.org>
 * Your reuse is governed by the Creative Commons Attribution 3.0 Unported License
 */

class GtkRefComboBox extends GtkComboBox
{
	public function setActiveRefID ($refID)
	{
		$refs = $this->get_model()->getRefs();
		$newActiveOption = isset ($refs[$refID]) ? $refs[$refID]->get_path () : null;
		$this->set_active ($newActiveOption !== null ? $newActiveOption[0] : -1);
	}
	
	public function getActiveRefID ()
	{
		$refs = $this->get_model()->getRefs();
		$activeIndex = $this->get_active ();
		
		foreach ($refs as $refID => $ref)
		{
			$option = $ref->get_path ();
			if ($option[0] === $activeIndex)
			{
				return $refID;
			}
		}
		return null;
	}
}