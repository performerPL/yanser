<?php
require_once '../module/Bean.class.php';


/**
 * Klasa odpowiadająca za działania w panelu admina dotyczące newslettera.
 *
 * @author Darek Skrzypczak <kontakt@app4you.pl>
 *
 */
class Newsletter extends Mod_Bean {


	/**
	 * @var tablica mapująca dla kolumn
	 */
	protected $colList = array(
    "default" => "date_send DESC",
	1 => array("dbCol" => ""),
	2 => array("dbCol" => ""),
	3 => array("dbCol" => ""),
	4 => array("dbCol" => ""),
	5 => array("dbCol" => ""),
	);

	// scieżka względna
	protected $includePath = '../';


	/**
	 * Zwraca listę newsletterów wg podanych kryteriów.
	 *
	 * @param $criteria
	 * @param $limit
	 * @param $offset
	 * @param $orderBy
	 * @return unknown_type
	 */
	function getList($criteria,$limit = null,$offset = null,$orderBy = "date_send DESC")
	{
		$sql =
          ' SELECT * FROM `'.DB_PREFIX.'newsletter` '
          . ' WHERE 1 ';
          // dodanie kryteriów
          if(isset($criteria[show])) {
          }

          $sql .= ' ORDER BY '.$orderBy;

          // dodanie limitu
          if(!empty($limit)) {
          	$sql .= " LIMIT ". $offset . " , ". $limit;
          }

          $list =  _db_get($sql);

          foreach($list as &$row) {
          	$row['groups'] = explode(",",$row['groups']);
          	// data ostatniej wysyłki
          	$row['date_last_send'] = newsletter_log_last($row['id']);
          }

          return $list;
	}

	/**
	 * Wyswietla kod html ze stronnicowaniem .
	 * Stronnicowanie dla listy w adminie.
	 *
	 * @param $pagingObj Obiekt od stronnicowania
	 * @param $activity Typ aktywnosci
	 * @return unknown_type
	 */
	public function getPaging($pagingObj,$show) {
		$out = array();
		$out[paging] = $pagingObj;
		// lista limitów wierszy na stronie
		$out[params][limitList] = array(10 => 10,20 => 20,50 => 50,100 => 100,);
		// lista opcji do pokazywania
		//		$out[params][showList] = array(0 => "Wszystkie",1 => "Newsletter",2 => "Pełne");
		//		$out[params][show] = $show;

		// załącza tablicę z parametrami
		$this->smarty->assign('out',$out);
		// wyświetla listę
		$this->smarty->display("admin/newsletter/paging.html");
	}


	/**
	 * Wysyła newsletter do klientów.
	 * Odpalane przez cron.
	 *
	 * @return unknown_type
	 */
	public function sendAll() {
		// pobiera listę aktywnych newsleterow dla danego dnia
		$newsletters = newsletter_list(date("Y-m-d"));

		foreach($newsletters as $newsletter) {
			$this->send($newsletter['id']);
		}
	}

