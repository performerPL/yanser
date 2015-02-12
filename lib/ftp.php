<?php
/**
 * File Manager System
 *
 * PHP version 5
 *
 * This source file is subject to the New BSD license, That is bundled
 * with this package in the file LICENSE, and is available through
 * the world-wide-web at http://www.opensource.org/licenses/bsd-license.php
 * If you did not receive a copy of the new BSDlicense and are unable
 * to obtain it through the world-wide-web, please send a note to
 * jacoz@php.net so we can mail you a copy immediately.
 *
 * @author    Jacopo Andrea Nuzzi <jacoz@php.net>
 * @copyright 2007-2008 Jacopo Andrea Nuzzi
 * @license   http://www.opensource.org/licenses/bsd-license.php  New BSD
 * @link      http://app.jaydns.net/FMS/
 */

// Replace constant for fixing a search error
define('FMS_REPLACE', '__File_Manager_System_REPLACE__');

// Require File Managery System error class
require_once 'ftp_error.php';


/**
 * This is class is very usefull if you are looking for a file manager.
 * It has got lots of functions:
 *
 * o You can get the list of files and/or directories contained in a
 *   selected folder with several information about them.
 *    o FILES:
 *      o name
 *      o position
 *      o size
 *      o date - last modify
 *      o type - extension or mime type (default: mime type)
 *      o permissions
 *
 *    o DIRECTORIES:
 *      o name
 *      o position
 *      o date - last modify
 *      o elements - files and directories contained in
 *
 * o You can sort files and/or directories:
 *    o FILES: (default: name ASC)
 *      o name [ASC, DESC] (default: ASC)
 *      o size [ASC, DESC] (default: ASC)
 *      o date - last modify [ASC, DESC] (default: ASC)
 *      o type - extension or mime type ASC, DESC] (default: ASC)
 *      o permissions [ASC, DESC] (default: ASC)
 *
 *    o DIRECTORIES: (default name: ASC)
 *      o name [ASC, DESC] (default: ASC)
 *      o date - last modify [ASC, DESC] (default: ASC)
 *      o elements - files and directories contained in [ASC, DESC] (default: ASC)
 *    o permissions [ASC, DESC] (default: ASC)
 *
 * o You can exclude files and/or directories from list.
 *
 * o You can search files and/or directories.
 *   You can limit the operation only in a selected folder, otherwise the engine
 *   will search (files and/or directories) also in sub-folders.
 *
 * o You can create, rename, delete directories.
 *
 * o You can create, rename, move, copy, delete, upload, highlight files.
 *
 * @author    Jacopo Andrea Nuzzi <jacoz@php.net>
 * @copyright 2007-2008 Jacopo Andrea Nuzzi
 * @license   http://www.opensource.org/licenses/bsd-license.php  New BSD
 * @link      http://app.jaydns.net/FMS/
 * @access    public
 */
class File_Manager_System
{

	/**
	 * Configuration array
	 *
	 * @var array
	 * @access protected
	 */
	public static $config = array();
	/**
	 * Icons array
	 * Each icon is combined with a file (or a folder)
	 *
	 * @var array
	 * @access private
	 */
	private $_iconsArray = array();

	/**
	 * Current directory without root directory
	 *
	 * @var string
	 * @access private
	 */
	protected static $currentDirectory = '/';

	/**
	 * Previous directory without root directory
	 *
	 * @var string
	 * @access private
	 */
	private $_prevDirectory;

	/**
	 * These directories will no appear in list
	 *
	 * @var array
	 * @access private
	 */
	private $_excludedDirectories = array();

	/**
	 * These files will no appear in list
	 *
	 * @var array
	 * @access private
	 */
	private $_excludedFiles = array();

	/**
	 * This array will contain directories and files and is used
	 * to print the list
	 *
	 * @var array
	 * @link http://fms.jaydns.net/manual/var.directory.php
	 * @access public
	 */
	public $directory = array();

	/**
	 * Number of elements (directories and files) in a folder
	 *
	 * @var int
	 * @access public
	 */
	public $directoryStats = 0;

	/**
	 * Directories array, is used to print the list
	 *
	 * @var array
	 * @link http://fms.jaydns.net/manual/var.directories.php
	 * @access public
	 */
	public $directories = array();

	/**
	 * Number of directories in a folder
	 *
	 * @var int
	 * @access public
	 */
	public $directoriesStats = 0;

	/**
	 * Directories name array
	 *
	 * @var array
	 * @access private
	 */
	private $_directoriesName = array();

	/**
	 * Directories date array
	 *
	 * @var array
	 * @access private
	 */
	private $_directoriesDate = array();

	/**
	 * Directories number of elements array
	 *
	 * @var array
	 * @access private
	 */
	private $_directoriesElements = array();

	/**
	 * Directories permissions array
	 *
	 * @var array
	 * @access private
	 */
	private $_directoriesPermissions = array();

	/**
	 * Files array, is used to print list
	 *
	 * @var array
	 * @link http://fms.jaydns.net/manual/var.files.php
	 * @access public
	 */
	public $files = array();

	/**
	 * Number of files in a folder
	 *
	 * @var int
	 * @access public
	 */
	public $filesStats = 0;

	/**
	 * Files name array
	 *
	 * @var array
	 * @access private
	 */
	private $_filesName = array();

	/**
	 * Files size array
	 *
	 * @var array
	 * @access private
	 */
	private $_filesSize = array();

	/**
	 * Files date array
	 *
	 * @var array
	 * @access private
	 */
	private $_filesDate = array();

	/**
	 * Files type array
	 * It can be just the extension or the mime type (default)
	 *
	 * @var array
	 * @access private
	 */
	private $_filesType = array();

	/**
	 * Files icon array
	 *
	 * @var array
	 * @access private
	 */
	private $_filesIcon = array();

	/**
	 * Files permissions array
	 *
	 * @var array
	 * @access private
	 */
	private $_filesPermissions = array();

	/**
	 * Class constructor
	 *
	 * @access private
	 * @return void
	 */
	public function __construct($rootDir = '')
	{
		global $GL_CONF;
		$cfg = $GL_CONF["IMAGES_FILES"];
		$this->mainDirectory = $rootDir.FILES_PATH;
		//echo 'indent'.$cfg["FILES_BASE_DIR"];
		//echo $mainDirectory;
		$numargs = func_num_args();
		if ($numargs > 0 && is_array($config = func_get_arg(0))) {
			$this->config = $config;
		}

		$this->setConfiguration();
	}

	/**
	 * Engine configuration
	 * If a configuration constant isn't set, it will be defined
	 *
	 * @access private
	 * @return void
	 */
	protected function setConfiguration()
	{
		$rootDir = $this->config['ROOT_DIRECTORY'];
		if (!$rootDir || $rootDir == '/' || !is_string($rootDir)) {
			$rootDir = $_SERVER['DOCUMENT_ROOT'];
		}
		$this->config['ROOT_DIRECTORY'] = FMS_checkDirectoryString($rootDir);

		$listDirs = $this->config['LIST_DIRECTORIES'];
		if (!is_bool($listDirs)) {
			$this->config['LIST_DIRECTORIES'] = true;
		}

		$listFiles = $this->config['LIST_FILES'];
		if (!is_bool($listFiles)) {
			$this->config['LIST_FILES'] = true;
		}

		$showSecretDirs = $this->config['SHOW_SECRET_DIRECTORIES'];
		if (!is_bool($showSecretDirs)) {
			$this->config['SHOW_SECRET_DIRECTORIES'] = true;
		}

		$showSecretFiles = $this->config['SHOW_SECRET_FILES'];
		if (!is_bool($showSecretFiles)) {
			$this->config['SHOW_SECRET_FILES'] = true;
		}

		$showMimeTypes = $this->config['SHOW_MIME_TYPES'];
		if (!is_bool($showMimeTypes)) {
			$this->config['SHOW_MIME_TYPES'] = true;
		}

		$filesCaseInsensitive = $this->config['FILES_CASE_INSENSITIVE'];
		if (!is_bool($filesCaseInsensitive)) {
			$this->config['FILES_CASE_INSENSITIVE'] = true;
		}

		$dirsCaseInsensitive = $this->config['DIRECTORIES_CASE_INSENSITIVE'];
		if (!is_bool($dirsCaseInsensitive)) {
			$this->config['DIRECTORIES_CASE_INSENSITIVE'] = true;
		}
	}

	/**
	 * With this function you can add you icons.
	 *
	 * @param array $icons Icons array
	 *
	 * @access public
	 * @uses setIcons(array $icons)
	 * @example
	 * <code>
	 * $fms = new File_Manager_System();
	 *
	 * $icons = array
	 * (
	 *     'htm html' => 'http://www.example.com/images/icons/html.gif',
	 *     'php php3 php4 phtml' => 'http://www.example.com/images/icons/php.gif',
	 *     'gif jpg png' => 'http://www.example.com/images/icons/image.gif',
	 *     'mp3 mid wav' => 'http://www.example.com/images/icons/audio.gif',
	 *     'mpg mpeg avi' => 'http://www.example.com/images/icons/video.gif'
	 * );
	 *
	 * $fms->setIcons($icons);
	 * </code>
	 * @return void
	 */
	public function setIcons($icons)
	{
		$this->_iconsArray = $icons;
	}

