


<div class="form_order">
<?php
  if($_POST){
   //$to = 'info@polskimiod.eu';
   $to = 'marcin@performer.pl';
   $subject = 'Kontakt ze strony CSV';
   
 
   
   $name = $_POST['name'];
   $email = $_POST['email'];
	$phone = $_POST['phone'];
	$contact = $_POST['contact'];
	$product = $_POST['product'];
	$product_spec = $_POST['product_spec'];
	$product_link = $_POST['product_link'];
	$product_amount = $_POST['product_amount'];
	$product_price = $_POST['product_price'];
	$product_dest1 = $_POST['product_dest1'];
	$product_dest2 = $_POST['product_dest2'];
	$product_dest3 = $_POST['product_dest3'];
	$dest_date = $_POST['dest_date'];
	$product_transport = $_POST['product_transport'];
	$message = $_POST['message'];

	
	
	
	
	
	$mail_body = " Zapytanie - csv-group.pl<br />
	Ten mail został wygenerowany automatycznie prosimy na niego nie odpowiadać<br /><br />  ";
	
	
	$mail_body .= 'Imię i Nazwisko: '.$name."<br /> ";
	$mail_body .= 'E-mail: '.$email."<br /> ";
	$mail_body .= 'Telefon: '.$phone."<br /> ";
	$mail_body .= 'Preferowana forma kontaktu: '.$contact."<br /> ";
	$mail_body .= 'Preferowany produkt: '.$product."<br /> ";
	$mail_body .= 'Specyfikacja produktu: '.$product_spec."<br /> ";
	$mail_body .= 'Link do produktu: '.$product_link."<br /> ";
	$mail_body .= 'Ilość: '.$product_amount."<br /> ";;
	$mail_body .= 'Dopuszczalna cena: '.$product_price."<br /> ";
	$mail_body .= 'Miejsce dostawy: '.$product_dest1.", ";
	$mail_body .= ' '.$product_dest2.", ";
	$mail_body .= ' '.$product_dest3."<br /> ";
	$mail_body .= 'Termin dostawy: '.$dest_date."<br /> ";
	$mail_body .= 'Liczba trasportów: '.$product_transport." w ciągu roku<br /> ";
	$mail_body .= 'Uwagi: '.$message."<br /> ";
   
   $mail_body .= '<br />
   Kontakt:<br />
   CSV Group Sp. z o.o. Sp. komandytowa<br />
   ul. Karola Libelta 1A lok.2, 61-706 Poznań, Polska<br /><br />

   tel kontaktowy: 0048-505-005-911<br />
   e-mail: info@csv-group.pl<br />';
	
	
	
   $robotest = $_POST['robotest'];
		
	$to_copy = $email;
	$subject_copy = 'Kopia formularza - csv-group.pl';
	$mail_body_copy = 'Kopia formularza - csv-group.pl <br /><br />';
	$mail_body_copy .= $mail_body;
	$mail_body_copy .= "<br /><br /> ";
		
    if($robotest)
      $error = "Zostaw ostatnie pole puste!";
    else{
      if($name ){
		
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
				$headers .= "From: ".$from_email." \r\n";
				$headers .= "Reply-To : ".$from_email." \r\n";
				
				$header_copy = "MIME-Version: 1.0" . "\r\n";
				$header_copy .= "Content-type:text/html;charset=UTF-8" . "\r\n";
				$header_copy .= "From: info@csv-group.pl \r\n";
				$header_copy .= "Reply-To : info@csv-group.pl \r\n";
				
        if(mail($to, $subject, $mail_body, $headers) && mail($to_copy, $subject_copy, $mail_body_copy, $header_copy))
				    $success = "Dziękujemy! Twój mail został wysłany.";
        else
          $error = "Wystąpił błąd podczas wysyłania formularza. Spróbuj ponownie";
      }else
        $error = "Wszystkie pola są obowiązkowe.";
    }
    if($error)
      echo '<div class="msg error">'.$error.'</div>';
    elseif($success)
      echo '<div class="msg success">'.$success.'</div>';
  }
