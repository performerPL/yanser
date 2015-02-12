
<div class="form_partners">
<?
//echo $_POST['go'];

  if ($_POST['go'] == '1') {    // formualrz przeslany do weryfikacji

				$name1=trim($_POST['name1']);
				$name2=trim($_POST['name2']);
				$mail=trim($_POST['mail']);
				$phone=trim($_POST['phone']);
				$text=trim($_POST['text']);
				$accept=trim($_POST['accept']);
				
							if ($mail=='' || $accept=='' || $name2=='' ) {
									$error = "1";
							} else {
									$error = "0";
							}
				
	}
	
	//echo $error;
	//echo $accept;
	
	
  if ($error == '0') { 

	$frommail = "biuro@berrytrade.pl";
	
	//$toaddress1 = 'biuro@berrytrade.pl';
	$toaddress2 = $mail;
	$toaddress1 = 'marcin@performer.pl';
	
  	$title1 = 'Formularz kontaktowy ze strony Yanser.pl';
	$title2 = 'Formularz kontaktowy ze strony Yanser.pl';
  
	
	$charset = 'utf-8';
	$head =
	"MIME-Version: 1.0\r\n" .
	"Content-Type: text/plain; charset=$charset\r\n" .
	"Content-transfer-Encoding: 8bit";
	

  $subject1 = "$title1";
	$subject2 = "$title2";
	

  $mailcontent1 = "Formularz kontaktowy ze strony BerryTrade: \n"
				."Firma: ".$name1." \n"	
				."Imię i Nazwisko: ".$name2."\n"	
				."Telefon: ".$phone."\n"	
				."E-mail: ".$mail."\n"	
				."treść: ".$text."\n"
				." \n";

				
  $mailcontent2 = "Formularz kontaktowy ze strony BerryTrade. \n"
				." \n"
				." \n"
				."Firma: ".$name1." \n"	
				."Imię i Nazwisko: ".$name2."\n"	
				."Telefon: ".$phone."\n"	
				."E-mail: ".$mail."\n"	
				."treść: ".$text."\n"
				." \n"
				."W razie pytań prosimy o kontakt:  \n" 
				."Yanser Polska \n" 
				."ul. Kopanina 54/56 \n" 
				."60-105 Poznań\n" 
				."tel. +48 61 661 62 35 \n" 
				."e-mail: yanserpl@yanser.com   \n"
				."www.yanser.pl  \n";
	
	$header = "Content-type: text/plain; charset=utf-8\r\n"
						."From: ".$frommail."\n";
	//$subject=iconv("utf-8","utf-8", $subject);
  //$subject='=?utf-8?B?'.base64_encode($subject).'?=';


	
	
  	mail($toaddress1, $subject1, $mailcontent1, $header);  
  	mail($toaddress3, $subject1, $mailcontent1, $header); 
	mail($toaddress2, $subject2, $mailcontent2, $header);  
?>


<p class="form_send"><b>Dziękujemy za wysłanie zgłoszenia. </b></p>
<p><? //echo nl2br($mailcontent); ?></p>
<?

} else {

?>


<form action="" method="post">
<input type="hidden" name="go" VALUE="1" />

<div class="form_row">
    <div class="form_left">Nazwa firmy: </div>
    <div class="form_right"><input class="input" type="text" value="<?=$_POST['name1']?>" name="name1" /></div>
</div>
<div class="space"></div>
<div class="form_row">
    <div class="form_left" <? if ($name2 == '' && $error == '1') { echo 'style="color: #ff0000; font-weight: bold;"'; } ?>>Imię i  Nazwisko: *</div>
    <div class="form_right"><input class="input" type="text" value="<?=$_POST['name2']?>" name="name2" /></div>
</div>
<div class="space"></div>
<div class="form_row">
    <div class="form_left">Telefon: </div>
    <div class="form_right"><input class="input" type="text" value="<?=$_POST['phone']?>" name="phone" /></div>
</div>
<div class="space"></div>
<div class="form_row">
    <div class="form_left" <? if ($mail == '' && $error == '1') { echo 'style="color: #ff0000; font-weight: bold;"'; } ?>>E-mail*: </div>
    <div class="form_right"><input class="input" type="text" value="<?=$_POST['mail']?>" name="mail" /></div>
</div>
<div class="space"></div>
<div class="form_row">
    <div class="form_left">treść: </div>
    <div class="form_right"><textarea class="input"  name="text" /><?=$_POST['text']?></textarea>	</div>
</div>
<div class="space"></div>
<div class="form_row">
    <div class="form_left"><input value="1" type="checkbox" name="accept" <? if ($accept == '1' ) { echo 'checked="divue"'; } ?> <? if ($accept == '' && $error == '1') { echo 'style="padding: 2px; width: 25px; background: #ff0000;"'; } ?> /></div>
    <div class="form_right">Wyrażam zgodę na przetwarzanie moich danych osobowych zawartych w formularzu, zgodnie z ustawą z dnia 29.08.1997 r.  o ochronie danych osobowych.</div>
</div>
<div class="space"></div>
<div class="form_row">
    <div class="form_left"></div>
    <div class="form_right"><sub <? if ($error == '1') { echo 'style="color: #ff0000; font-weight: bold;"'; } ?>>* pola obowiązkowe</sub></div>
</div>
<div class="space"></div>
<div class="form_row">
    <div class="form_left">&nbsp;&nbsp;&nbsp;  </div>
    <div class="form_right"><input type="submit" value="wyślij" class="btn" />  </div>
</div>
<div class="space"></div>
</form>





<?

}
?>
</div>