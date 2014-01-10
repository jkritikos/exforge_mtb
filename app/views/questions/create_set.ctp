<script type="text/javascript" src="/js/jquery.rules.js"></script>
<script>
$(document).ready(function(){
			           
    $("#form").validator({    	
    	position: 'left',
    	offset: [25, 10],
    	messageClass:'form-error',
    	message: '<div><em/></div>' // em element is the arrow
    }).submit(function(e) {
        var form = $(this);
        
    	//client-side passed
    	if (!e.isDefaultPrevented()) {
            $.post("/questions/validateQuestionSet", { question_set: $("#questionSet").val()},function(json) {
                
                if(json.data === true){
                    $('#loader').show();
                    document.getElementById('form').submit();
                } else {
                    form.data("validator").invalidate(json.data);
                }
            }, "json");
            
            e.preventDefault();
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

                    <form id="form" class="form panel" method="post" action="/questions/createSet" novalidate>
                        <header><h2>Use the following fields to create a new Question set:</h2></header>

                        <hr />
                        <fieldset>
                            <div class="clearfix">
                                <label>Name *</label>
                                <input id="questionSet" type="text" name="data[QuestionSet][name]" required="required" />
                            </div>
                            <div class="clearfix">
                                <label>Description *</label>
                                <input id="description" type="text" name="data[QuestionSet][description]" required="required" />
                            </div>
                            <div class="clearfix">
                                <label>Price *</label>
                                <input id="price" type="number" name="data[QuestionSet][price]" required="required" />
                            </div>
                            <div class="clearfix">
                                <label>Active *</label>
                                <select name="data[QuestionSet][active]" required="required" />
                                    <option value="">Please select</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                            <div class="clearfix">
                                <label>Language *</label>
                                <select name="data[QuestionSet][language_id]" required="required" />
                                    <option value="">Please select</option>
                                    <option value="1">Greek</option>
                                    <option value="2">English</option>
                                </select>
                            </div>
                        </fieldset>

                        <hr />
                        <button class="button button-green" type="submit">Create</button>
                        <button class="button button-gray" type="reset">Reset</button>
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
    <div class="clear"></div>
</div>
</section>