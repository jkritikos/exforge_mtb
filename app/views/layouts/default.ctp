<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<link rel="shortcut icon" type="image/x-icon" href="/img/favicon.ico"/>
<title>Exforge - <?php echo $title_for_layout?></title>

<link rel="stylesheet" media="all" href="/css/reset.css" />
<link rel="stylesheet" media="all" href="/css/style.css" />
<link rel="stylesheet" media="all" href="/css/messages.css" />
<link rel="stylesheet" media="all" href="/css/uniform.aristo.css" />
<link rel="stylesheet" media="all" href="/css/forms.css" />
<link rel="stylesheet" media="all" href="/css/tables.css" />
<link rel="stylesheet" media="all" href="/css/jquery.gritter.css" />
<link rel="stylesheet" media="all" href="/css/jquery.autocomplete.css" />
<link rel="stylesheet" media="all" href="/css/visualize.css" />


<script src="/js/html5.js"></script>
<!-- jquerytools -->
<script src="/js/jquery.tools.min.js"></script>
<script src="/js/visualize.jQuery.js"></script>
<script src="/js/jquery.uniform.min.js"></script>
<script src="/js/jquery.tables.js"></script>
<script type="text/javascript" src="/js/aes.js"></script>
<!--[if lt IE 9]>
<link rel="stylesheet" media="all" href="/css/ie.css" />
<script type="text/javascript" src="/js/selectivizr.js"></script>
<script type="text/javascript" src="/js/ie.js"></script>
<![endif]-->
<!--[if IE 8]>
<link rel="stylesheet" media="all" href="/css/ie8.css" />
<![endif]-->

<script type="text/javascript" src="/js/jquery.autocomplete.js"></script>
<script type="text/javascript" src="/js/global.js"></script>
<script type="text/javascript" src="/js/jquery.gritter.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $(".flag").click(function(){
        var lang = $(this).attr('id');
        $.post('/questions/language', {language:lang},function(data){
            location.reload(true);
        });
    });
    
    var key = CryptoJS.enc.Hex.parse('<?php echo AES_KEY; ?>');
    var iv  = CryptoJS.enc.Hex.parse('<?php echo AES_IV; ?>');
   
    <?php
    if(isset($notification)){
        ?>
	$.gritter.add({
            title: 'Done!',
            text: '<?php echo $notification; ?>'
	});
	<?php
    } else if(isset($errorMsg)){
        ?>
	$.gritter.add({
            title: 'Error!',
            text: '<?php echo $errorMsg; ?>'
	});
	<?php
    }
    ?>

    $(":date").dateinput({
	// this is displayed to the user
	format: 'dd/mm/yyyy'
});

    $("#tagSearch").autocomplete("/questions/getTags/", {onItemSelect:selectTag,delay:10,minChars:2});

    $("#tagdialog").overlay({
        // custom top position
        top: 260,
        // some mask tweaks suitable for facebox-looking dialogs
        mask: {
            // you might also consider a "transparent" color for the mask
            color: '#fff',
            // load mask a little faster
            loadSpeed: 200,
            // very transparent
            opacity: 0.5
        },

        // disable this for modal dialog-type of overlays
        closeOnClick: false,

        // load it immediately after the construction
        load: false
    });

    var api = $("#tagdialog").data("overlay");

    //tag select callback
    function selectTag(li){
        // if coming from an AJAX call, let's use the idd as the value
	if( !!li.extra ) {
            var sValue = li.extra[0];
            var sTitle = $("#tagSearch").val();
            var title = 'Ερωτήσεις σχετικές με: '+sTitle;
            $("#tagDialogTitle").html(title);
            
            var options = {
                url: "/questions/getTaggedQuestions",
                type: "POST",
                dataType: "json",
                data: ({t : sValue}),
                success: function(d){                    
                    var ajaxData = '';
                    
                    $.each(d, function (i) {
                        var qId = this.id;
                        var qName = this.question;
                        
                        encrypted = decodeURI(qName);
                        var decrypted = CryptoJS.AES.decrypt(encrypted, key, { iv: iv });      
                        var decryptedText = (decrypted.toString(CryptoJS.enc.Utf8));
                        
                        var editLink = '/questions/edit/'+qId;
                        ajaxData += '<li><a href='+editLink+'>' + decryptedText+'</a>';
                    });

                    $("#taggedQuestions").html(ajaxData);
                    // select the overlay element - and "make it an overlay"
                    api.load();
                }
            };

            $.ajax(options);            
        }        
    }

});
</script>
</head>
<body>

    <div id="wrapper">
        <?php echo $this->element('header'); ?>

        <?php echo $content_for_layout ?>

        <div class="clear">&nbsp;</div>
        <div class="clear">&nbsp;</div>
        <div class="clear">&nbsp;</div>
        
        <!-- simple dialog -->
        <div class="widget modal" id="tagdialog">
            <header><h2 id="tagDialogTitle"></h2></header>
            <section>
                <p id="taggedQuestions">                    
                </p>


                <!-- yes/no buttons -->
                <p>
                    <button class="button button-blue close"> Close </button>
                </p>
            </section>
        </div>

    </div>

    <?php echo $this->element('footer'); ?>
    
</body>
</html>