<?php

App::import('Sanitize');

class SiteController extends AppController {
    var $components = array('Security','Cookie', 'Captcha', 'RequestHandler');
    var $helpers = array('Javascript');
    
    function index(){
        $this->layout = 'site';
    }
    
}

?>