<?php

class User extends AppModel {
    var $name = 'User';

    function search($fname,$lname,$email){
        $sql = "select u.fname, u.lname, u.email, u.id, u.created from users u where 1=1 ";

	if($fname != ''){
            $sql .= " and u.fname like '%$fname%' ";
	}

	if($lname != ''){
            $sql .= " and u.lname like '%$lname%' ";
	}

	if($email != ''){
            $sql .= " and u.email = $email ";
	}

	$rs = $this->query($sql);

	$data = array();
	if(is_array($rs)){
            foreach($rs as $i => $values){
                $fname = $rs[$i]['u']['fname'];
		$lname = $rs[$i]['u']['lname'];
		$id = $rs[$i]['u']['id'];
		$email = $rs[$i]['u']['email'];
		$created = $rs[$i]['u']['created'];
		$obj['User']['fname'] = $fname;
		$obj['User']['lname'] = $lname;
		$obj['User']['email'] = $email;
		$obj['User']['created'] = $created;
		$obj['User']['id'] = $id;

		$data[] = $obj;
            }
	}

	$this->log("User->search() returns ".count($data), LOG_DEBUG);
	return $data;
    }

    /*Checks whether the specified email/password combination is valid.
    Returns the user id on success, null otherwise. Used by the API*/
    function validateAdminCredentials($email, $password){
	$this->log("User->validateCredentials() called with email $email and password $password", LOG_DEBUG);
	$result = null;

	//try to login
	$currentUser = $this->findAllByEmail($email);
	if($currentUser != null){
            //if the account is active
            if($currentUser[0]['User']['active'] == '1'){
                $userHash = Security::hash($password, 'md5');

		//and the password is a match
		if($currentUser[0]['User']['password'] == $userHash){
                    $result = $currentUser[0];
		} else {
                    $result = null;
		}
            }
	} else {
            $result = null;
	}

	$this->log("User->validateCredentials() returns $result", LOG_DEBUG);
	return $result;
    }
}

?>