	/**
	 * Set exclusion of files or directories in the selected array
	 *
	 * @param array &$type   Files or directories array
	 * @param array $exclude Exclude elements
	 *
	 * @access private
	 * @return void
	 */
	private function _exclude(&$type, $exclude)
	{
		if (is_array($exclude) && count($exclude) <= 0) {
			array_push($exclude, '*');
		}

		array_push($type, $exclude);
		$type = FMS_arrayFlatten($type);
	}

	/**
	 * Add new elements (files or directories) to selected array
	 *
	 * @param array  &$type      Files or directories array
	 * @param array  $elements   Exclude elements
	 * @param string $typeString This value has to be "files" or "dirs"
	 *
	 * @access private
	 * @return void
	 */
	private function _exclude2(&$type, $elements, $typeString)
	{
		$excludeArray = FMS_excludeElements($this, $type, $elements, $typeString);
		array_push($type, $excludeArray);
		$type = FMS_arrayFlatten($type);
	}

	/**
	 * Set files to be excluded
	 *
	 * @access public
	 * @uses excludeFiles([string $.., string $...]], ...)
	 * @example This will exclude ALL files
	 * <code>
	 * $fms = new File_Manager_System();
	 * $fms->excludeFiles();
	 * </code>
	 *
	 * @example This will exclude all "html" files and "index.php" file
	 * <code>
	 * $fms = new File_Manager_System();
	 * $fms->excludeFiles('*.html', 'index.php');
	 * </code>
	 * @return void
	 */
	public function excludeFiles()
	{
		$args = func_get_args();
		$this->_exclude($this->_excludedFiles, $args);
	}

	/**
	 * Set directories to be excluded
	 *
	 * @access public
	 * @uses excludeDirectories([string $.., string $...]], ...)
	 * @example This will exclude ALL directories
	 * <code>
	 * $fms = new File_Manager_System();
	 * $fms->excludeDirectories();
	 * </code>
	 *
	 * @example This will exclude "private" and "account" directories
	 * <code>
	 * $fms = new File_Manager_System();
	 * $fms->excludeFiles('private', 'account');
	 * </code>
	 * @return void
	 */
	public function excludeDirectories()
	{
		$args = func_get_args();
		$this->_exclude($this->_excludedDirectories, $args);
	}

	/**
	 * Set directories and files to be excluded
	 *
	 * @access public
	 * @uses excludeBoth([string $.., string $...]], ...)
	 * @example This will exclude ALL directories and ALL files
	 * <code>
	 * $fms = new File_Manager_System();
	 * $fms->excludeBoth();
	 * </code>
	 *
	 * @example This will exclude all directories and all files that begin with "_"
	 * <code>
	 * $fms = new File_Manager_System();
	 * $fms->excludeBoth('_*');
	 * </code>
	 * @return void
	 */
	public function excludeBoth()
	{
		$args = func_get_args();
		$this->_exclude($this->_excludedFiles, $args);
		$this->_exclude($this->_excludedDirectories, $args);
	}

	/**
	 * Enter description here...
	 *
	 * @access public
	 * @uses getCurrentDirectory()
	 * @return string
	 * @example
	 * <code>
	 * $fms = new File_Manager_System();
	 * $currdir = $fms->getCurrentDirectory();
	 *
	 * echo $currdir;
	 * </code>
	 */
	public function getCurrentDirectory()
	{
		if ($this->currentDirectory == '') {
			$this->currentDirectory = '/';
		}

		return FMS_checkDirectoryString($this->currentDirectory);
	}

	/**
	 * You can get the previous directory
	 *
	 * @access public
	 * @uses getPrevDirectory()
	 * @return string
	 * @example
	 * <code>
	 * $fms = new File_Manager_System();
	 * $prevdir = $fms->getPrevDirectory();
	 *
	 * echo $prevdir;
	 * </code>
	 * @return string
	 */
	public function getPrevDirectory()
	{
		if ($this->currentDirectory == '') {
			$this->currentDirectory = '/';
		}

		if ($this->currentDirectory == true && $this->currentDirectory != '/') {
			$exp = explode('/', $this->currentDirectory);
			for ($i = 0; $i <= count($exp) - 3; $i++) {
				$output .= $exp[$i] . '/';
			}

			$this->_prevDirectory = $output;
			if ($this->_prevDirectory != '/') {
				$this->_prevDirectory = substr($this->_prevDirectory, 0, -1);
			}
		} else {
			$this->_prevDirectory = $this->currentDirectory;
		}

		return $this->_prevDirectory;
	}

	/**
	 * Get number of elements (directories or files) in a selected folder
	 *
	 * @param string $type      This var has to be "files" or "directories"
	 * @param string $directory Position
	 *
	 * @access private
	 * @return int
	 */
	private function _getStats($type, $directory = null)
	{
		$mainDirectory = $this->_checkDirectory($directory);

		$excludedFiles       = $this->_excludedFiles;
		$excludedDirectories = $this->_excludedDirectories;
		$results             = scandir($mainDirectory);
		$resultFiles         = array();
		$resultDirectories   = array();
		$filesStats          = 0;
		$directoriesStats    = 0;

		foreach ($results as $result) {
			if ($result != '' && $result != '.' && $result != '..') {
				if ($this->config['LIST_FILES'] &&
				(is_file($mainDirectory . $result) ||
				is_link($mainDirectory . $result))
				) {
					$resultFiles[] = $result;
				}
				if ($this->config['LIST_DIRECTORIES'] &&
				is_dir($mainDirectory . $result)
				) {
					$resultDirectories[] = $result;
				}
			}
		}
		$this->_exclude2($excludedFiles, $resultFiles, 'files');
		$this->_exclude2($excludedDirectories, $resultDirectories, 'dirs');

		foreach ($results as $result) {
			if ($result != '' && $result != '.' && $result != '..') {
				if ($this->config['LIST_FILES'] &&
				$type == 'files' &&
				is_file($mainDirectory . $result) &&
				!in_array($result, $excludedFiles)
				) {
					if ($this->config['SHOW_SECRET_FILES'] ||
					$result{0} != '.'
					) {
						$filesStats++;
					}
				}
				if ($this->config['LIST_DIRECTORIES'] &&
				$type == 'directories' &&
				is_dir($mainDirectory . $result) &&
				!in_array($result, $excludedDirectories)
				) {
					if ($this->config['SHOW_SECRET_DIRECTORIES'] ||
					$result{0} != '.'
					) {
						$directoriesStats++;
					}
				}
			}
		}

		if ($type == 'files') {
			if ($filesStats == 0) {
				$filesStats = '0';
			}

			return $filesStats;
		} elseif ($type == 'directories') {
			if ($directoriesStats == 0) {
				$directoriesStats = '0';
			}

			return $directoriesStats;
		}
	}

	function file_del($file_id, $type, $name, $directory='')
	{
		if ($type=='directory') {
			if ($this->deleteDirectory($name)) {
				$sql = 'DELETE FROM ' . DB_PREFIX . 'files '
				. 'WHERE file_id = ' . intval($file_id);
				if (!_db_query($sql)) {
					return true;
				}
			}
			else {
				return false;
			}
		} else {
			if ($this->deleteFile($name, $directory)) {
				$sql = 'DELETE FROM ' . DB_PREFIX . 'files '
				. 'WHERE file_id =" ' . intval($file_id).'"';
				if (_db_query($sql)) {
					$sql = 'DELETE FROM ' . DB_PREFIX . 'files_in'
					.' WHERE file_id = "' . intval($file_id).'"';
					if(_db_query($sql))
					return true;
				}
			}
		}
		return true;
	}

	function file_group_del($file_id, $group_id)
	{
		$sql = 'DELETE FROM ' . DB_PREFIX . 'files_in '
		.'WHERE file_id = ' . intval($file_id).' AND group_id='.intval($group_id);
		if(_db_query($sql))
		return true;
	}
	/**
	 * Get number of files in a selected folder
	 *
	 * @param string $directory Position
	 *
	 * @access public
	 * @uses getFilesStats([string $directory])
	 * @return int
	 * @example This will print the number of files in the current directory
	 * <code>
	 * $fms = new File_Manager_System();
	 *
	 * echo $fms->getFilesStats();
	 * </code>
	 *
	 * @example This will print the number of files in the "foo" directory
	 * <code>
	 * $fms = new File_Manager_System();
	 *
	 * echo $fms->getFilesStats('foo');
	 * </code>
	 */
	public function getFilesStats($directory = null)
	{
		return $this->_getStats('files', $directory);
	}

	/**
	 * Get number of directories in a selected folder
	 *
	 * @param string $directory Position
	 *
	 * @access public
	 * @uses getDirectoriesStats([string $directory])
	 * @return int
	 * @example This will print the number of directories in the current directory
	 * <code>
	 * $fms = new File_Manager_System();
	 *
	 * echo $fms->getDirectoriesStats();
	 * </code>
	 *
	 * @example This will print the number of directories in the "foo" directory
	 * <code>
	 * $fms = new File_Manager_System();
	 *
	 * echo $fms->getDirectoriesStats('foo');
	 * </code>
	 */
	public function getDirectoriesStats($directory = null)
	{
		return $this->_getStats('directories', $directory);
	}

