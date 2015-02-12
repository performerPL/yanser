<?php

/**
 * Klasa opakowująca klasę Spreadsheet_Excel_Writer.
 *
 *
 * @author Darek Skrzypczak <kontakt@app4you.pl>
 *
 */
class Excel extends Spreadsheet_Excel_Writer
{

	protected $worksheet = null;

	public $format_text;

	public $format_title;
	protected $format_no_frame;

	protected $format_footer;

	/**
	 * Konstruktor klasy excel.
	 */
	public function __construct()
	{

		parent::Spreadsheet_Excel_Writer();
		$this->setVersion(8);
		$this->_codepage = 'UTF-8';
		$this->format_title = &$this->addFormat(array('Size' => 9,'Align' => 'center','Valign' => 'center','Bold' => '1'));
		$this->format_title->setVAlign('vcenter');
		$this->format_title->setAlign('top');
		$this->format_title->setAlign('center');
		$this->format_title->setTextWrap();

		$this->format_left = &$this->addFormat(array('Size' => 9,'Align' => 'left','Bold' => '1'));
		$this->format_title->setVAlign('vcenter');

		$this->format_no_frame = &$this->addFormat(array('Size' => 9,'Align' => 'left','Bold' => '0'));
		$this->format_title->setVAlign('vcenter');
	}

	/**
	 * Tworzy tabele w excelu.
	 * Korzysta z tablic asocjacyjnych.
	 *
	 * @param Array $tableHeader Tablica z nazami kolumn.
	 * @param Array $tableValues Tablica z danymi.
	 * @param Array $tableFooter Tablica z podsumowaniem.
	 *
	 */
	public function createTable($worksheetName,$tableHeader,$tableValues,$tableFooter,$headName = "") {

		$worksheet =& $this->addWorksheet($worksheetName);

		// ustawia wejsciowe kodowanie na utf-8
		$worksheet->setInputEncoding('UTF-8');

		$colCnt = 0;
		//nazwy kolumn
		foreach($tableHeader as $id => $colName) {
			$worksheet->write(1, $colCnt++, $colName,$this->format_title);
		}

		$rowCnt = 1;
		//wartosci tabeli
		foreach($tableValues as $rowKey => $row) {
			$colCnt = 0;
			foreach($tableHeader as $colKey => $col) {
				$worksheet->write(($rowCnt+1), $colCnt++, strip_tags($row[$colKey]), $this->format_text);
			}

			$rowCnt++;
		}

		$footerRow = ( $rowCnt + 2);

		$colCnt = 0;
		//podsumowanie
		foreach($tableHeader as $colKey => $col) {
			//			$worksheet->write($footerRow, $colCnt++, $tableFooter[$colKey], $this->format_title);
		}

	}


	/**
	 * Wysyla gotowy dokument do przegladarki.
	 */
	public function sendXLS($fileName)
	{

		$this->send($fileName.'.xls');
		$this->close();

	}

}



?>
