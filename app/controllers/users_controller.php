<?php

App::import('Core', 'l10n', 'Sanitize');

class UsersController extends AppController {
    var $components = array('Security','Cookie', 'Captcha');

    /*Executed before all functions*/
    function beforeFilter() {

        parent::beforeFilter();
	$this->set('headerTitle', "User Management");
	$this->set('activeTab', "users");
        
        //Force SSL on all actions
        $this->Security->blackHoleCallback = 'forceSSL';
        $this->Security->validatePost = false;
        $this->Security->requireSecure();
    }
    
    function forceSSL() {
        /*
        if(Configure::read('debug') == '0'){
            $this->redirect('https://' . env('SERVER_NAME') . $this->here);
        }*/
    }

    function captcha_image(){        
        $this->layout = 'blank';
        $this->Captcha->image();
    } 

    function index(){
        $currentUser = $this->Session->read('userID');
	if($currentUser != null){

	} else {
            $this->requireLogin('/users/index');
	}
    }

    function search(){
        $currentUser = $this->Session->read('userID');
	if($currentUser != null){
            $this->set('headerTitle', "Search users");

            if (!empty($this->data)){
                $fname = $this->data['User']['fname'];
		$lname = $this->data['User']['lname'];
		$email = $this->data['User']['email'];

		$data = $this->User->search($fname,$lname,$email);
		$this->set('results', $data);
            }

	} else {
            $this->requireLogin('/users/search');
	}
    }

    function create(){
	$currentUser = $this->Session->read('userID');
	if($currentUser != null){
            $this->set('headerTitle', "Create user");

            if (!empty($this->data)){
                $this->data['User']['password'] = Security::hash($this->data['User']['password'], 'md5');
		if($this->User->save($this->data)){
                    $this->set('notification', 'New user successfully created.');
		} else {
                    $this->set('notification', 'Unable to create the new user - please try again.');
		}
            }

	} else {
            $this->requireLogin('/users/create');
	}
    }

    function edit($id){
        $currentUser = $this->Session->read('userID');
	if($currentUser != null){
            $this->set('headerTitle', "Edit user");
            $this->set('targetUserId', $id);

            if (!empty($this->data)){
                if($this->User->save($this->data)){
                    $this->set('notification', 'User details updated successfully.');
		} else {
                    $this->set('errorMsg', 'Unable to update the new details - please try again.');
		}
            }

            $userObj = $this->User->findbyid($id);
            if($userObj != null){
                $this->set('user', $userObj);

		//update session data
		$this->Session->write('fname', $userObj['User']['fname']);
		$this->Session->write('lname', $userObj['User']['lname']);
            }

	} else {
            $this->requireLogin('/users/edit/$id');
	}
    }

    function login(){
	$this->layout = 'blank';

	if (!empty($this->data)){

            $email = $this->data['User']['email'];
            $password = $this->data['User']['password'];
            $userObj = $this->User->validateAdminCredentials($email, $password);
            $userId = $userObj['User']['id'];

            $userCaptcha = $this->data['User']['captcha'];
            $captchaOK = $this->Captcha->check($userCaptcha);
                        
            if($captchaOK == "1" && $userId != null){
                $this->Session->write('userID', $userId);
		$this->Session->write('fname', $userObj['User']['fname']);
		$this->Session->write('lname', $userObj['User']['lname']);
                $this->Session->write('role', $userObj['User']['role_id']);
                
                //init content language to greek
                $this->Session->write('content_language', 1);

		//Redirect to home or wherever was initially requested
		if(!empty($this->data['User']['redirectUrl'])){
                    $redirectUrl = $this->data['User']['redirectUrl'];
		} else {
                    $redirectUrl = "/questions/create";
		}

		//Set cookie if required
		if(isset($this->data['User']['remember_me']) && $this->data['User']['remember_me'] == '1'){
                    $this->Cookie->write('email', $userObj['User']['email'], false, '+1 week');
		} else {
                    $this->Cookie->del('email');
		}

		//Redirect
                $this->log("Users->login() redirecting to $redirectUrl", LOG_DEBUG);
                $this->redirect($redirectUrl);
            } else {
                if($captchaOK == "1"){
                    $this->set('errorMsg', 'Invalid username/password');
                } else {
                    $this->set('errorMsg', 'Invalid CAPTCHA');
                }
            }
	} else {
            //Provide login data from cookie to the view
            $this->set('email', $this->Cookie->read('email'));
	}
    }

    function resetPassword(){
	$this->layout = 'blank';

	if (!empty($this->data)){
            $email = $this->data['User']['email'];
            $currentUser = $this->User->findAllByEmail($email);

            //if the user is found
            if($currentUser != null){
		$this->set('notificationMsg', "Your new password has been sent to $email");
            } else {
		$this->set('errorMsg', 'No user found with this email address');
            }
	}

    }

    /*Destroy session and redirect to / */
    function logout(){
        $this->Session->destroy();
	$this->redirect('/');
    }

    function profile(){
	$currentUser = $this->Session->read('userID');
	if($currentUser != null){
            $this->set('headerTitle', "My Profile");

            if (!empty($this->data)){
                $this->data['User']['password'] = Security::hash($this->data['User']['password'], 'md5');
		if($this->User->save($this->data)){
                    $this->set('notification', 'Your personal details have been successfully updated.');
                } else {
                    $this->set('notification', 'Unable to update your personal details - please try again.');
		}
            }

            $userObj = $this->User->findbyid($currentUser);
            $this->set('user', $userObj);

            //update session data
            $this->Session->write('fname', $userObj['User']['fname']);
            $this->Session->write('lname', $userObj['User']['lname']);

	} else {
            $this->requireLogin('/users/profile');
	}
    }
}

?>