	/**
	 * With this function you get the icon url.
	 *
	 * This is a private function, if you want to get the url use $element['icon']
	 * as is shown in the example below:
	 * <code>
	 * $fms = new File_Manager_System();
	 * $fms->listing();
	 * $fms->merge();
	 * foreach($fms->directory as $element)
	 * {
	 *     if ($element['icon'] != false)
	 *     {
	 *         echo '<img src="' . $element['icon'] . '"  alt="" /><br />';
	 *     }
	 * }
	 * </code>
	 *
	 * Note: this is just an example!
	 *
	 * @param string $filename File name
	 *
	 * @access private
	 * @return string|bool
	 */
	private function _getFileIcon($filename)
	{
		if (count($this->_iconsArray) > 0) {
			if ($filename == 'directory' && $this->_iconsArray['directory'] == true) {
				return $this->_iconsArray['directory'];
			}

			$ext = substr(strrchr($filename, '.'), 1);

			foreach ($this->_iconsArray as $exts => $info) {
				$exts_array = explode(' ', $exts);
				if (in_array($ext, $exts_array)) {
					return $info;
				}
			}

			if ($this->_iconsArray['unknown'] == true) {
				return $this->_iconsArray['unknown'];
			}
		}

		return false;
	}

	/**
	 * With this function you get the file mime type (or file extension).
	 *
	 * This is a private function, if you want to get the type use $element['type']
	 * as is shown in the example below:
	 * <code>
	 * $fms = new File_Manager_System();
	 * $fms->listing();
	 * $fms->merge();
	 * foreach($fms->directory as $element)
	 * {
	 *     echo '' . $element['type'] . '<br />';
	 * }
	 * </code>
	 *
	 * Note: this is just an example!
	 *
	 * @param string $filename File name
	 *
	 * @access private
	 * @return string
	 */
	private function _getFileType($filename)
	{
		$ext = substr(strrchr($filename, '.'), 1);

		if ($this->config['SHOW_MIME_TYPES']) {
			$mime_types = array (
                'application/cgi cgi',
                'application/ruby rb rhtm rhtml',
                'application/x-httpd-php php php3 php4 php5 php6 phtm phtml',
                'application/x-httpd-php-source phps',
                'application/perl pl perl',
                'application/java class',
                'application/x-java-source java',
                'application/msword doc',
                'application/pdf pdf',
                'application/vnd.ms-excel xls',
                'application/vnd.ms-powerpoint ppt',
                'application/x-gtar gtar',
                'application/x-gzip',
                'application/zip zip',
                'application/gnutar tgz',
                'application/x-gzip gz',
                'application/x-bzip bz',
                'application/x-bzip2 bz2',
                'application/x-javascript js',
                'application/x-sh sh',
                'application/x-shockwave-flash swf',
                'application/x-tar tar',
                'application/xhtml+xml xhtml xht',
                'application/xslt+xml xslt',
                'application/xml xml xsl',
                'application/zip zip',
                'audio/midi mid midi kar',
                'audio/mpeg mpga mp2 mp3',
                'application/vnd.rn-realmedia rm',
                'audio/x-wav wav',
                'image/bmp bmp',
                'image/gif gif',
                'image/jpeg jpeg jpg jpe',
                'image/png png',
                'image/svg+xml svg',
                'image/vnd.wap.wbmp wbmp',
                'image/x-icon ico',
                'text/css css',
                'text/asp asp',
                'text/html html htm',
                'text/x-server-parsed-html ssi shtm shtml',
                'text/plain asc txt',
                'text/richtext rtx',
                'text/rtf rtf',
                'text/vnd.wap.wml wml',
                'text/vnd.wap.wmlscript wmls',
                'video/mpeg mpeg mpg mpe',
                'video/quicktime qt mov',
                'video/x-msvideo avi'
                );

                foreach ($mime_types as $line) {
                	$mime_type = explode(' ', $line);
                	if (in_array($ext, $mime_type)) {
                		// If the extension was found prints the mime type
                		return $mime_type[0];
                	}
                }

                // If the extension wasn't found prints an other text...
                return 'n/a';
		}

		// Prints the file extension
		return $ext;
	}

	/**
	 * Gets permissions of a file or directory (chmod)
	 *
	 * @param string $element Element - file or directory
	 *
	 * @access private
	 * @return int
	 */
	private function _getElementPermissions($element)
	{
		return substr(sprintf('%o', fileperms($element)), -4);
	}

	/**
	 * With this function we get some informations on a file
	 *
	 * @param string $file File name
	 *
	 * @access private
	 * @return array
	 */
	private function _getFileInfo($file)
	{
		$dir      = $this->getElementInfo($file);
		$fileName = $this->getElementInfo($file, 'file');

		return array (
            'name' => $fileName,
            'position' => FMS_checkDirectoryString($dir, true, $this),
            'size' => filesize($file),
            'date' => filemtime($file),
            'type' => $this->_getFileType($fileName),
            'icon' => $this->_getFileIcon($fileName),
            'perms' => $this->_getElementPermissions($file)
		);
	}

	/**
	 * With this function we get some informations on a directory
	 *
	 * @param string $directory Position
	 *
	 * @access private
	 * @return array
	 */
	private function _getDirectoryInfo($directory)
	{
		$dir           = $this->getElementInfo($directory);
		$directoryName = $this->getElementInfo($directory, 'directory');

		$_replacedDirectory = FMS_checkDirectoryString($directory, true, $this);
		$directoriesStats   = $this->getDirectoriesStats($_replacedDirectory);
		$filesStats         = $this->getFilesStats($_replacedDirectory);

		return array (
            'name' => $directoryName,
            'position' => $dir,
            'date' => filemtime($directory),
            'type' => 'directory',
            'icon' => $this->_getFileIcon('directory'),
            'elements' => ($directoriesStats + $filesStats),
            'perms' => $this->_getElementPermissions($directory)
		);
	}

	/**
	 * Get directories and files list!
	 *
	 * @param string $directory Position
	 *
	 * @access public
	 * @uses listing([string $directory])
	 * @return void
	 * @example This will display only directories
	 * <code>
	 * $fms = new File_Manager_System();
	 * $fms->listing();
	 *
	 * foreach($fms->directories as $directory)
	 * {
	 *     echo '<li>' . $directory['name'] . '';
	 * }
	 *
	 * @example This will display only files
	 * <code>
	 * $fms = new File_Manager_System();
	 * $fms->listing();
	 *
	 * foreach($fms->files as $file)
	 * {
	 *     echo '<li>' . $file['name'] . '';
	 * }
	 *
	 * @example This will display only directories and files
	 * <code>
	 * $fms = new File_Manager_System();
	 * $fms->listing();
	 * $fms->merge();
	 *
	 * foreach($fms->directory as $element)
	 * {
	 *     if ($element['type'] == 'directory')
	 *     {
	 *         $element['name'] = '[DIR] ' . $element['name'];
	 *     }
	 *     echo '<li>' . $element['name'] . '';
	 * }
	 * </code>
	 */
	public function listing($directory = null)
	{
		if(!$this->_checkDirectory($directory))
		return 0;
		else
		$main_directory = $this->_checkDirectory($directory);

		$this->mainDirectory    = $main_directory;
		$this->currentDirectory = FMS_checkDirectoryString($main_directory, true, $this);
		$results                = scandir($this->mainDirectory);

		foreach ($results as $result) {
			if ($result != '' && $result != '.' && $result != '..') {
				if ($this->config['LIST_FILES'] &&
				(is_file($this->mainDirectory . $result) ||
				is_link($this->mainDirectory . $result))
				) {
					$resultFiles[] = $result;
				}
				if ($this->config['LIST_DIRECTORIES'] &&
				is_dir($this->mainDirectory . $result)
				) {
					$resultDirectories[] = $result;
				}
			}

		}
		$this->_exclude2($this->_excludedFiles, $resultFiles, 'files');
		$this->_exclude2($this->_excludedDirectories, $resultDirectories, 'dirs');

		foreach ($results as $result) {
			if ($result != '' && $result != '.' && $result != '..') {
				if ($this->config['LIST_FILES'] &&
				(is_file($this->mainDirectory . $result) ||
				is_link($this->mainDirectory . $result)) &&
				!in_array($result, $this->_excludedFiles)
				) {
					if ($this->config['SHOW_SECRET_FILES'] ||
					$result{0} != '.'
					) {
						$this->filesStats++;

						$this->files[] = $this->_getFileInfo(
						$this->mainDirectory . $result
						);
					}
				}
				if ($this->config['LIST_DIRECTORIES'] &&
				is_dir($this->mainDirectory . $result) &&
				!in_array($result, $this->_excludedDirectories)
				) {
					if ($this->config['SHOW_SECRET_DIRECTORIES'] ||
					$result{0} != '.'
					) {
						$this->directoriesStats++;

						$this->directories[] = $this->_getDirectoryInfo(
						$this->mainDirectory . $result
						);
					}
				}
			}
		}

		foreach ($this->files as $key => $row) {
			$this->_filesName[$key]        = $row['name'];
			$this->_filesSize[$key]        = $row['size'];
			$this->_filesDate[$key]        = $row['date'];
			$this->_filesType[$key]        = $row['type'];
			$this->_filesIcon[$key]        = $row['icon'];
			$this->_filesPermissions[$key] = $row['perms'];
		}

		foreach ($this->directories as $key => $row) {
			$this->_directoriesName[$key]        = $row['name'];
			$this->_directoriesDate[$key]        = $row['date'];
			$this->_directoriesElements[$key]    = $row['elements'];
			$this->_directoriesPermissions[$key] = $row['perms'];
		}

		$this->filesStats       -= 1;
		$this->directoriesStats -= 1;
		$this->directoryStats    = ($this->filesStats + $this->directoriesStats);
	}

