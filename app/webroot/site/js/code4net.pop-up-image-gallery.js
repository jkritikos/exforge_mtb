/*
 *  Code4Net.pop-up-image-gallery.js is jQuery plugin created in order 
 *  to simplify creation of pop-up galleries for web
 *  
 *  Copyright 2011 Milan Jovanovic
 *  Licensed under the GPL Version 2 licenses <http://www.gnu.org/licenses/>
 *
 *  Release date: Mon Oct 31 2011
 *
*/
(function($){
    var methods = {
      init: function(options){
          return this.each(function(){
                var scope = $(this), 
                    _c4n = scope.data('code4netpopup');
                    
                _c4n = {
                    'scrollSpeed' : 'slow',         //slow/fast
                    'thumbsDir' : 'images/gallery/thumbs',
                    'originalsDir' : 'images/gallery/originals',
                    'maskColor' : '#000000',
                    'maskOpacity' : 0.7,
                    'idBase': 'code4net-popup',
                    
                    'beforeRenderFn' : false,
                    'renderFn' : false,         //overrides render
                    'afterRenderFn' : false,
                    
                    'beforeHideFn' : false,
                    'hideFn' : false,           //overrides hide
                    'afterHideFn' : false,
                    
                    'maskInitFn' : false,       //overrides windowInit
                    'afterMaskInitFn' : false,
                    
                    'windowInitFn' : false,     //overrides windowInit
                    'afterWindowInitFn' : false                                        
                };   
                
                
                
                var internal = {
                    'isLocked' : false,
                    'current' : null,
                    'wrapper' : null,
                    'window' : null,
                    'prevBtn' : null,
                    'nextBtn' : null,
                    'closeBtn' : null,
                    'loader' : null,
                    'content' : null,
                    'img' : null,
                    'cache' : []
                };
                
                if(options)
                    $.extend(_c4n, options);
                $.extend(_c4n, internal);     
                
                /**************************************************************/
                /* OVERRIDE                                                   */
                /**************************************************************/
                var execute = function(overideFn){                    
                    scope.data('code4netpopup', _c4n);
                    overideFn.call(scope);
                    _c4n = scope.data('code4netpopup');                    
                }
                
                /**************************************************************/
                /* ON RENDER                                                  */
                /**************************************************************/
                var onRender = function(thumb){
                    if(!_c4n.isLocked){
                        _c4n.current = thumb;
                        beforeRender();
                        render();
                    }
                };
                
                var beforeRender = function(){
                    _c4n.isLocked = true;
                    if(_c4n.beforeRenderFn)
                        execute(_c4n.beforeRenderFn);
                };
                
                var render = function(){
                    if(_c4n.renderFn)
                        execute(_c4n.renderFn);
                    else{  
                        if(_c4n.wrapper.css('display') == 'none'){
                            _c4n.content.hide();//*1
                            showWindow(showLoader);
                        } else
                            showLoader();
                    }
                };
                
                var renderNavigation = function(){                    
                    _c4n.prevBtn.unbind('click');
                    _c4n.nextBtn.unbind('click');

                    if(_c4n.current.prev().length > 0){
                        _c4n.prevBtn.toggleClass(_c4n.idBase+'-nav-prev', true);
                        _c4n.prevBtn.click(function(){onRender(_c4n.current.prev())});
                    }else{                            
                        _c4n.prevBtn.toggleClass(_c4n.idBase+'-nav-prev', false);
                    }

                    if(_c4n.current.next().length > 0){
                        _c4n.nextBtn.toggleClass(_c4n.idBase+'-nav-next', true);
                        _c4n.nextBtn.click(function(){onRender(_c4n.current.next())});
                    }else{
                        _c4n.nextBtn.toggleClass(_c4n.idBase+'-nav-next', false);
                    }
                    afretRender();
                };
                
                var afretRender = function(){                    
                    if(_c4n.afterRenderFn)
                        execute(_c4n.afterRenderFn);
                    _c4n.isLocked = false;
                };
                
                /**************************************************************/
                /* ON HIDE                                                    */
                /**************************************************************/
                var onHide = function(event){
                    if($(event.target).attr('id') == $(this).attr('id')){
                        beforeHide();
                        hide();
                    }
                };
                
                var beforeHide = function(){
                    if(_c4n.beforeHideFn)
                        execute(_c4n.beforeHideFn);
                };
                
                var hide = function(){
                    if(_c4n.hideFn)
                        execute(_c4n.hideFn);
                    else
                        hideWindow();
                };
                
                var afterHide = function(){
                    if(_c4n.afterHideFn)
                        execute(_c4n.afterHideFn);
                    _c4n.isLocked = false;
                };
                
                /**************************************************************/
                /* LOADER                                                     */
                /**************************************************************/
                var showLoader = function(){
                    var imgPath = _c4n.current.find('img').attr('src');
                    if($.inArray(imgPath, _c4n.cache) < 0){
                        _c4n.cache.push(imgPath);
                        _c4n.loader.show();
                    }
                    _c4n.content.fadeOut(_c4n.scrollSpeed, //*1
                        function(){
                            imgPath = imgPath.replace(_c4n.thumbsDir, _c4n.originalsDir);
                            _c4n.img.attr('src', imgPath);
                            _c4n.img.attr('alt', _c4n.current.find('img').attr('alt'));
                            renderNavigation();
                    });
                };
                
                var hideLoader = function(){
                    _c4n.content.fadeIn(_c4n.scrollSpeed);//*1
                    _c4n.loader.hide();
                };
                
                /**************************************************************/
                /* MASK                                                       */
                /**************************************************************/                
                var afterMaskInit = function(){
                    if(_c4n.afterMaskInitFn)
                        execute(_c4n.afterMaskInitFn);
                };
                
                var maskInit = function(){
                    if(_c4n.maskInitFn)
                        execute(_c4n.maskInitFn);
                    else{
                        if($('#' + _c4n.idBase+'-mask').length == 0){
                            var mask = '<div id="'+_c4n.idBase+'-mask" style="background-color:'+ _c4n.maskColor +';"></div>';
                            $('body').append(mask);
                        }
                        _c4n.mask = $('#' + _c4n.idBase+'-mask');
                    }
                    afterMaskInit();
                }();
                
                var showMask = function(){
                    _c4n.mask.unbind('click');
                    _c4n.mask.bind('click', onHide);
                    _c4n.mask.fadeTo('fast', _c4n.maskOpacity, showWindow);
                };
                
                var hideMask = function(){
                    _c4n.mask.unbind('click');
                    _c4n.mask.fadeOut('fast');
                };
                
                /**************************************************************/
                /* WINDOW                                                     */
                /**************************************************************/
                var afterWindowInit = function(){
                    if(_c4n.afterWindowInitFn)
                        execute(_c4n.afterWindowInitFn);
                };
                
                var windowInit = function(){
                    if(_c4n.windowInitFn)
                        execute(_c4n.windowInitFn);
                    else{
                        if($('#' + _c4n.idBase+'-window').length == 0){
                            var win =   '<table id="'+_c4n.idBase+'-wrapper" border="1" cellpadding="0" cellspacing="0">' +
                                            '<tr>' +
                                                '<td id="'+_c4n.idBase+'-wrapper-cell" >' +
                                                    '<div id="'+_c4n.idBase+'-window" style="zoom: 1;*display:inline;">' +
                                                        '<div id="'+_c4n.idBase+'-nav-prev" class="'+_c4n.idBase+'-nav-prev-empty"></div>' +
                                                        '<div id="'+_c4n.idBase+'-close"></div>' +
                                                        '<div id="'+_c4n.idBase+'-loader"></div>' +
                                                        '<div id="'+_c4n.idBase+'-content" style="zoom: 1;*display:inline;">' +
                                                            '<img id="'+_c4n.idBase+'-img" src="" alt="" />' +
                                                        '</div>' +
                                                        '<div id="'+_c4n.idBase+'-nav-next" class="'+_c4n.idBase+'-nav-next-empty"></div>' +
                                                    '</div>' +
                                                '</td>' +
                                            '</tr>' +
                                        '</table>' 
                                        ;
                             $('body').append(win);
                        }
                        _c4n.wrapper = $('#' + _c4n.idBase+'-wrapper');
                        _c4n.wrapperCell = $('#' + _c4n.idBase+'-wrapper-cell');
                        _c4n.window = $('#' + _c4n.idBase+'-window');
                        _c4n.prevBtn = _c4n.window.find('#' + _c4n.idBase+'-nav-prev');
                        _c4n.nextBtn = _c4n.window.find('#' + _c4n.idBase+'-nav-next');
                        _c4n.closeBtn = _c4n.window.find('#' + _c4n.idBase+'-close');
                        _c4n.loader = _c4n.window.find('#' + _c4n.idBase+'-loader');
                        _c4n.content = _c4n.window.find('#' + _c4n.idBase+'-content');
                        _c4n.img = _c4n.window.find('#' + _c4n.idBase+'-img');
                        _c4n.img.load(hideLoader);
                    }
                    afterWindowInit();
                }();
                
                var showWindow = function(callback){
                    _c4n.mask.unbind('click');
                    _c4n.mask.bind('click', onHide);
                    
                    _c4n.wrapperCell.unbind('click');
                    _c4n.wrapperCell.bind('click', onHide);
                    
                    _c4n.closeBtn.unbind('click');
                    _c4n.closeBtn.bind('click', onHide);
                    
                    _c4n.mask.fadeTo('fast', _c4n.maskOpacity);
                    
                    if(callback)                        
                        _c4n.wrapper.fadeIn(_c4n.scrollSpeed, callback);
                    else
                        _c4n.wrapper.fadeIn(_c4n.scrollSpeed);
                };
                
                var hideWindow = function(){
                    _c4n.mask.unbind('click');
                    _c4n.wrapper.unbind('click');
                    _c4n.window.unbind('click');
                    _c4n.closeBtn.unbind('click');
                    _c4n.wrapper.fadeOut(_c4n.scrollSpeed, hideMask);
                };
                
                /**************************************************************/
                /* INIT                                                       */
                /**************************************************************/
                scope.children().each(function(){
                   $(this).click(function(){onRender($(this))});
                });
          });
      }
    };
    
    $.fn.code4netpopup = function(method) {    
        if ( methods[method] ) {
          return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
          return methods.init.apply( this, arguments );
        } else {
          $.error( 'Method ' +  method + ' does not exist on jQuery.code4netpopup' );
        }      
  };
})( jQuery );