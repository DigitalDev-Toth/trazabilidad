/**
  * Toth context menu 
  * -------
  * Context menu right click plugin for jQuery
  * Created by:
  * - Toth (http://www.toth.cl)
  * - DigitalDev (http://www.digitaldev.org)	
  * © Copyright 2015
  */
(function ($) {    
    $.fn.tothcontextmenu = function (object) { 
        if (typeof object === 'object') {
            $('#tcm').remove();
            var tothcontextmenuDiv = $('<div id="tcm"></div>');                
            tothcontextmenuDiv.css({
                'min-width': '200px',
                'display': 'block',
                'position': 'absolute',
                'top': '0px',
                'left': '0px',
                'background': '#FFF',
                'color': '#000',
                'border': '1px solid #CCC',
                'border-radius': '15px',
                'padding': '14px',
                '-webkit-box-shadow': '0 5px 10px rgba(0, 0, 0, .2)',
                'box-shadow': '0 5px 10px rgba(0, 0, 0, .2)'
            });
            return this.on('mousedown', function (event) {
                if (event.which === 3) {
                    var content = '<table>';  
                    for (var i in object) {
                        content += '<tr>';
                        content += '<td>';
                        content += '<div style="width: 100%;" class="input-group">';
                        content += '<span class="input-group-addon">';
                        if (MODE === parseInt(i)) {
                            content += '<input id="'+ i +'" type="radio" name="optradio" onclick="radioClick('+ i +');" checked="checked">';
                        } else {
                            content += '<input id="'+ i +'" type="radio" name="optradio" onclick="radioClick('+ i +');">';
                        }                  
                        content += '</span>';
                        content += '<span class="form-control">'+ object[i].name +'</span>';
                        content += '</div>';
                        content += '</td>';
                        content += '</tr>';
                    }                
                    content += '</table>';
                    tothcontextmenuDiv.html(content);
                    $('body').append(tothcontextmenuDiv);
                    if (((event.clientX + tothcontextmenuDiv.width() + (parseInt(tothcontextmenuDiv.css('padding')) * 2)) > $(window).width()) && ((event.clientY + tothcontextmenuDiv.height() + (parseInt(tothcontextmenuDiv.css('padding')) * 2)) < $(window).height())) {
                        tothcontextmenuDiv.css({
                            'left': event.clientX - 20 - tothcontextmenuDiv.width() - (parseInt(tothcontextmenuDiv.css('padding')) * 2),
                            'top': event.clientY + 20
                        });
                    } else if (((event.clientX + tothcontextmenuDiv.width() + (parseInt(tothcontextmenuDiv.css('padding')) * 2)) < $(window).width()) && ((event.clientY + tothcontextmenuDiv.height() + (parseInt(tothcontextmenuDiv.css('padding')) * 2)) > $(window).height())) {
                        tothcontextmenuDiv.css({
                            'left': event.clientX + 20,
                            'top': event.clientY - 20 - tothcontextmenuDiv.height() - (parseInt(tothcontextmenuDiv.css('padding')) * 2)
                        });
                    } else if (((event.clientX + tothcontextmenuDiv.width() + (parseInt(tothcontextmenuDiv.css('padding')) * 2)) > $(window).width()) && ((event.clientY + tothcontextmenuDiv.height() + (parseInt(tothcontextmenuDiv.css('padding')) * 2)) > $(window).height())) {
                        tothcontextmenuDiv.css({
                            'left': event.clientX - 20 - tothcontextmenuDiv.width() - (parseInt(tothcontextmenuDiv.css('padding')) * 2),
                            'top': event.clientY - 20 - tothcontextmenuDiv.height() - (parseInt(tothcontextmenuDiv.css('padding')) * 2)
                        });
                    } else {
                        tothcontextmenuDiv.css({
                            'left': event.clientX + 20,
                            'top': event.clientY + 20
                        });
                    }    
                } else if (event.which === 1 || event.which === 2) {
                    if (event.target.localName === 'svg' || event.target.localName === 'window') {
                        $('#tcm').remove();
                    } 
                }                  
            });                                                       
        } else if (typeof object === 'string') {
            if (object === 'hide') {
                $('#tcm').remove();
                this.unbind();
            }            
        }          
    };
})(jQuery);
function radioClick (id) {
    MODE = id;
    repaintViewport ();
}