	// }}}
	// {{{ sortFiles()

	/**
	 * Sort files through a parameter
	 *
	 * @param string $sortby  Sort method
	 * @param string $sortway Sort way
	 *
	 * @access public
	 * @uses sortFiles(string $sortby, string $sortway)
	 * @return void
	 * @example
	 * <code>
	 * $fms = new File_Manager_System();
	 * $fms->listing();
	 * $fms->sortFiles('size', 'desc');
	 * foreach($fms->files as $file)
	 * {
	 *     echo '<li>' . $file['name'] . '';
	 * }
	 * </code>
	 */
	public function sortFiles($sortby = null, $sortway = null)
	{
		$sortby  = strtolower($sortby);
		$sortway = strtolower($sortway);
		switch ($sortby) {
			case 'size' :
				$sortby   = $this->_filesSize;
				$sorttype = SORT_NUMERIC;
				break;
			case 'date' :
				$sortby   = $this->_filesDate;
				$sorttype = SORT_NUMERIC;
				break;
			case 'type' :
				$sortby   = $this->_filesType;
				$sorttype = SORT_REGULAR;
				break;
			case 'perms' :
				$sortby   = $this->_filesPermissions;
				$sorttype = SORT_NUMERIC;
				break;
			default :
				if ($this->config['FILES_CASE_INSENSITIVE']) {
					$sortby = array_map('strtolower', $this->_filesName);
				} else {
					$sortby = $this->_filesName;
				}
				$sorttype = SORT_STRING;
				break;
		}
		switch ($sortway) {
			case 'asc' :
				$sortway = SORT_ASC;
				break;
			case 'desc' :
				$sortway = SORT_DESC;
				break;
			default :
				$sortway = SORT_ASC;
				break;
		}

		array_multisort($sortby, $sortway, $sorttype,
		$this->_filesName, $sortway, SORT_STRING,
		$this->files);
	}

	/**
	 * Sort directories through a parameter
	 *
	 * @param string $sortby  Sort method
	 * @param string $sortway Sort way
	 *
	 * @access public
	 * @uses sortDirectories(string $sortby, string $sortway)
	 * @return void
	 * @example
	 * <code>
	 * $fms = new File_Manager_System();
	 * $fms->listing();
	 * $fms->sortDirectories('elements', 'desc');
	 * foreach($fms->directories as $directory)
	 * {
	 *     echo '<li>' . $directory['name'] . '';
	 * }
	 * </code>
	 */
	public function sortDirectories($sortby = null, $sortway = null)
	{
		switch ($sortby) {
			case 'date' :
				$sortby   = $this->_directoriesDate;
				$sorttype = SORT_NUMERIC;
				break;
			case 'elements' :
				$sortby   = $this->_directoriesElements;
				$sorttype = SORT_NUMERIC;
				break;
			case 'perms' :
				$sortby   = $this->_directoriesPermissions;
				$sorttype = SORT_NUMERIC;
				break;
			default :
				if ($this->config['DIRECTORIES_CASE_INSENSITIVE']) {
					$sortby = array_map('strtolower', $this->_directoriesName);
				} else {
					$sortby = $this->_directoriesName;
				}
				$sorttype = SORT_STRING;
				break;
		}
		switch ($sortway) {
			case 'asc' :
				$sortway = SORT_ASC;
				break;
			case 'desc' :
				$sortway = SORT_DESC;
				break;
			default :
				$sortway = SORT_ASC;
				break;
		}

		array_multisort($sortby, $sortway, $sorttype,
		$this->_directoriesName, $sortway, SORT_STRING,
		$this->directories);
	}

	/**
	 * Merge directories array and files array in one unique
	 * global array named 'directory'
	 *
	 * @param string $first  First array
	 * @param string $second Second array
	 *
	 * @access public
	 * @uses merge(string $first [optional], string $second [optional])
	 * @return void
	 * @example
	 * <code>
	 * $fms = new File_Manager_System();
	 * $fms->listing();
	 * $fms->merge();
	 *
	 * foreach($fms->directory = $element)
	 * {
	 *     if ($element['type'] == 'directory')
	 *     {
	 *         $element['name'] = '[DIR] ' . $element['name'];
	 *     }
	 *     echo '<li>' . $element['name'] . '';
	 * }
	 * </code>
	 */
	public function merge($first = 'directories', $second = 'files')
	{
		//eval('$this->directory = array_merge($this->' . $first . ', $this->' . $second . ');');
		$this->directory = array_merge($this->$first, $this->$second);
	}

	/**
	 * Replace directory string
	 *
	 * @param string $directory Position
	 *
	 * @access private
	 * @return string|bool
	 */
	private function _checkDirectory($directory = null)
	{
		// drugi parametr na false oznacza brak dodania scieżki bezwględnej
		$directory = FMS_checkDirectoryString($directory, false, $this);
		$returnDir = '';
		if (!$directory && $this->mainDirectory != '') {
			$returnDir = FMS_checkDirectoryString($this->mainDirectory);
		}
		elseif (!$directory && $this->mainDirectory == '') {
			$returnDir = $this->config['ROOT_DIRECTORY'];
		}
		elseif ($directory && $directory{0} == '/') {
			// drugi parametr na false oznacza brak dodania scieżki bezwględnej
			$returnDir = FMS_checkDirectoryString($this->mainDirectory.$directory,false);
		}
		elseif ($directory &&
		$directory{0} != '/' &&
		$this->mainDirectory != ''
		) {
			$returnDir = FMS_checkDirectoryString($this->mainDirectory .
			$directory);
		} elseif ($directory &&
		$directory{0} != '/' &&
		$this->mainDirectory == ''
		) {
			$returnDir = FMS_checkDirectoryString($this->config['ROOT_DIRECTORY'] .
			$directory);
		} else {
			//echo $returnDir;
			FMS_Error::raiseError(102);
		}

		if (file_exists($returnDir)){
			return $returnDir;
		} else {
//			FMS_Error::raiseError(101);
			return false;
		}
	}

	/**
	 * Gets info of an element
	 *
	 * @param string $element File or directory
	 * @param string $what    File or directory or null
	 * @param string $root    Root directory (true or false)
	 *
	 * @access private
	 * @return string
	 */
	protected function getElementInfo($element, $what = null, $root = null)
	{
		// If the last char is "/" delete it
		if (substr($element, -1) == '/') {
			$element = FMS_delSlashes($element, false, true);
		}

		$explode = explode('/', $element);
		$count   = count($explode);

		if ($count == 0) {
			$directory = '/';
		} else {
			$myelement = array_pop($explode);
			$directory = implode('/', $explode);
		}

		if (is_null($what)) {
			$rootDirectory = '';
			if (!is_null($root)) {
				$rootDirectory = $this->config['ROOT_DIRECTORY'];
			}

			return FMS_checkDirectoryString($rootDirectory . $directory);
		} elseif ($what == 'file' || $what == 'directory') {
			return $myelement;
		}
	}

	/**
	 * Rename file or directory
	 *
	 * @param string $file      File
	 * @param string $newname   New name
	 * @param string $directory Position
	 *
	 * @access protected
	 * @return bool
	 */
	protected function renameElement($file, $newname, $directory = null)
	{
		$main_directory = $this->_checkDirectory($directory);

		if (is_dir($main_directory) && is_writable($main_directory)) {
			if (@rename($main_directory . $file, $main_directory . $newname)) {
				return true;
			}

			FMS_Error::raiseError(401);
		}

		FMS_Error::raiseError(402);
	}

	/**
	 * Alias of renameElement()
	 * Rename directory
	 *
	 * @param string $dirname   Directory name
	 * @param string $newname   New name
	 * @param string $directory Position
	 *
	 * @access public
	 * @uses renameDirectory(string $dirname, string $newname, [string $directory])
	 * @return bool
	 * @example This will rename directory "foo" in root directory
	 * <code>
	 * $fms = new File_Manager_System();
	 * if ($fms->renameDirectory('foo', 'foo2'))
	 * {
	 *     echo 'Ok!';
	 * }
	 * else
	 * {
	 *     echo 'Error';
	 * }
	 * </code>
	 */
	public function renameDirectory($dirname, $newname, $directory = null)
	{
		return $this->renameElement($dirname, $newname, $directory);
	}