	/**
	 * Wysyłka newslettera.
	 *
	 * @param unknown_type $newsletter_id
	 * @return unknown_type
	 */
	public function send($newsletter_id) {
		// pobiera newslettera
		$newsletter = newsletter_get($newsletter_id);

		$mail = $this->getMailInstance();
		$mail->replace('type', $newsletter['type']);
		$mail->replace('email_content', $newsletter['email_content']);
		$mail->replace('date', date('Y-m-d'));
		$mail->replace('newsletter_groups', www_user_group_list(0,true));

		// gdy typ Spycjalny i wysyłka do wszystkich
		if( ($newsletter['type'] == 3) && ($newsletter['all_users'] == 1) ) {
			$user_list = www_user_list('wu_login',true);
		}
		else {
			// pobiera userów którzy odpowiadają warunkom wysyłki
			$user_list = www_user_groups_in($newsletter['groups']);
		}

		// licznik wyslanych maili
		$counter = 0;
		foreach($user_list as $user_id => $user) {
			// pobiera dane usera
			$user = www_user_get($user_id);
			// pobiera grupy usera - jako index id grupy
			$user_groups = www_user_get_group_access($user_id,'wug_id');

			// pobranie artykułów dla typu newslettera
			$articles = $this->getArticles($newsletter,array_keys($user_groups));
			// dla typu 0, lista musi być niepusta
			if(empty($articles) && $newsletter['type'] == 0) {
				return;
			}
			$mail->replace('articles', $articles);

			// TODO dodaje maila usera
			global $rootMailList;

			foreach($rootMailList as $email) {
				$mail->add($email);
			}
			
			$mail->replace('email',$user['wu_email']);

			$mail->assignTemplate('../_mail/newsletter');

			// ustawia temat
			$mail->setSubject($newsletter['title']." - do ".$user['wu_email']);
			$mail->send();
			
			// zwiększa licznik
			$counter++;
			// sprawdza czy nie należy zrobić przerwy - co 20 maili, przerwa 10 sek
			if($counter%20 == 0) {
				sleep(10);
			}
			

		}

		// ustawia datę na kolejny mailing
		$newsletter['date_send'] = date('Y-m-d',time() + 86400 * $newsletter['day_loop']);
		newsletter_update($newsletter);
		// dodaje wysyłkę do logow
		newsletter_log_insert(array('newsletter_id'=>$newsletter_id,'datetime_send'=>date('Y-m-d H:i:s')));

	}


	/**
	 * Testowa wysyłka newslettera.
	 * Wysyłka następuje do administratorów.
	 *
	 * @param unknown_type $newsletter_id
	 * @return unknown_type
	 */
	public function sendTest($newsletter_id) {
		// pobiera newslettera
		$newsletter = newsletter_get($newsletter_id);

		$mail = $this->getMailInstance();
		$mail->replace('type', $newsletter['type']);
		$mail->replace('email_content', $newsletter['email_content']);
		$mail->replace('date', date('Y-m-d'));
		$mail->replace('newsletter_groups', www_user_group_list(0,true));

		// pobranie artykułów dla typu newslettera
		$mail->replace('articles', $this->getArticles($newsletter,$newsletter['groups']));


		$mail->assignTemplate('../_mail/newsletter');

		global $rootMailList;

		foreach($rootMailList as $email) {
			$mail->add($email);
			$mail->replace('email',$email);
		}

		// ustawia temat
		$mail->setSubject($newsletter['title']);
		$mail->send();

	}


	/**
	 * Zwraca listę artykułów dla danego typu newslettera.
	 *
	 * @param unknown_type $type
	 * @param
	 * @return unknown_type
	 */
	public function getArticles($newsletter,$groups) {
		$list = array();

		switch($newsletter['type']) {
			// 0 - nowe artykuly = obecna data - day_loop
			case 0 :
				// liczy date minimalną
				$dateStart = date('Y-m-d',time() - 86400 * $newsletter['day_loop']);

				$criteria['newsletter_groups'] = $groups;
				$criteria['date_start'] = $dateStart;
				// pobiera itemy będące artykułami, utworzone po dacie minimalnej
				$list = newsletter_list_item($criteria);
					
				break;
				//1 - 10 najnowszych(grupy)
			case 1 :
				foreach($groups as $newsletter_group) {
					// pobiera ostatnich 10 artykulow tylko dla danej grupy
					$criteria['newsletter_groups'] = array($newsletter_group);
					$list_item = newsletter_list_item($criteria,10);
					// puste grupy nie są prezentowane w newsletterze
					if(empty($list_item)) {
						continue;
					}
					$list[$newsletter_group] = $list_item;
				}
				break;
				// 2 - 10 najnowszych
			case 2 :

				// pobiera itemy będące artykułami - limit 10 najnowszych
				$criteria['newsletter_groups'] = $groups;
				$list = newsletter_list_item($criteria,10);
				break;
		}

		return $list;
	}

}
?>