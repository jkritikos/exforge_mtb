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

	    <form id="form" class="form panel" method="post" action="/users/create">
	    <input type="hidden" name="data[User][activated]" value="1" />
		<header><h2>Use the following fields to update the user details:</h2></header>

		<hr />
		<fieldset>
                    <div class="clearfix">
                        <label>First Name *</label>
                        <input type="text" name="data[User][fname]" required="required" />
                    </div>
                    <div class="clearfix">
                        <label>Last Name *</label>
                        <input type="text" name="data[User][lname]" required="required" />
                    </div>
                    <div class="clearfix">
                        <label>Role *</label>
                        <select name="data[User][role_id]">
                            <option value="1">Content Editor</option>
                            <option value="2">Admin</option>
			</select>
                    </div>
                    <div class="clearfix">
                        <label>Email *</label>
                        <input type="text" name="data[User][email]" required="required" />
                    </div>
                    <div class="clearfix">
                        <label>Password *</label>
                        <input type="password" id="password" name="data[User][password]" required="required"/>
                    </div>
                    <div class="clearfix">
                        <label>Password confirmation *</label>
                        <input type="password" name="check" data-equals="password" />
                    </div>
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