<?php
$include_file = '../module/Bean.class.php';
if(file_exists($include_file)){
    require_once $include_file;
}


/**
 * Klasa odpowiadająca za działania w panelu admina dotyczące tagów.
 * 
 * @author Darek Skrzypczak <kontakt@app4you.pl>
 *
 */
class Tags extends Mod_Bean {

    
    // scieżka względna
    protected $includePath = '../';
    
    
    /**
     * Konstruktor klasy CodeBlock
     * 
     * 
     */
    public function __construct() {
        parent :: __construct();
        require_once $this->includePath.'module/mod_show_tags.class.php';
        
    }
 
    /**
     * Pobiera wszystkie moduły, wykonuje je i cachuje do plików.
     * 
     * @return unknown_type
     */
    public function cacheAll() {
    	// pobiera moduły typu tag
    	$mod_show_tags = new mod_show_tags();
    	$list = $mod_show_tags->getModules();
    	foreach($list as $moduleId => $moduleContent) {
    		$tags = $mod_show_tags->front(array('module_id'=>$moduleId),null,true);
    		file_put_contents('../upload/common/tags_'.$moduleId.'.html',$tags);
    	}
    }
    
}
?>