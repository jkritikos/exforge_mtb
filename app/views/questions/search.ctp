<script type="text/javascript" src="/js/jquery.form.js"></script>
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

    <div class="columns leading">
        <div class="grid_6 first">

            <form id="form" class="form panel" method="post" action="/questions/search" novalidate>
		<header><h2>Συμπληρώστε την παρακάτω φόρμα για να αναζητήσετε ερωτήσεις:</h2></header>

		<hr />
		<fieldset>
		    <div class="clearfix">
                        <label>Κατηγορία *</label>
                        <select id="workTypeSelector" name="data[Question][category_id]">
                        <option value="">Επιλέξτε</option>
                        <?php
                        foreach($categories as $k => $v){
                            ?>
                            <option value="<?php echo $k ?>"><?php echo $v ?></option>
                            <?php
                        }
                        ?>
                        </select>
                    </div>
                    <div class="clearfix">
                        <label>Ερώτηση *</label>
                        <input size="40" type="text" name="data[Question][question]"/>
                    </div>                    
                    <div class="clearfix">
                        <label>Πόντοι</label>
                        <select name="data[Question][value]">
                            <option value="">Επιλέξτε</option>
                            <option value="100">Εύκολη (100)</option>
                            <option value="200">Μεσαία (200)</option>
                            <option value="300">Δύσκολη (300)</option>
                        </select>
                    </div>
                    <div class="clearfix">
                        <label>Αφορά Γλώσσα</label>
                        <select name="data[Question][language_id]">
                            <option value="">Επιλέξτε</option>
                            <option value="-1">Όλες</option>
                            <option value="1">Ελληνικά</option>
                            <option value="2">Αγγλικά</option>
                        </select>
                    </div>
                    <div class="clearfix">
                        <label>Χρήστης</label>
                        <select name="data[Question][user_id]">
                            <option value="">Όλοι</option>
                            <?php
                            foreach($users as $u){
                                ?>
                                <option value="<?php echo $u['User']['id']; ?>"><?php echo $u['User']['fname'] . " " . $u['User']['lname']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="clearfix">
                        <label>Tag</label>
                        <input size="40" type="text" name="data[Question][tag]"/>
                    </div>
		</fieldset>

		<hr />
		<button class="button button-green" type="submit">Αναζήτηση</button>
		<button class="button button-gray" type="reset">Καθαρισμός</button>
		<img id="loader" style="display:none;position:absolute;" src="/img/ajax-loader.gif" />
	    </form>

	 </div>
    </div>

    <div class="clear">&nbsp;</div>
</section>
<!-- End of Left column/section -->
<!-- Right column/section -->

<?php echo $this->element('menu_question'); ?>
 <!-- End of Right column/section -->

</div>
</section>