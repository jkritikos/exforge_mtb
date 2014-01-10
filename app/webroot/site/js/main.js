
$(document).ready(function() {
    $('.header-mask').fadeTo(0, 0.9);
    $(window).load(function(){
        $('.doc-loader').fadeOut('slow', function(){
            $('.gap').find('img').each(function(){
                $(this).attr('src', $(this).attr('alt'));
                $(this).load(function(){
                   $(this).fadeIn('slow'); 
                });
            });
        });
    });
       
    $('.gallery').code4netslideshow({
        'navigation' : [
            {
                'id' : 'gallery-nav',
                'type' : 'bullet'           //bullet/arrow
            },
            {
                'id' : 'galery-nav-arrows',
                'type' : 'arrow'           //bullet/arrow
            }
        ],
        'slideInterval' : 5,
        'pauseInterval' : 10, 
        'type' : 'horizontal',
        'autoSlide' : true
    });

    $('.portfolio').code4netslideshow({
        'navigation' : [
            {
                'id' : 'portfolio-nav',
                'type' : 'arrow'           //bullet/arrow
            }
        ],
        'slideInterval' : 20,
        'pauseInterval' : 15, 
        'type' : 'horizontal',
        'autoSlide' : true
    });
    
    $('.portfolio-slide').each(function(){
        $(this).code4netpopup({
            'thumbsDir': 'images/portfolio/thumbs',
            'originalsDir': 'images/portfolio/originals',
            'afterWindowInitFn' : function(){
                var scope = $(this),
                    _c4n = scope.data('code4netpopup');
                
                if(_c4n.content.find('.portfolio-frame-title').length == 0){    
                    //_c4n.content.append('<div class="portfolio-frame-title"></div>');
                }
                _c4n.imgTitle = _c4n.content.find('.portfolio-frame-title');
                
                if(_c4n.content.find('.portfolio-frame-text').length == 0){    
                    //_c4n.content.append('<div class="portfolio-frame-text"></div>');
                }
                //_c4n.imgText = _c4n.content.find('.portfolio-frame-text');
            },
            'afterRenderFn' : function(){
                var scope = $(this),
                    _c4n = scope.data('code4netpopup');
                
                //_c4n.imgTitle.html(_c4n.current.find('p').html());
                //_c4n.imgText.html(_c4n.current.find('span').html());
            }
        });        
    });
        
    $('.portfolio-image').mouseenter(function() {$(this).find('.portfolio-text').fadeTo('slow', 0.9);});
    $('.portfolio-image').mouseleave(function() {$(this).find('.portfolio-text').fadeOut('slow');});
    
    $('#view-portfolio').click(function(){
        $('#portfolio-link').click();
    });
    $('#logo').click(function(){
        $('#take-me-home-link').click();
    });
});