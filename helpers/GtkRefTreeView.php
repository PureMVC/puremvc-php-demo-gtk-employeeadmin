<?php
/**
 * Author: Sasa Tarbuk <sasa@mindframes.org>
 * Your reuse is governed by the Creative Commons Attribution 3.0 Unported License
 */

class GtkRefTreeView extends GtkTreeView
{
	public function getSingleActiveRefID ()
	{
		$selection = $this->get_selection ();
		list ($model, $iter) = $selection->get_selected ();
		$path = $model->get_path ($iter);
		$refs = $model->getRefs ();
		
		foreach ($refs as $refID => $ref)
		{
			$option = $ref->get_path ();
			if ($option[0] === $path[0])
			{
				return $refID;
			}
		}
		return null;
	}
}