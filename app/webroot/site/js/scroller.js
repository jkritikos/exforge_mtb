var aesthetica = [];

function scrollbarInit(name, marginLeft, marginRight, marginMiddle){
	
	var containerWidth = 0;
	$('.'+name+'-content-item').each(function(){
		$(this).css('margin-left', marginMiddle + 'px');
		containerWidth = containerWidth + $(this).width() + marginMiddle;
	});
	
	$('.'+name+'-content-item').first().css('margin-left', marginLeft + 'px');
	$('.'+name+'-content-item').last().css('margin-right', marginRight + 'px');
	
	containerWidth = containerWidth + marginLeft + marginRight - marginMiddle;
	$('.'+name+'-content').width(containerWidth);
	
	aesthetica[name] = {
		lock: false,
		minScrollBarLeft: 5,
		minContentLeft: 0,
		pageX: null
	};
	aesthetica[name].maxScrollBarLeft = ($('.'+name+'-pane').width() - aesthetica[name].minScrollBarLeft - $('#'+name+'-bar').width());
	aesthetica[name].maxContentLeft = (containerWidth - $('.'+name+'-pane').width());
	aesthetica[name].multiplier = aesthetica[name].maxContentLeft / aesthetica[name].maxScrollBarLeft;
	var wrapperPos =  $('.'+name+'-pane').position();
	aesthetica[name].wrapperLeft = wrapperPos.left;
	aesthetica[name].wrapperRight = wrapperPos.left + $('.'+name+'-pane').width();
	
	$('#'+name+'-bar').mousedown(function(event){		
		aesthetica[name].lock = true;
		$('body').css('cursor', 'pointer');
		event.preventDefault();
		return false;
	});
	
	$(document).mousemove(function(event){
		if(aesthetica[name].lock){
			if(aesthetica[name].pageX != null){
				if(event.pageX > aesthetica[name].wrapperRight - 35){
					$('#'+name+'-bar').css('left', aesthetica[name].maxScrollBarLeft + 'px');
					$('.'+name+'-content').css('left', (-1) * aesthetica[name].maxContentLeft + 'px');
				}else if(event.pageX < aesthetica[name].wrapperLeft + 35){
					$('#'+name+'-bar').css('left', aesthetica[name].minScrollBarLeft + 'px');
					$('.'+name+'-content').css('left', (-1) * aesthetica[name].minContentLeft + 'px');					
				}else{
					var newLeft = $('#'+name+'-bar').css('left').replace('px', '') * 1  + (event.pageX - aesthetica[name].pageX);
					if(newLeft > aesthetica[name].maxScrollBarLeft){
						$('#'+name+'-bar').css('left', aesthetica[name].maxScrollBarLeft + 'px');
					$('.'+name+'-content').css('left', (-1) * aesthetica[name].maxContentLeft + 'px');
					}else if(newLeft < aesthetica[name].minScrollBarLeft){
						$('#'+name+'-bar').css('left', aesthetica[name].minScrollBarLeft + 'px');
					$('.'+name+'-content').css('left', (-1) * aesthetica[name].minContentLeft + 'px');
					}else{
						$('#'+name+'-bar').css('left', newLeft + 'px');
					$('.'+name+'-content').css('left', (-1)*((newLeft * aesthetica[name].multiplier)) + 'px');
					}
				}
			}
			aesthetica[name].pageX = event.pageX;
		}
	});
	
	$(document).mouseup(function(){
		if(aesthetica[name].lock){
			$('body').css('cursor', '');
			aesthetica[name].lock = false;
			aesthetica[name].pageX = null;
		}
	});	
};

$(document).ready(function() {
	//scrollbarInit('clients', 5, 5, 30);
	scrollbarInit('quotes', 20, 20, 60);
});