	/**
	 * Delete a directory
	 *
	 * @param string $directory Position
	 * @param bool   $complete  Delete all sub-files and sub-directories
	 *
	 * @access public
	 * @uses deleteDirectory(string $directory, [bool $complete])
	 * @return bool
	 * @example This will delete directory "foo" and all its content
	 * <code>
	 * $fms = new File_Manager_System();
	 * if ($fms->deleteDirectory('foo', true))
	 * {
	 *     echo 'Ok!';
	 * }
	 * else
	 * {
	 *     echo 'Error';
	 * }
	 * </code>
	 *
	 * @example This will delete directory "foo" if it doesn't contains files
	 *          or directories
	 * <code>
	 * $fms = new File_Manager_System();
	 * if ($fms->deleteDirectory('foo'))
	 * {
	 *     echo 'Ok!';
	 * }
	 * else
	 * {
	 *     echo 'Error';
	 * }
	 * </code>
	 */
	public function deleteDirectory($directory, $complete = null)
	{
		if ($directory == '') {
			return false;
		}
		$directory = trim($directory);

		static $i = 0;
		if ($i == 0) {
			$directory = FMS_checkDirectoryString(
			$this->config['ROOT_DIRECTORY'] . FILES_PATH . $directory
			);
		}
		if (!file_exists($directory)) {
			FMS_Error::raiseError(202);
		}

		if ($complete) {
			$filename = FMS_delSlashes($directory, false, true);
			if (is_file($filename) || is_link($filename)) {
				return unlink($filename);
			}

			$dir = dir($directory);
			while (false !== $result = $dir->read()) {
				if ($result == '.' || $result == '..') {
					continue;
				}

				$this->deleteDirectory($directory . $result . '/', true);
			}

			$dir->close();
		}

		return @rmdir($directory);
	}

	/**
	 * Create a directory
	 * If directory already exists it will create the directory just adding (N)
	 * at the end of the directory name
	 *
	 * @param string $name      Directory name
	 * @param string $directory Position
	 *
	 * @access public
	 * @uses createDirectory(string $name, [string $directory])
	 * @return bool
	 * @example This will create a directory named "foo" in root directory
	 * <code>
	 * $fms = new File_Manager_System();
	 * if ($fms->createDirectory('foo'))
	 * {
	 *     echo 'Ok!';
	 * }
	 * else
	 * {
	 *     echo 'Error';
	 * }
	 * </code>
	 */
	public function createDirectory($name, $directory = null, $group_id=null)
	{
		$main_directory = $this->_checkDirectory($directory);

		if (is_dir($main_directory) && is_writable($main_directory)) {
			if (!is_dir($main_directory . $name)) {
				if (mkdir($main_directory . $name)) {
					$t = array(
						'file_name'=>_db_string($name),
						'file_group_id'=>_db_string($group_id),		
						'file_type'=>_db_string('directory'),		
						'file_path'=>_db_string($directory),		
					);
					$new_file_id  = _db_insert('files',$t);
					$Tab = array(
						"file_id" => $new_file_id,					
						"group_id" => $group_id);
					//group_file_add($Tab);
					return true;
				}
				FMS_Error::raiseError(401);
			} else {
				$name    = str_replace(array('(', ')'), array('\\(', '\\)'), $name);
				$lastDir = array_pop(
				$this->searchDirectories(
                        '' . $name . ' \(*',
				FMS_checkDirectoryString($main_directory, true, $this),
				false
				)
				);

				$regexp        = '/.*\((.*).*\)/i';
				$last_dir_copy = (int) (preg_replace($regexp, '\1', $lastDir) + 1);
				$newname       = stripslashes($name . ' (' . $last_dir_copy . ')');

				if (mkdir($main_directory . $newname)) {
					$t = array(
						'file_name'=>_db_string($newname),
						'file_group_id'=>_db_string($group_id),		
						'file_type'=>_db_string('directory'),		
						'file_path'=>_db_string($directory),		
					);
					$new_file_id  = _db_insert('files',$t);
					$Tab = array(
						"file_id" => $new_file_id,					
						"group_id" => $group_id);
					//group_file_add($Tab);
					return true;
				}

				FMS_Error::raiseError(401);
			}
		}

		FMS_Error::raiseError(402);
	}

	/**
	 * Alias of renameElement()
	 * Rename a file
	 *
	 * @param string $file      File name
	 * @param string $newname   New name
	 * @param string $directory Position
	 *
	 * @access public
	 * @uses renameFile(string $file, string $newname, [string $directory])
	 * @return bool
	 * @example This will rename file "foo.html" in root directory
	 * <code>
	 * $fms = new File_Manager_System();
	 * if ($fms->renameFile('foo.html', 'foo2.html'))
	 * {
	 *     echo 'Ok!';
	 * }
	 * else
	 * {
	 *     echo 'Error';
	 * }
	 * </code>
	 */
	public function renameFile($file, $newname, $directory = null)
	{
		return $this->renameElement($file, $newname, $directory);
	}

	/**
	 * Delete a file
	 *
	 * @param string $file      File
	 * @param string $directory Position
	 *
	 * @access public
	 * @uses deleteFile(string $file, [string $directory])
	 * @return bool
	 * @example This will delete file "foo.html" in root directory
	 * <code>
	 * $fms = new File_Manager_System();
	 * if ($fms->deleteFile('foo.html'))
	 * {
	 *     echo 'Ok!';
	 * }
	 * else
	 * {
	 *     echo 'Error';
	 * }
	 * </code>
	 *
	 */
	public function deleteFile($file, $directory = null)
	{
		$main_directory = $this->_checkDirectory($directory);

		if (is_dir($main_directory) && is_writable($main_directory)) {
			//echo $main_directory . $file;
			if (unlink($main_directory . $file)) {
				return true;
			}
			FMS_Error::raiseError(401);
		}

		FMS_Error::raiseError(402);
	}

	/**
	 * Copy a file (private)
	 * If file already exists and $override option is false, it will rename
	 * the file just adding (N) before the extension
	 *
	 * @param string $file      File
	 * @param string $directory Position
	 * @param string $newdir    New position
	 * @param bool   $override  Override (if null, will rename the new element)
	 *
	 * @access private
	 * @return bool
	 * @example If you want to copy in root directory the file "foo.html", but
	 * it already exists the file will be named "foo (1).html".
	 */
	private function _copyFilePrivate($file, $directory, $newdir, $override = null)
	{
		if (is_null($override)) {
			$explode   = explode('.', $file);
			$extension = array_pop($explode);
			$filename  = implode('.', $explode);
			$name      = str_replace(array('(', ')'), array('\\(', '\\)'), $filename);
			$last_file = array_pop(
			$this->searchFiles(
                    '' . $name . ' (*).' . $extension . '',
			FMS_checkDirectoryString(
			FMS_if($directory == $newdir, $directory, $newdir),
			true,
			$this
			),
			false
			)
			);

			$regexp         = '/.*\((.*)\)\..*/i';
			$last_file_copy = (int) (preg_replace($regexp, '\1', $last_file) + 1);

			$newfile = stripslashes($name . ' (' . $last_file_copy . ').' . $extension);
		} else {
			$newfile = $file;
		}

		return copy($directory . $file, $newdir . $newfile);
	}

	/**
	 * Copy a file
	 *
	 * @param string $file      File
	 * @param string $newdir    New position
	 * @param string $directory Position
	 * @param bool   $override  Override (if null, will rename the element)
	 *
	 * @access public
	 * @uses copyFile(string $file, [string $newdirectory, [string $directory, [bool $override]]])
	 * @return bool
	 * @example This will copy file "foo.html" in "foodir" directory
	 * <code>
	 * $fms = new File_Manager_System();
	 * if ($fms->copyFile('foo.html', 'foodir'))
	 * {
	 *     echo 'Ok!';
	 * }
	 * else
	 * {
	 *     echo 'Error';
	 * }
	 * </code>
	 */
	public function copyFile($file, $newdir = null, $directory = null, $override = null)
	{
		$main_directory = $this->_checkDirectory($directory);

		if (is_null($newdir) || $newdir == '') {
			$newdir = $main_directory;
		} else {
			$newdir = FMS_checkDirectoryString(
			$this->config['ROOT_DIRECTORY'] .
			FMS_checkDirectoryString($newdir, true, $this)
			);
		}

		if (is_dir($newdir) && is_writable($newdir)) {
			if (file_exists($newdir . $file)) {
				if ($override == true) {
					if ($this->deleteFile(
					$file,
					FMS_checkDirectoryString($newdir, true, $this)
					) &&
					$this->_copyFilePrivate($file, $main_directory, $newdir, $override)
					) {
						return true;
					}

					FMS_Error::raiseError(401);
				} else {
					if ($this->_copyFilePrivate(
					$file,
					$main_directory,
					$newdir,
					$override
					)
					) {
						return true;
					}

					FMS_Error::raiseError(401);
				}
			} else {
				if ($this->_copyFilePrivate($file, $main_directory, $newdir, false)) {
					return true;
				}

				FMS_Error::raiseError(401);
			}
		}

		FMS_Error::raiseError(402);
	}