?>


<form method="post" action="">

      <h2>Formularz - zapytanie ofertowe</h2>
   
    
    
    
      <p class="form_row name"><label>Imię i Nazwisko:</label>    <input name="name" type="text"  /></p>
      <p class="form_row email"><label>E-mail:</label>            <input name="email" type="text" /></p>
      <p class="form_row phone"><label>Telefon:</label>           <input name="phone" type="text" /></p>
      <p class="form_row contact"><label>Preferowana forma kontaktu:</label>
         <select name="contact">
            <option value="dowolny">dowolny</option>
            <option value="tele">telefon</option>
            <option value="e-mail">e-mail</option>
         </select>
      </p>
      <p class="form_row product"><label>Preferowany produkt:</label>               <input name="product" type="text" /></p>
      <p class="form_row product_spec"><label>Podaj specyfikacje produktu;</label>  <input name="product_spec" type="text" /></p> 
      <p class="form_row product_link"><label>Przykładowy link do produktu</label>  <input name="product_link" type="text" /></p>
      <p class="form_row product_amount"><label>Ilość:</label><br/>
            <input type="radio" class="radio" name="product_amount" value="drobnica 5 m3 (minimum)" />drobnica 5 m3 (minimum), <br/>
            <input type="radio" class="radio" name="product_amount" value="drobnica powyżej 5 m3" />drobnica powyżej 5 m3, <br/>
            <input type="radio" class="radio" name="product_amount" value="kontener 20" />kontener 20’, <br/>
            <input type="radio" class="radio" name="product_amount" value="kontener 40" />kontener 40’, <br/>
            <input type="radio" class="radio" name="product_amount" value="kontener 40 hq " />kontener 40 hq <br/>
      </p>
      <p class="form_row product_amount_link"><a href="http://www.csv-group.pl/5,0,faq-najczesciej-zadawane-pytania.html" title="Wymiary kontenerów" name="Wymiary kontenerów" target="_blank">Wymiary kontenerów?</a></p>
      <p class="form_row product_price"><label>Dopuszczalna cena produktu:</label><input name="product_price" type="text" /></p>
      <p class="form_row product_dest"><label>Miejsce dostarczenia towaru:</label></p>
      <p class="form_row product_dest1"><label>Państwo:</label><input name="product_dest1" type="text" /></p>
      <p class="form_row product_dest1"><label>Miasto:</label><input name="product_dest2" type="text" /></p>
      <p class="form_row product_dest1"><label>Kod pocztowy:</label><input name="product_dest3" type="text" /></p>
      <p class="form_row dest_date"><label>Preferowany termin dostawy:</label><input name="dest_date" type="text" /></p>
      <p class="form_row dest_date_link"><a href="http://www.csv-group.pl/5,0,faq-najczesciej-zadawane-pytania.html" title="Wymiary kontenerów" name="Wymiary kontenerów" target="_blank">Ile trwa dostarczenie towaru?</a> </p>
            <p class="form_row product_transport"><label>Szacowana liczba transportów:</label>
         <select name="product_transport">
            <option value="1" >1 raz</option>
            <option value="2" >2 razy</option>
            <option value="3">3 razy</option>
            <option value="5">5 razy</option>
            <option value="10">10 razy</option>
            <option value="20">20 razy</option>
            <option value="30">30 razy</option>
            <option value="więcej">więcej</option>
         </select> w ciągu roku.
      </p>
      <p class="form_row message"><label>Dodatkowe uwagi:</label><br/>
            <textarea name="message" cols="30" rows="5"></textarea>
      </p>

      <p class="form_row robotic" id="pot"><label>&nbsp; </label><input name="robotest" type="text" id="robotest" class="robotest" /> Antyspam - Zostaw puste pole </p>
      <p class="form_row submit"><input type="submit" value="Wyślij" class="button btn" /></p>


</form>
<div class="space"></div>
</div>




  

