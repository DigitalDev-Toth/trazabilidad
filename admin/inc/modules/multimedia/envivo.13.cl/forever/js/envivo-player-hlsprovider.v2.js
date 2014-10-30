/*
FlowPlayer Init Versión 1.0
*/
$(document).ready(function() {
	if(prerollVideoUrl != null){
		try{
			$f.addPlugin("prerollButton", function() {
				var preroll = this.getPlugin("preroll");
				if(preroll){
					this.onLoad(function() {
						setTimeout(function(){preroll.show();},35000);
					});
				}
				return this;
			});
			$f("preroll", {
					src:"forever/swf/flowplayer.commercial-3.2.7.swf",
					wmode: 'opaque'
				}, {
				key: '#@733891725da2e9a7514',
				clip: {
					autoPlay: true,
					url: prerollVideoUrl,
					linkUrl: (prerollClickUrl != undefined ? prerollClickUrl : ''),
					linkWindow: "_blank",
					onFinish: initFPlayer,
					scaling: 'fit'
				},
				plugins: {
					controls: {
						scrubber: false,
						autoHide: false,
						fullscreen: false,
						width: 230,
						backgroundGradient: 'none',
						backgroundColor: 'rgba(0, 0, 0, 0.35)',
						borderRadius: 10,
						volumeColor: '#fc6700',
						tooltips: {
							buttons: true,
							play: 'Reproducir',
							pause: 'Detener',
							mute: 'Silencio',
							unmute: 'Activar Sonido'
						}
					},
					preroll: {
						top: 0,
						right: 10,
						backgroundColor: '#000000',
						width: 72,
						height: 20,
						border: 0,
						padding: 0,
						opacity: 1,
						url: 'forever/swf/flowplayer.content-3.2.0.swf',
						html: '<a href="javascript:initFPlayer();">Saltar Video</a>',
						display: 'none'
					}
				},
				autoBuffering: true,
				onError: initFPlayer,
				canvas: {
					backgroundGradient: 'none'
				},
				onUnload: function(){$("#preroll").remove();}
			}).load().prerollButton();
		}catch(e){}
	} else {
		$("#preroll").remove();
		initFPlayer();
	}
	setInterval(function(){
		try{
			var d = new Date();
			var fin = parseInt(($(".live").next().children(".time").html()).replace(':', ''));
			var ahora = parseInt(d.getHours()+''+d.getMinutes());
			if(ahora >= fin){
				$(".live").next().addClass("live");
				$(".live").prev().removeAttr("class");
			}
		}
		catch(e){}
	},60000);
});
function initFPlayer(){
	setTimeout(function(){
		try{
			if(typeof($f("preroll")) === "object"){
				$f("preroll").unload();
			}
		}catch(e){}
		try{
			$("#player").css('display','block');
			$f.addPlugin("videoBanner", function() {
				var bannerInterval, bannerIntervalCounter = 1, content = this.getPlugin("content");
				if(content && (adImageUrl != null)){
					this.onLoad(function() {
						setTimeout(function(){
							content.setHtml('<a href="'+adClickUrl+'" target="_blank"><img src="'+adImageUrl+'"/></a>');
							content.show();
							setTimeout(function(){content.hide();},8000);
							bannerInterval = setInterval(function(){
								if(bannerIntervalCounter++ < 7){
									content.show();
									setTimeout(function(){content.hide();},8000);
								} else clearInterval(bannerInterval);
							},360000);
						},7000);
					});
				}
				return this;
			});
			$f("player", {
					src:"forever/swf/flowplayer.commercial-3.2.16.swf",
					wmode: 'opaque'
				}, {
				key: '#@733891725da2e9a7514',
				clip: {
					autoPlay: true,
					live: true,
					provider: 'httpstreaming',
					urlResolvers: ["httpstreaming","brselect"],
					scaling: 'fit',
					url: 'http://live.hls.http.13.ztreaming.com/13hddesktop/13hd-desktopc3vW.m3u8'
				},
				play: {color: '#fc6700'},
				//stopping default pause action on click
				onBeforePause: function(){
					console.log('stopping default pause action on click...');
					return false;
				},
				plugins: {
					httpstreaming: {
						url: '/forever/swf/HLSProviderFlowPlayer.swf'
					},			
					controls: {
						width: 170,
						scrubber: false,
						time: false,
						play: false,
						backgroundGradient: 'none',
						backgroundColor: 'rgba(0, 0, 0, 0.35)',
						borderRadius: 10,
						volumeColor: '#fc6700',
						tooltips: {
							buttons: true,
							play: 'Reproducir',
							// pause: 'Detener',
							mute: 'Silencio',
							unmute: 'Activar Sonido',
							fullscreen: 'Pantalla Completa',
							fullscreenExit: 'Salir de Pantalla Completa'
						}
					},
					content: {
						bottom: 15,
						backgroundColor: 'transparent',
						backgroundGradient: 'none',
						width: 420,
						height: 80,
						border: 0,
						padding: 0,
						opacity: 1,
						url: 'forever/swf/flowplayer.content-3.2.0.swf',
						closeButton:true,
						closeImage: 'forever/images/close.png',
						display: 'none'
					}
				},
				canvas: {
					backgroundGradient: 'none'
				}
			}).load().videoBanner();
		}catch(e){}
	},1000);
}