	/**
	 * Create a file (private)
	 * If file already exists and $override option is false, it will create
	 * the file just adding (N) before the extension
	 *
	 * @param string $file      File
	 * @param string $directory Position
	 * @param bool   $override  Override (if null, will rename the element)
	 *
	 * @access private
	 * @return bool
	 * @example If you want to create in root directory the file "foo.html", but
	 * it already exists the file will be named "foo (1).html".
	 */
	private function _createFilePrivate($file, $directory, $override = null)
	{
		if (is_null($override) && file_exists($directory . $file)) {
			$explode        = explode('.', $file);
			$extension      = array_pop($explode);
			$filename       = implode('.', $explode);
			$name           = str_replace(array('(', ')'), array('\\(', '\\)'), $filename);
			$last_file      = array_pop(
			$this->searchFiles(
                    '' . $name . ' (*).' . $extension . '',
			FMS_checkDirectoryString($directory, true, $this),
			false
			)
			);
			$regexp         = '/.*\((.*)\)\..*/i';
			$last_file_copy = (int) (preg_replace($regexp, '\1', $last_file) + 1);
			$newfile        = stripslashes(
			$name . ' (' . $last_file_copy . ').' . $extension
			);
		} else {
			$newfile = $file;
		}

		$f = @fopen($directory . $newfile, 'w');
		@fputs($f, '');
		@fclose($f);

		if (file_exists($directory . $newfile)) {
			@chmod($directory . $newfile, 0777);

			return true;
		}

		FMS_Error::raiseError(402);
	}

	/**
	 * Create a file
	 *
	 * @param string $file      File
	 * @param string $directory Position
	 * @param bool   $override  Override (if null, will rename the element)
	 *
	 * @access public
	 * @uses createFile(string $file, [string $directory, [bool $override]]);
	 * @return bool
	 * @example This will create file "foo.html" in root directory
	 * <code>
	 * $fms = new File_Manager_System();
	 * if ($fms->createFile('foo.html'))
	 * {
	 *     echo 'Ok!';
	 * }
	 * else
	 * {
	 *     echo 'Error';
	 * }
	 * </code>
	 */
	public function createFile($file, $directory = null, $override = null)
	{
		$main_directory = $this->_checkDirectory($directory);

		if (is_dir($main_directory) && is_writable($main_directory)) {
			if (file_exists($main_directory . $file)) {
				if ($override == true) {
					if ($this->deleteFile($file, $main_directory) &&
					$this->_createFilePrivate(
					$file,
					$main_directory,
					$override
					)
					) {
						return true;
					}

					FMS_Error::raiseError(401);
				} else {
					if ($this->_createFilePrivate(
					$file,
					$main_directory,
					$override
					)
					) {
						return true;
					}

					FMS_Error::raiseError(401);
				}
			} else {
				$this->_createFilePrivate($file, $main_directory, $override);

				return true;
			}
		}

		FMS_Error::raiseError(402);
	}

	/**
	 * Move a file to another directory
	 *
	 * @param string $file      File
	 * @param string $newdir    New position
	 * @param string $directory Position
	 * @param bool   $override  Override (if null, will rename the element)
	 *
	 * @access public
	 * @uses moveFile(string $file, string $newdirectory, [string $directory, [bool $override]])
	 * @return bool
	 * @example This will move "foo.html" to "foodir"
	 * <code>
	 * $fms = new File_Manager_System();
	 * if ($fms->moveFile('foo.html', 'foodir'))
	 * {
	 *     echo 'Ok!';
	 * }
	 * else
	 * {
	 *     echo 'Error';
	 * }
	 * </code>
	 */
	public function moveFile($file, $newdir, $directory = null, $override = null)
	{
		$main_directory = $this->_checkDirectory($directory);

		if ($newdir == false) {
			FMS_Error::raiseError(403);
		} else {
			$newdir = $this->_checkDirectory($newdir);
		}

		if (is_dir($newdir) && is_writable($newdir)) {
			if (file_exists($newdir . $file)) {
				if ($override == true) {
					if ($this->deleteFile(
					$file,
					FMS_checkDirectoryString($newdir, true, $this)
					) &&
					$this->_copyFilePrivate(
					$file,
					$main_directory,
					$newdir,
					$override
					) &&
					$this->deleteFile(
					$file,
					FMS_checkDirectoryString(
					$main_directory, true, $this
					)
					)
					) {
						return true;
					}

					FMS_Error::raiseError(401);
				} else {
					if ($this->_copyFilePrivate(
					$file,
					$main_directory,
					$newdir,
					$override
					) &&
					$this->deleteFile(
					$file,
					FMS_checkDirectoryString(
					$main_directory, true, $this
					)
					)
					) {
						return true;
					}

					FMS_Error::raiseError(401);
				}
			} else {
				if ($this->_copyFilePrivate(
				$file,
				$main_directory,
				$newdir,
				false
				) &&
				$this->deleteFile(
				$file,
				FMS_checkDirectoryString($main_directory, true, $this)
				)
				) {
					return true;
				}

				FMS_Error::raiseError(401);
			}
		}

		FMS_Error::raiseError(402);
	}

	/**
	 * Upload from your harddisk a file
	 *
	 * @param string $fieldname Field name (HTML form)
	 * @param string $directory Position
	 * @param bool   $override  Override (if null, will rename the element)
	 *
	 * @access public
	 * @uses uploadFile(string $fieldname, [string $directory, [bool $override]]);
	 * @return bool
	 * @example This will upload your file to your web server
	 * <code>
	 * </code>
	 */
	public function uploadFile($fieldname, $post, $directory = null, $override = null)
	{

		$main_directory = $this->_checkDirectory($directory);
		if (gettype($_FILES[$fieldname]['error'][0]) != 'integer') {
			$_FILES[$fieldname]['name']     = array($_FILES[$fieldname]['name']);
			$_FILES[$fieldname]['type']     = array($_FILES[$fieldname]['type']);
			$_FILES[$fieldname]['tmp_name'] = array($_FILES[$fieldname]['tmp_name']);
			$_FILES[$fieldname]['error']    = array($_FILES[$fieldname]['error']);
			$_FILES[$fieldname]['size']     = array($_FILES[$fieldname]['size']);
		}

		if (is_dir($main_directory) && is_writable($main_directory)) {
			foreach ($_FILES[$fieldname]['error'] as $key => $error) {
				if ($error == UPLOAD_ERR_OK) {
					$tmp_name = $_FILES[$fieldname]['tmp_name'][$key];
					$name     = $_FILES[$fieldname]['name'][$key];

					if (file_exists($main_directory . $name)) {
						if ($override == true) {
							unlink($main_directory . $name);
							move_uploaded_file($tmp_name, $main_directory . $name);
						} else {
							$explode        = explode('.', $name);
							$extension      = array_pop($explode);
							$filename       = implode('.', $explode);
							$filename       = str_replace(array('(', ')'), array('\\(', '\\)'), $filename);
							$last_file      = array_pop(
							$this->searchFiles(
                                    '' . $filename . ' (*).' . $extension . '',
							FMS_checkDirectoryString($main_directory, true, $this),
							false
							)
							);
							$regexp         = '/.*\((.*)\)\..*/i';
							$last_file_copy = (int) (preg_replace($regexp, '\1', $last_file) + 1);
							$newname        = stripslashes($filename . ' (' . $last_file_copy . ').' . $extension);

							move_uploaded_file($tmp_name, $main_directory . $newname);
						}
					} else {
						move_uploaded_file($tmp_name, $main_directory . $name);
						$t = array(
							'file_name'=>_db_string($name),
							'file_group_id'=>_db_string($post['group_id']),		
							'file_type'=>_db_string($_FILES[$fieldname]['type'][0]),		
							'file_path'=>_db_string($directory),		
							'file_title'=>_db_string($post['file_title']),		
							'file_description'=>_db_string($post['file_description']),		
						);
						$new_file_id  = _db_insert('files', $t);
						$t2 = array(
							'file_id'=>_db_string($new_file_id),
							'group_id'=>_db_string($post['group_id']),					
						);
						$new_group_id = _db_insert('files_in', $t2);
					}
				}
			}

			return true;
		}

		FMS_Error::raiseError(402);
	}

	/**
	 * Get source of a file
	 * If the file is a php file, it will be highlighted
	 *
	 * @param string $file      File
	 * @param string $directory Position
	 *
	 * @access public
	 * @uses highlightFile(string $file, [string $directory]);
	 * @return string
	 * @example
	 * <code>
	 * $fms = new File_Manager_System();
	 * echo $fms->highlightFile('foo.html');
	 * </code>
	 */
	public function highlightFile($file, $directory = null)
	{
		$main_directory = $this->_checkDirectory($directory);

		return highlight_file($main_directory . $file, true);
	}

