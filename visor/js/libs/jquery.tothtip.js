  /* Tooltip Tothtip - plugins for jQuery
 * --------  
 * Created by:
 * - Toth (http://www.toth.cl)
 * - DigitalDev (http://www.digitaldev.org)	
 *
 * © Copyright 2014
 */
(function ($) {
    $.fn.extend({
        tothtip: function (content, options) {
            var div = $('<div></div>');
            div.html(content);
            div.css({
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
            return this.on('mouseover', function () {
                $('body').append(div);
                $(this).on('mousemove', function (event) {
                    if (((event.clientX + div.width() + (parseInt(div.css('padding')) * 2)) > $(window).width()) && ((event.clientY + div.height() + (parseInt(div.css('padding')) * 2)) < $(window).height())) {
                        div.css({
                            'left': event.clientX - 20 - div.width() - (parseInt(div.css('padding')) * 2),
                            'top': event.clientY + 20
                        });
                    } else if (((event.clientX + div.width() + (parseInt(div.css('padding')) * 2)) < $(window).width()) && ((event.clientY + div.height() + (parseInt(div.css('padding')) * 2)) > $(window).height())) {
                        div.css({
                            'left': event.clientX + 20,
                            'top': event.clientY - 20 - div.height() - (parseInt(div.css('padding')) * 2)
                        });
                    } else if (((event.clientX + div.width() + (parseInt(div.css('padding')) * 2)) > $(window).width()) && ((event.clientY + div.height() + (parseInt(div.css('padding')) * 2)) > $(window).height())) {
                        div.css({
                            'left': event.clientX - 20 - div.width() - (parseInt(div.css('padding')) * 2),
                            'top': event.clientY - 20 - div.height() - (parseInt(div.css('padding')) * 2)
                        });
                    } else {
                        div.css({
                            'left': event.clientX + 20,
                            'top': event.clientY + 20
                        });
                    }                    
                });                
            }).on('mouseout', function () {
                div.remove();
            });
        }
    });
})(jQuery);