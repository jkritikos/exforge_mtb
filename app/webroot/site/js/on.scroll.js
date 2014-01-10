/********************************************************/
/*      ON.SCROLL - CONFIG                              */
/********************************************************/

var menuClass = 'menu';
var activeMenuItemClass = 'menu-active';
var sectionAnchorClass = 'section';
var visibleFromBottom = 100; //how m

/********************************************************/

var scrollLock = false;
var sections = [];
var sectionsCount = 0;

function setActiveMenuItem(itemId){
	$('.'+ menuClass +' a').toggleClass(activeMenuItemClass, false);
		$('.' + menuClass).find('a[href="#' + itemId + '"]').toggleClass(activeMenuItemClass, true);
};

var Section = function(_id, _top){
	this.id = _id;
	this.top = Math.round(_top); //px
	this.select = function(){
		setActiveMenuItem(this.id);
	};
};


var markMenu = (function() {
	if(!scrollLock){
		var position = $(window).scrollTop();		
		
		if(position == 0){
			setActiveMenuItem(sections[0].id);
			return true;
		}
		
		var i = 0;		
		while(i < sectionsCount-1 && ((sections[i+1].top - position) < ($(window).height() - visibleFromBottom)) )
		{
			i++;
		}
		
		setActiveMenuItem(sections[i].id);
	}
});
$(document).ready(function() {
	
	var anchors = $('.' + sectionAnchorClass);
	sectionsCount = anchors.length;
	var position = 0;
	for(i=0; i<sectionsCount; i++){
		position = $(anchors[i]).offset();
		sections[i] = new Section($(anchors[i]).attr('id'),  position.top);
	}
	
	markMenu();
	$(window).scroll(function() {markMenu();});
	
	$('.menu a').each(function(){
		$(this).click(function(event){
			event.preventDefault();
			scrollLock = true;
			var itemId = ($(this).attr('href')).replace('#', '');
			var position = $('div#' + itemId).position();
			$('html,body').animate(
				{ scrollTop: position.top+'px' },
				2000, 
				function() {
					scrollLock = false;
					setActiveMenuItem(itemId);
				}
			);
		});
	});
});