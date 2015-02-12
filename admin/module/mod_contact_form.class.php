<?php
define('mod_contact_form.class',1);

class mod_contact_form {
	function update($tab) {
		return _db_replace('mod_contact_forms',array('module_id'=>_db_int($tab['module_id']),'form_type'=>_db_string($tab['form_type']),'form_adres'=>_db_string($tab['form_adres']),'form_subject'=>_db_string($tab['form_subject'])));
	}
	function remove($id) {
		return _db_delete('mod_contact_forms','module_id='.intval($id),1);
	}
	function validate($tab,$T) {
		return true;
	}
	function get($id) {
		$data = _db_get_one('SELECT * FROM `'.DB_PREFIX.'mod_contact_forms` WHERE module_id='.intval($id).' LIMIT 1');
		// jesli jest wybrany inny typ formularza niż zapisany w bazie
		if($_GET['form_type_id']!=''){
			$data['form_type']=$_GET['form_type_id'];
		}
		$data['form_types'] = $this->getFormTypes();
		$data['form_type_print'] = $this->getFormByType($data['form_type']);
		return $data;
	}
	function front($module,$Item) {
		$data = $this->get($module['module_id']);
		$data_type = _db_get_one('SELECT * FROM `'.DB_PREFIX.'mod_contact_forms_type` where form_type_id='.intval($data['form_type']).' LIMIT 1');
		
		$style = $module['module_style'];
		$styles = array(
			0	=> 'style="float: left; margin: 0 10px 10px 0;" ',
			1	=> 'style="float: right; margin: 0 0 10px 10px;" ',
			2	=> 'style="margin: 20px 0; text-align: left" '
		);
			
		if($_REQUEST['send_contact_form'] == 'x'){
			
			// send email
			$mail_to=$data['form_adres'];
			$mail_from='pefrormer@performer.com.pl';
			$mail_reply_to=$mail_from;
			$mail_headers= 'From: '.$mail_from. "\r\n" .
    					'Reply-To: '.$mail_reply_to. "\r\n";
    					
    					
			$mail_subject=$data['form_subject'];
			foreach($_POST as $key => $value){
				if($key!='send_contact_form'){
					$mail_text.=$key.": ".$value."\n\n";
				}
			}
			if($mail_to!='' && $mail_text!=''){
				mail($mail_to,$mail_subject,$mail_text,$mail_headers);
			}
			
			
			?>
			Dziękujemy za przesłanie formularza.<br>
			<?
		}
		echo '<br><div ' . $styles[$style] . '>';
		?>
		<form method="POST" action="#contact_form_<?=$module['module_id']?>">
		<input type="hidden" name="send_contact_form" value="x">
		<?
		echo $data_type['form_type_html'];
		?>
		<input type="submit" value="wyślij">
		</form>
		<?
		echo '</div>';
	}
	function getFormTypes(){
		$data = _db_get('SELECT * FROM `'.DB_PREFIX.'mod_contact_forms_type`');
		foreach($data as $key => $value){
			$array[$value['form_type_id']]=$value['form_type_name'];
		}
		return $array;
	}
	function getForms(){
		$data = _db_get('SELECT * FROM `'.DB_PREFIX.'mod_contact_forms_type`');
		return $data;
	}
	function getFormByType($form_type_id){
		switch ($form_type_id) {
			case 1:
				return "<input type='text'><br><input type='checkbox'>";
			break;
			case 2:
				return "<input type='text'><br><textarea></textarea><br><input type='checkbox'>";
			break;
			case 3:
				return "<input type='text'><br><textarea></textarea><br><input type='checkbox'><br><select><option></option></select>";
			break;
			default:
			?>
			nie ma takiego formularza
			<?
				;
			break;
		}
		
	}
}
