<?php
/**
 * Author: Sasa Tarbuk <sasa@mindframes.org>
 * Your reuse is governed by the Creative Commons Attribution 3.0 Unported License
 */

class GtkRefListStore extends GtkListStore
{
	private $refs = array ();
	
	public function getRefs ()
	{
		return $this->refs;
	}
	
	public function refInsert (array $data, $i = 0)
	{
		foreach ($data as $refID => $value)
		{
			$iter = $this->insert ($i ++);
			$path = $this->get_path ($iter);
			$this->refs [$refID] = new GtkTreeRowReference ($this, $path);
			$this->refSet ($iter, $refID, $value);
		}
	}
	
	public function refAdd ($refID, $value)
	{
		$iter = $this->insert (count ($this));
		$path = $this->get_path ($iter);
		$this->refs [$refID] = new GtkTreeRowReference ($this, $path);
		$this->refSet ($iter, $refID, $value);
	}
	
	public function refUpdate ($refID, $value)
	{
		$path = $this->refs[$refID]->get_path ();
		$iter = $this->get_iter ($path);
		$this->refSet ($iter, $refID, $value);
	}
	
	public function refRemove ($refID)
	{
		$path = $this->refs[$refID]->get_path ();
		$iter = $this->get_iter ($path);
		$this->remove ($iter);
		unset ($this->refs[$refID]);
	}
	
	public function refClear ()
	{
		$this->clear ();
		$this->refs = array ();
	}
	
	private function refSet (GtkTreeIter $iter, $refID, $value)
	{
		if (is_array ($value))
		{
			$params = array ($iter);
			foreach ($value as $col => $val) array_push ($params, $col, $val);
			call_user_func_array (array ($this, 'set'), $params);
		}
		else
		{
			$this->set ($iter, 0, $value);
		}
	}
}