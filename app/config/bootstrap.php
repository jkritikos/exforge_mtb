<?php
/* SVN FILE: $Id$ */
/**
 * Short description for file.
 *
 * Long description for file
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @version       $Revision$
 * @modifiedby    $LastChangedBy$
 * @lastmodified  $Date$
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 *
 * This file is loaded automatically by the app/webroot/index.php file after the core bootstrap.php is loaded
 * This is an application wide file to load any function that is not used within a class define.
 * You can also use this to include or require any files in your application.
 *
 */
/**
 * The settings below can be used to set additional paths to models, views and controllers.
 * This is related to Ticket #470 (https://trac.cakephp.org/ticket/470)
 *
 * $modelPaths = array('full path to models', 'second full path to models', 'etc...');
 * $viewPaths = array('this path to views', 'second full path to views', 'etc...');
 * $controllerPaths = array('this path to controllers', 'second full path to controllers', 'etc...');
 *
 */
//EOF
define("NOTIFICATION_TYPE_SCORE", 1);
define("NOTIFICATION_TYPE_NEW_FRIEND_JOIN", 2);

//dev
define("PUSH_USERNAME", "QcPHp0gxT3-3yj5Y9aLDpA");
define("PUSH_PASSWORD", "qK_-SzSeQP6NA_UQ8g-ENw");

//prod
//define("PUSH_USERNAME", "W1NHMmPjR56aHc3u6nu6iA");
//define("PUSH_PASSWORD", "0louzaRKRLmStWwb0qEHjw");

define("PUSH_SUCCESS", 200);

//IPAD dev
define("PUSH_IPAD_USERNAME", "apVjXPfWQaqwLUb9ZeglhA");
define("PUSH_IPAD_PASSWORD", "zQRRhDxoQtGRr-V0EoRFmw");

define("AES_KEY", "57622f526916f52becfa91db932acb0cbc2c0046fb30fc41ab3502d5ac3b6d08");
define("AES_IV", "bba6707d3f018b645166b47fd9c5e83b");
define("AES_SALT", "8fce695ec4fea76e");

//languages
define("LANG_GREEK", "1");
define("LANG_ENGLISH", "2");

//APPLICATION_TYPE_IDS
define("APP_IPHONE", 1);
define("APP_IPAD", 2);

//SECURITY ACTIONS
define("SECURITY_ACTION_BLACKLIST_APP", 1);
define("SECURITY_ACTION_FORCE_UPDATE", 2);

define("LATEST_IPHONE_VERSION", "1.2");
define("LATEST_IPHONE_VERSION_MANDATORY", false);

?>