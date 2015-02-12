<?php
if (!defined('_APP')) {
	exit;
}

class ItemList
{
	private
	$tab=array(), // id => item
	$indices = array(); //i => id

	function __construct($tab=array(), $objects=false)
	{
		if ($objects) {
			$this->tab = $tab;
			$this->indices = array_keys($tab);
		} else {
			foreach ($tab as $x=>$v) {
				$this->tab[$v['item_id']] = new Item($v, true);
				$this->indices[] = $v['item_id'];
			}
		}
	}

	function get($i)
	{
		return $this->tab[$this->indices[$i]];
	}

	function getByID($id)
	{
		return $this->tab[$id];
	}

	function getCount()
	{
		return count($this->tab);
	}

	function toArray()
	{
		return $this->tab;
	}

	function add($item)
	{
		$this->indices[] = $item->getID();
		return $this->tab[$item->getID()] = $item;
	}

	function getRootItem()
	{
		return $this->tab[end($this->indices)];
	}

}