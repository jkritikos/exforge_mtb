<?php
/* SVN FILE: $Id: app_controller.php 7945 2008-12-19 02:16:01Z gwoo $ */
/**
 * Short description for file.
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.controller
 * @since         CakePHP(tm) v 0.2.9
 * @version       $Revision: 7945 $
 * @modifiedby    $LastChangedBy: gwoo $
 * @lastmodified  $Date: 2008-12-18 20:16:01 -0600 (Thu, 18 Dec 2008) $
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 * This is a placeholder class.
 * Create the same file in app/app_controller.php
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       cake
 * @subpackage    cake.cake.libs.controller
 */
App::import('l10n');
class AppController extends Controller {

    /*Executed before all functions*/
    function beforeFilter() {

	parent::beforeFilter();

	/*
	//load appropriate locale
	$this->L10n = new L10n();
	$language = $this->Session->read('language');
   	$this->L10n->get($language);

   	//init cookie
 	$this->Cookie->name = 'veladiaCookie';
	$this->Cookie->key = 'hts1^je73@qmk02q!';
	*/

    }

    /*Renders the login screen (with cookie data)*/
    function requireLogin($redirectUrl){
	$this->set('redirectUrl', $redirectUrl);
	$this->set('email', $this->Cookie->read('email'));
	$this->set('password', $this->Cookie->read('password'));
	$this->layout = 'blank';
	$this->render('/users/login');
    }

    function noAccess(){
        $this->layout = 'blank';
	$this->render('/users/rights');
    }
}
?>