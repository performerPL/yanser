<?

class mod_notice_groups {
	
	private function fetchGroupsC($GROUPS, $parent = 0)
	{
		foreach ($GROUPS as $k => $V) {
			if ($V['ng_parent_id'] == $parent) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Tworzy listę(drzewko) z grupami tematycznymi.
	 * Drzewko tworzy się rekurencyjnie.
	 * 
	 * @param $GROUPS
	 * @param $parent
	 * @return unknown_type
	 */
	public function fetchGroups($GROUPS,$parent = 0)
	{
		$class = '';//($parent == 0 ? 'root' : '');
		$body =  '<ul>';
		foreach ($GROUPS as $k => $V) {
			if ($V['ng_parent_id'] == $parent) {
				$body .=  '<li id="'. $V['ng_id'] .'" name="'. $parent .'" class="'. $class .'">';
				$body .=  '<span name="spanName">'. $V['ng_name'] .'</span>
				<div name="editDiv" style="display:none;">
				<input name="active" type="checkbox" value="1"';
                if($V[ng_active] == 1)
                    $body .= ' checked="checked"';
                $body .=  '>
                <input name="editName" type="text" value="'. $V['ng_name'] .'" /><a name="save">Zapisz</a> <a name="hideEdit">Ukryj<a/>
                </div>
				<div name="buttonsDiv">
				<a name="showEdit">Edytuj<a/>
				<a name="add">Dodaj</a>
				<a name="delete">Usun</a></div>';
				// sprawdza czy istnieje zagnieżdzenie
				if ($this->fetchGroupsC($GROUPS, $V['ng_id'])) {
					$body .= $this->fetchGroups($GROUPS,$V['ng_id']);
				}
				// dodaje pustego <ul>
				else {
					//$body .=  '<ul></ul>';	 
				}
				$body .=  '</li>';
			}
		}
		$body .=  '</ul>';
		
		return $body;
	}
	
	/**
	 * Tworzy listę(drzewko) z grupami globalnymi.
	 * 
	 * 
	 * @param $GROUPS
	 * 
	 * @return unknown_type
	 */
	public function fetchMainGroups($GROUPS)
	{
		$body =  '';
		foreach ($GROUPS as $k => $V) {
				$body .=  '<li id="'. $V['ngm_id'] .'">';
				$body .=  '<span class="folder"><input name="edit" type="text" value="'. $V['ngm_name'] .'" /></span>
				<div><input name="active" type="checkbox" value="1"';
				if($V[ngm_active] == 1)
					$body .= ' checked="checked"';
				$body .=  '><a name="delete">Usun</a></div>';				
				$body .=  '</li>';
		}
		
		return $body;
	}
	
}