<?php

/**
 * File Manager System Error Class
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

/**
 * File Manager System Error class
 * 
 * @author    Jacopo Andrea Nuzzi <jacoz@php.net>
 * @copyright 2007-2008 Jacopo Andrea Nuzzi
 * @license   http://www.opensource.org/licenses/bsd-license.php  New BSD
 * @link      http://app.jaydns.net/FMS/
 * @access    public
 */
class FMS_Error
{
    
    static private $_errno;
    
    static private $_errors = array(
        0 => 'Unknown error',
        101 => 'Nie ma takiego katalogu',
        102 => 'Invalid directory',
        103 => 'Not writable directory',
        201 => 'File does not exist',
        202 => 'File or directory does not exist',
        301 => 'Element is not an array',
        401 => 'Operation failed! Chmod 0777 directory',
        402 => 'Directory does not exist or directory is not writable',
        403 => 'Specify a new directory',
    );
    
    /**
     * Checks if error exists
     *
     * @param int $errno Error ID
     * 
     * @access private
     * @return bool
     */
    private static function _isError($errno)
    {
        return (array_key_exists($errno, self::$_errors) ? true : false);
    }
    
    /**
     * Raise an error (if exists)
     *
     * @param int $errno Error ID
     * 
     * @access public
     * @uses raiseError($errno)
     * @return string
     */
    public static function raiseError($errno = null)
    {
        $errno = ($errno ? $errno : 0);
        $error = self::$_errors[$errno];
        
        if (self::_isError($errno)) {
            echo '<br /><strong>Wystąpił błąd</strong>: ';
            echo $error;
            die;
        }
    }
    
}
