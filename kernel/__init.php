<?php
///**
// * @author Jakub Roszkiewicz
// */
//if (!defined('ROOT_PATH')) {
//  if (dirname(dirname(__FILE__)) . '/' == '//') { //for lame home.pl hack
//    define('ROOT_PATH', DIRECTORY_SEPARATOR);
//  } else {
//    define('ROOT_PATH', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
//  }
//}
//
//require_once ROOT_PATH . 'kernel' . DIRECTORY_SEPARATOR . 'Classes' . DIRECTORY_SEPARATOR . 'Locale' . DIRECTORY_SEPARATOR . 'Class.Locale.main.php';
//
//require_once ROOT_PATH . 'kernel' . DIRECTORY_SEPARATOR . 'Include' . DIRECTORY_SEPARATOR . 'Functions' . DIRECTORY_SEPARATOR . 'Include.main.php';
//
////funkcja autoladujaca klasy z katalogu Class, w przypadku niepowodzenia zwraca false//
//function v_autoload($ClassName)
//{
//  if (class_exists($ClassName, false)) {
//    return true;
//  }
//  if (file_exists(ROOT_PATH . 'kernel' . DIRECTORY_SEPARATOR . 'Classes' . DIRECTORY_SEPARATOR . $ClassName . DIRECTORY_SEPARATOR . 'Class.' . $ClassName . '.main.php')) {
//    require_once ROOT_PATH . 'kernel' . DIRECTORY_SEPARATOR . 'Classes' . DIRECTORY_SEPARATOR . $ClassName . DIRECTORY_SEPARATOR .'Class.' . $ClassName . '.main.php';
//  }
//}
//
//spl_autoload_register('v_autoload');
//
////obsolete
//define('SANDBOX_PATH', ROOT_PATH);
//define('DOCTRINE_PATH', SANDBOX_PATH . 'kernel' . DIRECTORY_SEPARATOR . 'Doctrine');
//define('DATA_FIXTURES_PATH', SANDBOX_PATH . 'sql' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'fixtures');
//define('MODELS_PATH', SANDBOX_PATH . 'sql' . DIRECTORY_SEPARATOR . 'models');
//define('MIGRATIONS_PATH', SANDBOX_PATH . 'sql' . DIRECTORY_SEPARATOR . 'migrations');
//define('SQL_PATH', SANDBOX_PATH . 'sql' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'sql');
//define('YAML_SCHEMA_PATH', SANDBOX_PATH . DIRECTORY_SEPARATOR . 'sql' . DIRECTORY_SEPARATOR . 'schema');
//require_once ROOT_PATH . 'kernel' . DIRECTORY_SEPARATOR . 'Doctrine' . DIRECTORY_SEPARATOR . 'Doctrine.php';
//
//spl_autoload_register(array('Doctrine', 'autoload'));
////nazwy polaczen obsluzyc! albo najlepiej to foreachami jechac po roznych sql z configa
//Doctrine_Manager::connection(vConfig::get('sql/dsn'), 'sandbox');
//
//Doctrine_Manager::getInstance()->setAttribute('model_loading', 'conservative');
//if (!defined('CLI_VAKA')) {
//  Doctrine::loadModels(ROOT_PATH . 'sql' . DIRECTORY_SEPARATOR . 'models');
//}
