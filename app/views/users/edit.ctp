<?php

$activeSelected = "";
$deactiveSelected = "";
$developerSelected = "";
$managerSelected = "";
$externalSelected = "";
$internalSelected = "";

if(isset($user)){
    if($user['User']['active'] == '1'){
	$activeSelected = "selected";
	$deactiveSelected = "";
    } else {
	$deactiveSelected = "selected";
	$activeSelected = "";
    }

    if($user['User']['role_id'] == '1'){
        $developerSelected = "selected";
        $managerSelected = "";
    } else if($user['User']['role_id'] == '2'){
        $developerSelected = "";
        $managerSelected = "selected";
    }

    if($user['User']['external'] == '0'){
        $internalSelected = "selected";
        $externalSelected = "";
    } else if($user['User']['role_id'] == '1'){
        $internalSelected = "";
        $externalSelected = "selected";
    }
}

?>

<script type="text/javascript" src="/js/jquery.rules.js"></script>
<script>
$(document).ready(function(){
    $("#form").validator({    	
    	position: 'left',
    	offset: [25, 10],
    	messageClass:'form-error',
    	message: '<div><em/></div>' // em element is the arrow
    }).submit(function(e) {
    	
    	if (!e.isDefaultPrevented()) {
            $('#loader').show();
    	}    	
    });
});
</script>

<section id="content">
    <div class="wrapper">

    <!-- Left column/section -->

    <section class="grid_6 first">

<div class="columns">
	<div class="grid_6 first">
		<?php 
		$action = "/users/edit/$targetUserId";
		?>
	    <form id="form" class="form panel" method="post" action="<?php echo $action; ?>">
	    <input type="hidden" name="data[User][id]" value="<?php echo $targetUserId; ?>" />	    
		<header><h2>Use the following fields to update the user details:</h2></header>

		<hr />
		<fieldset>
		    <dl>
			<dt></dt><dd><label>First name</label><input value="<?php echo $user['User']['fname']; ?>" type="text" name="data[User][fname]" required="required" /></dd>
			<dt></dt><dd><label>Last name</label><input value="<?php echo $user['User']['lname']; ?>" type="text" name="data[User][lname]" required="required" /></dd>
			<dt></dt><dd><label>Role *</label>
                        <select name="data[User][role_id]">                            
                            <option <?php echo $developerSelected; ?> value="1">Developer</option>
                            <option <?php echo $managerSelected; ?> value="2">Manager</option>
			</select>
                        </dd>
                        <dt></dt><dd><label>External employee *</label>
                        <select name="data[User][external]">
                            <option <?php echo $internalSelected; ?> value="0">No</option>
                            <option <?php echo $externalSelected; ?> value="1">Yes</option>
			</select>
                        </dd>
                        <dt></dt><dd><label>Email</label><input value="<?php echo $user['User']['email']; ?>" type="text" name="data[User][email]" required="required" /></dd>
			<dt></dt><dd><label>Status</label>
			<select name="data[User][active]">
				<option <?php echo $activeSelected; ?> value="1">Active</option>
				<option <?php echo $deactiveSelected; ?> value="0">Deactive</option>
			</select>
			</dd>
			</dl>
		</fieldset>

		<hr />
		<button class="button button-green" type="submit">Update</button>
		<button class="button button-gray" type="reset">Reset</button>
		<img id="loader" style="display:none;position:absolute;" src="/img/ajax-loader.gif" />
	    </form>
	</div>
    </div>
    
    <div class="clear">&nbsp;</div>    
    </section>
    
    <!-- End of Left column/section -->    
    <!-- Right column/section -->
    
    <aside class="grid_2">    				
    <div class="widget">    
        <header>
            <h2>Options</h2>
    	</header>    
    	<section>
    
    	<dl>				    				                                    				    
            <dd><img src="/img/fam/user_add.png" />&nbsp;<a href="/users/create">Create new user</a></dd>
            <dd><img src="/img/fam/search.png" />&nbsp;<a href="/users/search">Search users</a></dd>
            <dd><img src="/img/fam/user_edit.png" />&nbsp;<a href="/users/profile">My profile</a></dd>
    	</dl>
    
    	</section>    						    
    </div>    
    </aside>
    
    <!-- End of Right column/section -->
    <div class="clear"></div>    
</div>
<div id="push"></div>
</section>