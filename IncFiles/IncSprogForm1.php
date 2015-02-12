<div class="form_1_sprog">

<?


  if ($_POST['go'] == '1') {    // formualrz przeslany do weryfikacji

				$name1=trim($_POST['name1']);
				$name2=trim($_POST['name2']);
				$mail=trim($_POST['mail']);
				$phone=trim($_POST['phone']);
				$text=trim($_POST['text']);
				$accept=trim($_POST['accept']);
				$what=trim($_POST['what']);
				
							if ($mail=='' ) {
									$error = "1";
							} else {
									$error = "0";
							}
				
	}
	
	//echo $error;
	//echo $accept;
	/*
	switch($what) {
			case '1':
				 $temat = 'Zapytanie ogólne';
			break;
			case '2':
				 $temat = 'Informacje';
			break;
			case '3':
				 $temat = 'Ogłoszenie';
			break;
			case '4':
				 $temat = 'Współpraca';
			break;
			case '5':
				 $temat = 'Reklama';
			break;
			case '6':
				 $temat = 'Usterka techniczna';
			break;
	}
	*/
  if ($error == '0') { 

	$frommail = "automat@sprog.pl";
	
	$toaddress1 = 'roma@sprog.pl';
	//$toaddress2 = 'marcin@performer.pl';
	
  $title1 = 'Formularz ze strony sprog.pl: '.$temat.'';
  
	
	$charset = 'utf-8';
	$head =
	"MIME-Version: 1.0\r\n" .
	"Content-Type: text/plain; charset=$charset\r\n" .
	"Content-Transfer-Encoding: 8bit";
	
			

  $subject1 = "$title1";

	$data = date("Y-m-d, H:m:s");
	
  $mailcontent1 = 
				 "Kontakt z dnia:  ".$data." \n\n"
				."Imię i Nazwisko: ".$name1." ".$name2."\n"	
				."Telefon: ".$phone."\n"	
				."E-mail: ".$mail."\n"	
				."Treść: ".$text."\n"
				." \n";

				
	
	$header = "Content-type: text/plain; charset=utf-8\r\n"
						."From: ".$frommail."\n";
	//$subject=iconv("utf-8","utf-8", $subject);
  //$subject='=?utf-8?B?'.base64_encode($subject).'?=';


	
	
  mail($toaddress1, $subject1, $mailcontent1, $header);  
	//mail($toaddress2, $subject1, $mailcontent1, $header);  
?>



<p><? //  TEKST PO WYSLANIU MAILA echo nl2br($mailcontent); ?></p>
<?

} else {

?>


<form action="#" method="post">
<input type="hidden" name="go" VALUE="1" >


<div class="form_left">Imię: </div>
<div class="form_right"><input class="input" type="text" value="<?=$_POST['name1']?>" name="name1"></div>
<div class="space"></div>

<div class="form_left">Nazwisko: </div>
<div class="form_right"><input class="input" type="text" value="<?=$_POST['name2']?>" name="name2"></div>
<div class="space"></div>

<div class="form_left" <? if ($phone == '' && $error == '1') { echo 'style="color: #ff0000; font-weight: bold;"'; } ?>>Telefon: </div>
<div class="form_right"><input class="input" type="text" value="<?=$_POST['phone']?>" name="phone"></div>
<div class="space"></div>

<div class="form_left" <? if ($mail == '' && $error == '1') { echo 'style="color: #ff0000; font-weight: bold;"'; } ?>>E-mail*: </div>
<div class="form_right"><input class="input" type="text" value="<?=$_POST['mail']?>" name="mail"></div>
<div class="space"></div>

<div class="form_left" style="display: none;">Dotyczy: </div>
<div class="form_right"  style="display: none;">
<select name="what" class="input">
			<option value="1">Zapytanie ogólne</option>
			<option value="2">Informacje</option>
			<option value="3">Ogłoszenie</option>
			<option value="4">Współpraca</option>
			<option value="5">Reklama</option>
			<option value="6">Usterka techniczna</option>
</select>
</div>
<div class="space"></div>

<div class="form_left">Treść: </div>
<div class="form_right"><textarea class="textarea_kontakt" type="text"  name="text"><?=$_POST['text']?></textarea></div>
<div class="space"></div>

<div class="form_left">&nbsp;</div>
<div class="form_right"><sub <? if ($error == '1') { echo 'style="color: #ff0000; font-weight: bold;"'; } ?>>/* pola obowiązkowe</sub></div>
<div class="space"></div>

<div class="form_left">&nbsp;</div>
<div class="form_right"><input type="submit" value="wyślij" class="btn"> &nbsp;&nbsp;&nbsp;</div>
<div class="space"></div>


</form>





<?

}
?>
</div>