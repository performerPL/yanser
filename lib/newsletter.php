<?php
function newsletter_get($id)
{
	$R = _db_get_one('SELECT * FROM `'.DB_PREFIX.'newsletter` WHERE id='.intval($id));

	// przerabia grupy na tablice
	$R['groups'] = explode(",",$R['groups']);

	return $R;
}


/**
 * Usuwa newsletter.
 *
 * @param $id
 * @return unknown_type
 */
function newsletter_delete($id)
{
	return _db_delete('newsletter', 'id=' . intval($id), 1);
}

function newsletter_update($tab)
{
	$tab['groups'] = implode(",",$tab['groups']);

	$t = array(
        'title' => _db_string($tab['title']),
        'email_content' => _db_string($tab['email_content']),
        'groups' => _db_string($tab['groups']),
        'date_send' => _db_string($tab['date_send']),
	    'active' => _db_int($tab['active']),
	    'day_loop' => _db_int($tab['day_loop']),
	    'type' => _db_int($tab['type']),
	    'all_users' => _db_int($tab['all_users']),
	);
	$idt = 0;

	if ($tab['id'] > 0) {
		$return = _db_update('newsletter', $t, 'id=' . intval($tab['id']));
		$idt = $tab['id'];
	} else {
		$return = _db_insert('newsletter', $t);
		$idt = $return;
	}

	return $idt;
}

function newsletter_log_insert($tab)
{
    $t = array(
        'newsletter_id' => _db_int($tab['newsletter_id']),
        'datetime_send' => _db_string($tab['datetime_send']),
    );
 
    return _db_insert('newsletter_log', $t);
}

function newsletter_log_last($newsletter_id)
{
    $sql = ' SELECT * FROM `'.DB_PREFIX.'newsletter_log`';
    $sql .= ' WHERE newsletter_id = '.$newsletter_id;
    $sql .= ' ORDER BY datetime_send DESC';
    $sql .= ' LIMIT 1';

    $list = _db_get($sql);

    return $list[0]['datetime_send'];
}

function newsletter_log_list($newsletter_id)
{
    $sql = ' SELECT * FROM `'.DB_PREFIX.'newsletter_log`';
    $sql .= ' WHERE newsletter_id = '.$newsletter_id;
    $sql .= ' ORDER BY datetime_send DESC';

    $list = _db_get($sql);

    return $list;
}

function newsletter_validate($tab, $T)
{
	$res = array();
	return $res;
}

function newsletter_list($date,$orderBy = "title")
{
	$sql = ' SELECT * FROM `'.DB_PREFIX.'newsletter`';
	$sql .= ' WHERE active = 1 AND date_send = \''.$date.'\'';
	$sql .= ' ORDER BY '.$orderBy;

	$list = _db_get($sql);

	foreach($list as &$row) {
		// przerabia grupy na tablice
        $row['groups'] = explode(",",$row['groups']);
	}

	return $list;
}

function newsletter_list_item($criteria, $limit=0)
{
	if(!empty($criteria['newsletter_groups'])) {
		$groups = '';
		foreach($criteria['newsletter_groups'] as $group) {
			if(empty($groups)) {
				$groups .= " ( ";
			}
			else {
				$groups .= " OR ";
			}
			$groups .= ' pnga.newsletter_group_id = '. $group;
		}
		$groups .= ') ';
		// dodaje do warunku
		$where .= ' AND '.$groups;
	}

	if(!empty($criteria['date_start'])) {
		// dodaje do warunku
		$where .= ' AND DATE(i.show_start) >= \''.$criteria['date_start'] .'\'';
	}


	$query = 'SELECT i.* '
	. ' FROM ' . DB_PREFIX . 'item i '
	//    . 'LEFT JOIN cms_article a ON (i.article_id = a.article_id) '
	. ' JOIN ' . DB_PREFIX . 'item_promotion ip ON (ip.item_id = i.item_id) '
	. ' JOIN ' . DB_PREFIX . 'promotion_newsletter_group_access pnga ON (pnga.promotion_id = ip.promotion_id) '
	. ' WHERE i.active > 0 AND i.show_start <= NOW() AND (i.show_end >= NOW() OR i.show_endless > 0) '
	. $where . ' '
	. ' GROUP BY i.item_id '
	. ' ORDER BY i.show_start DESC ';

	if ($limit > 0) {
		$query .= 'LIMIT ' . intval($limit);
	}
	
	$list = _db_get($query);

	foreach($list as &$row) {
		$row['url'] = Site :: urlize($row['item_name']);
	}

	return $list;
}