	/**
	 * Search directories or files
	 *
	 * @param string $type      Files or directories
	 * @param string $query     Query
	 * @param string $directory Position
	 * @param bool   $complete  Search also in sub-directories
	 *
	 * @access private
	 * @return array
	 */
	private function _search($type, $query, $directory = null, $complete = true)
	{
		$main_directory = $this->_checkDirectory($directory);

		$regexpQuery = $query;
		$regexpQuery = str_replace('.', '\\.', $regexpQuery);
		$regexpQuery = str_replace('*', '.*', $regexpQuery);
		$regexp      = '/\b(' . $regexpQuery . ')\b$/';
		static $searchElements = array(
            'files' => array(),
            'directories' => array()
		);

		$excludedFiles       = $this->_excludedFiles;
		$excludedDirectories = $this->_excludedDirectories;
		$results             = scandir($main_directory);

		foreach ($results as $result) {
			if ($result != '' && $result != '.' && $result != '..') {
				if ($this->config['LIST_FILES'] &&
				(is_file($main_directory . $result) ||
				is_link($main_directory . $result))
				) {
					$resultFiles[] = $result;
				}
				if ($this->config['LIST_DIRECTORIES'] &&
				is_dir($main_directory . $result)
				) {
					$resultDirectories[] = $result;
				}
			}

		}
		$this->_exclude2($excludedFiles, $resultFiles, 'files');
		$this->_exclude2($excludedDirectories, $resultDirectories, 'dirs');

		foreach ($results as $result) {
			$fms_rep = 0;

			if ($result != '' && $result != '.' && $result != '..') {
				if ((is_file($main_directory . $result) ||
				is_link($main_directory . $result)) &&
				!in_array($result, $excludedFiles)
				) {
					if ($this->config['FILES_CASE_INSENSITIVE']) {
						$regexp .= 'i';
					}

					if (preg_match($regexp, $result)) {
						$searchElements['files'][] = $directory . '/' . $result;
					}
				}
				if (is_dir($main_directory . $result) &&
				!in_array($result, $excludedDirectories)
				) {
					if (substr($result, -1) == ')' OR substr($result, -1) == ']') {
						$fms_rep = 1;
						$result .= FMS_REPLACE;
					}

					if ($this->config['DIRECTORIES_CASE_INSENSITIVE']) {
						$regexp .= 'i';
					}

					if (preg_match($regexp, $result)) {
						if ($fms_rep == 1) {
							$result = str_replace('__File_Manager_System_REPLACE__', '', $result);
						}

						$searchElements['directories'][] = FMS_checkDirectoryString(
						$directory . '/' . $result
						);
					}

					if ($fms_rep == 1) {
						$result = str_replace('__File_Manager_System_REPLACE__', '', $result);
					}
					if ($complete) {
						$this->_search(
						$type,
						FMS_checkDirectoryString($directory . '/' . $result),
						$query,
						true
						);
					}
				}
			}
		}

		return $searchElements[$type];
	}

	/**
	 * Search files
	 *
	 * @param string $query     Query
	 * @param string $directory Position
	 * @param bool   $complete  Search also in sub-directories
	 *
	 * @access public
	 * @uses searchFiles(string $query, [string $directory, [bool $complete]])
	 * @return array
	 * @example This will search all "html" files in "foodir" directory and in all its subdirectories
	 * <code>
	 * $fms = new File_Manager_System();
	 * $search_results = $fms->searchFiles('*.html', 'foodir');
	 *
	 * echo '<pre>';
	 * print_r($search_results);
	 * echo '</pre>';
	 * </code>
	 *
	 * @example This will search all "html" files only in "foodir" directory
	 * <code>
	 * $fms = new File_Manager_System();
	 * $search_results = $fms->searchFiles('*.html', 'foodir', false);
	 *
	 * echo '<pre>';
	 * print_r($search_results);
	 * echo '</pre>';
	 * </code>
	 */
	public function searchFiles($query, $directory = null, $complete = true)
	{
		return $this->_search('files', $query, $directory, $complete);
	}

	/**
	 * Search searchDirectories
	 *
	 * @param string $query     Query
	 * @param string $directory Position
	 * @param bool   $complete  Search also in sub-directories
	 *
	 * @access public
	 * @uses searchDirectories(string $query, [string $directory, [bool $complete]])
	 * @return array
	 * @example This will search all directories that starts with "_" character in "foodir" directory and in all its subdirectories
	 * <code>
	 * $fms = new File_Manager_System();
	 * $search_results = $fms->searchDirectories('_*', 'foodir');
	 *
	 * echo '<pre>';
	 * print_r($search_results);
	 * echo '</pre>';
	 * </code>
	 *
	 * @example This will search all directories that starts with "_" character only in "foodir" directory
	 * <code>
	 * $fms = new File_Manager_System();
	 * $search_results = $fms->searchDirectories('_*', 'foodir', false);
	 *
	 * echo '<pre>';
	 * print_r($search_results);
	 * echo '</pre>';
	 * </code>
	 */
	public function searchDirectories($query, $directory = null, $complete = true)
	{
		return $this->_search('directories', $query, $directory, $complete);
	}

}

/**
 * Inline if
 *
 * @param bool $a     Control
 * @param bool $true  Return (true)
 * @param bool $false Return (false)
 *
 * @access public
 * @uses FMS_if(string $a, $true, [$false])
 * @return bool|string|array|int
 */
function FMS_if($a, $true, $false = null)
{
	return ($a ? $true : $false);
}

/**
 * Add slashes to a string
 *
 * @param string $string    String
 * @param bool   $beginning First slash
 * @param bool   $end       Last slash
 *
 * @access public
 * @uses FMS_addSlashes(string $string, [bool $beginning, [bool $end]])
 * @return string
 */
function FMS_addSlashes($string, $beginning = null, $end = null)
{	
	if (substr($string, -1) != '/' && $end == true) {
		$string .= '/';
	}
	if ($string{0} != '/' && $beginning == true) {
		$string = '/' . $string;
	}

	return $string;
}

/**
 * Add slashes to a string
 *
 * @param string $string    String
 * @param bool   $beginning First slash
 * @param bool   $end       Last slash
 *
 * @access public
 * @uses FMS_delSlashes(string $string, [bool $beginning, [bool $end]])
 * @return string
 */
function FMS_delSlashes($string, $beginning = null, $end = null)
{
	if (substr($string, -1) == '/' && $end == true) {
		$string = substr($string, 0, -1);
	}
	if ($string{0} == '/' && $beginning == true) {
		$string = substr($string, 1);
	}

	return $string;
}

/**
 * Replace directory string
 *
 * @param string $string       String
 * @param bool   $rep_root_dir Replace root directory
 * @param class  $fms          File_Manager_System class
 *
 * @access public
 * @uses FMS_checkDirectoryString(string $string, [bool $rep_root_dir, [bool $fms]]);
 * @return string
 */
function FMS_checkDirectoryString($string, $rep_root_dir = null, $fms = null)
{
	$string = FMS_addSlashes($string, false, true);
	
	if ($rep_root_dir == true && $fms == true) {
		$string = str_replace($fms->config['ROOT_DIRECTORY'], '/', $string);
	}

	$string = str_replace('//', '/', $string);

	return $string;
}

/**
 * Reduce multiple array to a single array
 *
 * @param array $array Array
 *
 * @access public
 * @uses FMS_arrayFlatten(array $array);
 * @return array
 */
function FMS_arrayFlatten($array)
{
	static $myArray = array();

	for ($i = 0; $i <= count($array); $i++) {
		if (is_array($array[$i])) {
			FMS_arrayFlatten($array[$i]);
		} else {
			$myArray[] = $array[$i];
		}
	}

	return $myArray;
}

/**
 * Enter description here...
 *
 * @param class $fms     File_Manager_System class
 * @param array $exclude Files or directories array
 * @param array $array   Exclude elements
 * @param type  $type    This value has to be "files" or "dirs"
 *
 * @access private
 * @return array
 */
function FMS_excludeElements($fms, $exclude, $array, $type = null)
{
	$newarray = array();
	$config   = $fms->config;

	$regexp = '/\b(';
	foreach ($exclude as $element) {
		if (eregi('\*', $element)) {
			$element = str_replace('.', '\\.', $element);
			$element = str_replace('*', '.*', $element);

			$regexp .= $element . '|';
		}
	}
	$regexp  = substr($regexp, 0, -1);
	$regexp .= ')\b$/';

	if ($type == 'files' && $config['FILES_CASE_INSENSITIVE']) {
		$regexp .= 'i';
	} elseif ($type == 'dirs' && $config['DIRECTORIES_CASE_INSENSITIVE']) {
		$regexp .= 'i';
	}

	if ($regexp != '/\b)\b$/i' && $array !== null) {
		foreach ($array as $string) {
			if (@preg_match($regexp, $string)) {
				if (!in_array($string, $newarray)) {
					$newarray[] = $string;
				}
			}
		}
	}

	return $newarray;
}

/**
 * Get users files groups
 *
 * @access public
 * @return array
 */
function files_list_access()
{
	return _db_get('SELECT `'.DB_PREFIX.'files_groups`.*  FROM `'.DB_PREFIX.'files_groups`,`'.DB_PREFIX.'user_menu_access` WHERE `'.DB_PREFIX.'user_menu_access`.user_id='.intval($_SESSION['cms_logged_user']['user_id']).' GROUP BY group_id ORDER BY group_name, group_id');
}

/**
 * Validate add group form
 *
 * @access public
 * @param array $tab input form values
 * @param array $T array contains text from language fiels
 * @return array
 */
