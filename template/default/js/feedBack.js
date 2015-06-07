//var _feedBackUrl = "freedBackIframe.html";   
if(!cid){
		cid = 20;
}

var _feedBackUrl = "/gather.php?cid=" + cid + "&page=" + encodeURIComponent(window.location.href);
function extend() {
				 var options, name, src, copy, copyIsArray, clone,
					target = arguments[0] || {},
					i = 1,
					length = arguments.length,
					deep = false;
			 
				// Handle a deep copy situation
				if ( typeof target === "boolean" ) {
					deep = target;
					target = arguments[1] || {};
					// skip the boolean and the target
					i = 2;
				}
			 
				// Handle case when target is a string or something (possible in deep copy)
				if ( typeof target !== "object" && !jQuery.isFunction(target) ) {
					target = {};
				}
			 
				// extend jQuery itself if only one argument is passed
				if ( length === i ) {
					target = this;
					--i;
				}
			 
				for ( ; i < length; i++ ) {
					// Only deal with non-null/undefined values
					if ( (options = arguments[ i ]) != null ) {
						// Extend the base object
						for ( name in options ) {
							src = target[ name ];
							copy = options[ name ];
			 
							// Prevent never-ending loop
							if ( target === copy ) {
								continue;
							}
			 
							// Recurse if we're merging plain objects or arrays
							if ( deep && copy && ( jQuery.isPlainObject(copy) || (copyIsArray = jQuery.isArray(copy)) ) ) {
								if ( copyIsArray ) {
									copyIsArray = false;
									clone = src && jQuery.isArray(src) ? src : [];
			 
								} else {
									clone = src && jQuery.isPlainObject(src) ? src : {};
								}
			 
								// Never move original objects, clone them
								target[ name ] = jQuery.extend( deep, clone, copy );
			 
							// Don't bring in undefined values
							} else if ( copy !== undefined ) {
								target[ name ] = copy;
							}
						}
					}
				}
			 
				// Return the modified object
				return target;
			};

		(function() {
	var util = {
		mix: extend
	}; // 基础的一些方法
	var usePM = ( typeof window.postMessage !== 'undefined'); // 是否支持 postMessage
	var PM = function( win ) {
		this._win = win;
		this._event = function(){};
		this._initialize();
	};
	util.mix( PM.prototype, {
		_initialize: function() { // 初始化
			var me = this;
			if ( usePM ) {
				if( window.addEventListener ) {
					window.addEventListener('message', function(e){
					me._event( e.data);
				});
				} else {
					window.attachEvent('onmessage', function(e){
						me._event(e.data);
					});
				}
			} else {
				var lastName = window.name;
				setInterval( function() {
					if( window.name != lastName && window.name != '' ) {
						lastName = window.name;
						me._event(lastName);
					}
				}, 50);
			}
		},
		onmessage: function(callback) { // 添加 onmessage 方法
			this._event = callback;
		},
		send: function( data, origin ) { // send 方法
			var wl = window.location;
			var sendOrigin = wl.href.substr( 0, wl.href.indexOf( wl.pathname ) + 1 );
			var sendData = {
				data: data,
				ts: (+(new Date)).toString(10),
				origin: sendOrigin
			}
 
			if( usePM ) {
				this._win.postMessage( data, origin || '*' );
			} else {
				this._win.name = data;
			}
		}
	});
	window.XPM = PM;
})();
			var f = window;
			var PM = new XPM(f);
			PM.onmessage(function(e){
				if(e == "close"){
					document.getElementById("feedBackWrap").style.display="none";
					var ifrm = document.getElementById("feedBackIframe");
					ifrm.setAttribute("src",_feedBackUrl);
				}else if(!isNaN(parseInt(e))){
					var _height  = parseInt(e);
					document.getElementById("feedBackWrap").style.height = _height + 16 + "px";
					document.getElementById("feedBackMask").style.height = _height + 16 + "px";
					document.getElementById("feedBackIframe").style.height = _height + "px";
				}
			});

			function divcenter(){ 
				document.getElementById("feedBackWrap").style.height = 400 + 16 + "px";
				document.getElementById("feedBackMask").style.height = 400 + 16 + "px";
				document.getElementById("feedBackIframe").style.height = 400 + "px";
				var ifrm = document.getElementById("feedBackIframe");
				ifrm.setAttribute("src",_feedBackUrl);

				document.getElementById("feedBackWrap").style.display="block";
				document.getElementById("feedBackWrap").src=_feedBackUrl;
				var _top = document.documentElement.scrollTop || document.body.scrollTop;
				var _left = document.documentElement.scrollLeft || document.body.scrollLeft;
				var divId=document.getElementById('feedBackWrap');   
				divId.style.left=(document.documentElement.clientWidth-divId.clientWidth)/2+ _left + "px";   
				divId.style.top=(document.documentElement.clientHeight-divId.clientHeight)/2+ _top + "px"; 
			};
		
		(function(){
			var _wrap = document.createElement("div");
			_wrap.setAttribute("id","feedBackWrap");
			_wrap.style.position = "absolute";
			_wrap.style.top = "0px";
			_wrap.style.left= "0px";
			_wrap.style.display= "none";
			_wrap.style.width= "516px";
			_wrap.style.height= "416px";
			_wrap.style.zIndex= 100;
			//_wrap.setAttribute("style","position:absolute;top:0px;left:0px;width:516px;height:416px;display:none;");
			var _mask = document.createElement("div");
			_mask.setAttribute("id","feedBackMask");
			_mask.style.position = "absolute";
			_mask.style.top = "0px";
			_mask.style.left= "0px";
			_mask.style.width= "516px";
			_mask.style.height= "416px";
			_mask.style.background = "black";
			//_mask.setAttribute("style","position:absolute;top:0px;left:0px;width:516px;height:416px;z-index:1;background:black;");
			_mask.style.opacity="0.2";
			_mask.style.filter="alpha(opacity=20)";
			var ifrm = document.createElement("iframe");
			ifrm.setAttribute("id","feedBackIframe");
			//ifrm.setAttribute("src","freedBackIframe.html");
			ifrm.setAttribute("frameborder","no");
			ifrm.setAttribute("border","0");
			ifrm.setAttribute("marginwidth","0");
			ifrm.setAttribute("marginheight","0");
			ifrm.setAttribute("scrolling","0");
			ifrm.setAttribute("allowtransparency","0");
			ifrm.style.position = "absolute";
			ifrm.style.top = "8px";
			ifrm.style.left= "8px";
			ifrm.style.width= "500px";
			ifrm.style.height= "400px";
			//ifrm.setAttribute("style","position:absolute;top:8px;left:8px;width:500px;height:400px;z-index:2;");
			_wrap.appendChild(_mask);
			_wrap.appendChild(ifrm);
			document.body.appendChild(_wrap);
})();