function group_validate($tab,$T)
{
	global $GL_CONF;
	$res = array();
	if(trim($tab['group_name']) == '')
	$res['group_name'] = $T['group_name_error'];
	return $res;
}

/**
 * Update and insert new group
 *
 * @access public
 * @param array $tab group information
 * @return array
 */
function group_update($tab)
{
	global $GL_ACCESS_LVL;
	$t = array(
		'group_name'=>_db_string($tab['group_name']),
		'group_description'=>_db_string($tab['group_description']),		
	);
	if ($tab['group_id'] > 0) {
		$menu_check = array();
		$user_menu_access = group_get_menu_access($tab['group_id']);
		foreach ($user_menu_access as $key => $access) {
			if (!array_key_exists($access['menu_id'], $tab['allow_menu_access'])) {
				_db_query('DELETE from `'.DB_PREFIX.'files_groups_menu_access` WHERE menu_id='.intval($access['menu_id']).' and group_id='.intval($tab['group_id']));
			}
			$menu_check[$access['menu_id']] = 1;
		}

		if (is_array($tab['allow_menu_access'])) {
			foreach ($tab['allow_menu_access'] as $menu_id => $menu_access) {
				if (!array_key_exists($menu_id,$menu_check)) {
					$t_access = array(
					'group_id'=>$tab['group_id'],
					'menu_id'=>$menu_id,
					);
					_db_insert('files_groups_menu_access', $t_access);
				}
			}
		}
		return _db_update('files_groups', $t, 'group_id=' . intval($tab['group_id']));
	} else {
		$new_group_id  = _db_insert('files_groups', $t);
		return $new_group_id;
	}
}

function group_get($id)
{
	$res = _db_get_one('SELECT * FROM `' . DB_PREFIX . 'files_groups` WHERE group_id=' . intval($id));

	return $res;
}

function group_get_menu_access($id)
{
	return _db_get('SELECT menu_id FROM `' . DB_PREFIX . 'files_groups_menu_access` WHERE group_id=' . intval($id));
}

function group_delete($id) {
	return _db_delete('files_groups', 'group_id='.intval($id),1);

}

function group_files_list($group_id, $path, $limit = 0)
{
	$query = 'SELECT f.*, fi.file_name AS fa , fi.file_description AS fd FROM ' . DB_PREFIX . 'files f LEFT JOIN ' . DB_PREFIX . 'files_in fi ON (fi.group_id=' . intval($group_id) . ' AND fi.file_id=f.file_id) WHERE f.file_path="'.$path.'" AND f.file_id IN (SELECT file_id FROM ' . DB_PREFIX . 'files_in fg WHERE fg.group_id=' . intval($group_id).' ) OR (f.file_type="directory" AND f.file_path="'.$path.'") GROUP BY f.file_id ORDER BY fi.file_order  '.($limit > 0 ? ' LIMIT ' . intval($limit) : '');
	$res = _db_get($query);
	foreach ($res as $k => $V) {
		if (!empty($V['fa']) && trim($V['fa']) != '') {
			$res[$k]['file_title'] = $V['fa'];
			$res[$k]['file_name'] = $V['fa'];
		}
		if (!empty($V['fd']) && trim($V['fd']) != '') {
			$res[$k]['file_description'] = $V['fd'];
		}
	}
	return $res;
}

function group_files_reorder2($galleryId, $tab)
{
	$i = 1;
	foreach ($tab as $k => $V) {
		_db_update('files_in', array('file_order' => $i), 'file_id='.$V.' AND group_id='.$galleryId);
		$i++;
	}
	return true;
}

function group_files_reorder($oo, $no, $galleryId)
{

	return _db_reorder('files_in','file_order',$oo,$no,'group_id',$galleryId);
}

function editFile($tab)
{
	$t = array(
		'file_title'=>_db_string($tab['file_title']),
		'file_description'=>_db_string($tab['file_description'])		
	);
	if ($tab['file_id']>0) {
		$a = array(
       'file_name' => _db_string($tab['file_title']),
       'file_description'=>_db_string($tab['file_description'])
		);
		unset($t['file_title']);

		_db_update('files_in', $a, 'file_id='.intval($tab['file_id']) . ' AND group_id=' . intval($_GET['group_id']));
		//return _db_update('files', $t, 'file_id='.intval($tab['file_id']));
		return 1;
	}
}

function file_del($file_id, $type, $name, $directory='') {
	if($type=='directory')
	{
		if(File_Manager_System::deleteDirectory($name))
		{
			$sql = 'DELETE FROM ' . DB_PREFIX . 'files '
			. 'WHERE file_id =" ' . intval($file_id).'"';
			if(!_db_query($sql))
			{
				return true;
			}
		}
		else
		return false;
	}
	else
	{
		if($this->deleteFile($name, $directory))
		{
			$sql = 'DELETE FROM ' . DB_PREFIX . 'files '
			. 'WHERE file_id = ' . intval($file_id);
			if(!_db_query($sql))
			{
				$sql = 'DELETE FROM ' . DB_PREFIX . 'files_in'
				.'WHERE file_id = "' . intval($file_id).'"';
				//if(!_db_query($sql))
			}
		}
	}
	return true;
}

function group_list()
{
	global $GL_CONF;
	return _db_get(
		'SELECT *, (SELECT COUNT(*) FROM ' . DB_PREFIX . 'gallery_pic WHERE group_id = g.group_id) AS "count" ' .
		'FROM `'.DB_PREFIX.'files_groups` g ' .
		'ORDER BY group_id');
}

function group_files($group_id, $limit = 0)
{
	$sql = 'SELECT *, fg.file_name AS fa, fg.file_description AS fn  FROM ' . DB_PREFIX . 'files_in fg INNER JOIN ' . DB_PREFIX . 'files f ON f.file_id=fg.file_id INNER JOIN '.DB_PREFIX.'files_groups gr ON fg.group_id=gr.group_id WHERE fg.group_id = ' . intval($group_id).' AND f.file_type!="directory" GROUP BY f.file_id ORDER BY fg.file_order '.($limit > 0 ? ' LIMIT ' . intval($limit) : '');
	$res = _db_get($sql);
	foreach ($res as $k => $V) {
		if (!empty($V['fa']) && trim($V['fa']) != '') {
			$res[$k]['file_title'] = $V['fa'];
		}
		$res[$k]['file_description'] = $V['fn'];
		$res[$k]['file_ext'] = substr($V['file_name'], strrpos($V['file_name'], '.'), strlen($V['file_name']));
	}
	return $res;
}

function group_files_all($limit)
{
	$sql = 'SELECT *, fg.file_name AS fa FROM ' . DB_PREFIX . 'files_in fg INNER JOIN ' . DB_PREFIX . 'files f ON f.file_id=fg.file_id INNER JOIN '.DB_PREFIX.'files_groups gr ON fg.group_id=gr.group_id WHERE f.file_type!="directory" GROUP BY f.file_id '.($limit > 0 ? ' LIMIT ' . intval($limit) : '');
	$res = _db_get($sql);
	foreach ($res as $k => $V) {
		if (!empty($V['fa']) && trim($V['fa']) != '') {
			$res[$k]['file_title'] = $V['fa'];
		}
	}
	return $res;
}

function group_file_add($tab)
{
	$t = array(
		'file_id'=>_db_string($tab['file_id']),
		'group_id'=>_db_string($tab['group_id']),
		'file_order'=>_db_int(_db_new_order('files_in','file_order','group_id',$tab['group_id']))
	);
	return _db_insert('files_in', $t);
}

function _ftp_get($id, $type='')
{
	$sql = 'SELECT * FROM `'.DB_PREFIX.'mod_ftp` WHERE module_id='.intval($id).($type != '' ? ' AND item_type="'.$type.'"' : '');
	$res = _db_get($sql);
	return $res;
}

function get_all_files()
{
	return _db_get('SELECT * FROM ' . DB_PREFIX . 'files WHERE file_type != "directory" ');
}

function get_file_info($id, $group_id = null)
{
	$sql = 'SELECT * FROM ' . DB_PREFIX . 'files_in WHERE file_id='.$id;
	$res = _db_get($sql);
	$group = (int) $group_id;
	if ($group == 0) {
		$group = (int) $_GET['group_id'];
	}
	if ($group > 0) {
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'files_in WHERE group_id = ' . $group . ' AND file_id='.$id;
		$X = _db_get($sql);
		if (count($X) > 0) {
			if (trim($X[0]['file_name']) != '') {
				$res[0]['file_title'] = $X[0]['file_name'];
			}
			if (trim($X[0]['file_description']) != '') {
				$res[0]['file_description'] = $X[0]['file_description'];
			}
		}
	}
	$res[0][$k]['file_ext'] = substr($X[0]['file_name'], strrpos($X[0]['file_name'], '.'), strlen($X[0]['file_name']));
	return $res;
}

function checkFileinGroup($group_id)
{
	$sql = 'SELECT * FROM '.DB_PREFIX.'mod_ftp '.' m INNER JOIN '.DB_PREFIX.'files_in '.' fi ON fi.file_id = m.item_id WHERE m.item_type = "f" AND fi.group_id=m.group_id AND fi.group_id="'.$group_id.'"';
	return _db_get($sql);
}
