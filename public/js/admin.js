/*******************************************************************************
* Copyright (C) 2006 - 2010 jisucms.com
* @author 809 <271513820@qq.com>
* @version 2.0.0 (2010-12-05)
* ep:
$(document).ready(function(){
	$jisucms.show.table();
});
*******************************************************************************/
var $jisucms = {};
$jisucms.version = '2.0';
//表单提交验证
$jisucms.form = ({
	//$("#"+$id).val().match(/([w]){2,12}$/) 2-12位数字字母组合	  
	empty : function($formname,$id){
		if(!$("#"+$id).val()){
			$("#"+$id).focus();
			$("#"+$id).css("border-color","#FF0000");
			$("#"+$id+"_error").html($('#'+$id).attr('error'));//html>><input ... error='not empty'></input><span id="$id_error">error</span>
			return false;
		}else{
			return true;
		}
	},
	repwd : function($formname,$id,$reid){
		if($("#"+$id).val()){
			if($("#"+$id).val() != $("#"+$reid).val()){
				$("#"+$id).focus();
				$("#"+$id).css("border-color","#FF0000");
				$("#"+$reid+"_error").html($('#'+$reid).attr('error'));
				return false;
			}else{
				return true;
			}
		}
	}	
});
//表单提交
function post($url){
	$('#myform').attr('action',$url);
	$('#myform').submit();
}
//全选与反选
function checkall($all){
	if($all){
		$("input[name='ids[]']").each(function(){
				this.checked = true;
		});		
	}else{
		$("input[name='ids[]']").each(function(){
			if(this.checked == false)
				this.checked = true;
			else
			   this.checked = false;
		});		
	}
}
//tab切换
function showtab(mid,no,n){
    for(var i=0;i<=n;i++){
        $('#'+mid+i).hide();
    }
    $('#'+mid+no).show();
}
//分页跳转
function pagego($url,$total){
	$page=document.getElementById('page').value;
	if($page>0&&($page<=$total)){
		$url=$url.replace('{!page!}',$page);
		location.href=$url;
	}
	return false;
}
// 图片预览
function showpic(event,imgsrc,path){	
	var left = event.clientX+document.body.scrollLeft+20;
	var top = event.clientY+document.body.scrollTop+20;
	$("#showpic").css({left:left,top:top,display:""});
	if(imgsrc.indexOf('://')<0){
		imgsrc = path+imgsrc;
	}
	$("#showpic_img").attr("src",imgsrc);
}
// 取消图片预览
function hiddenpic(){
	$("#showpic").css({display:"none"});
}
//设置星级
function setstars(mid, id, stars){
	$.get('?s=admin-'+mid+'-ajaxstars-id-'+id+'-stars-'+stars, function(obj){
		if(obj == 'ok'){
			for(i=1; i<=5; i++){
				$('#star_'+id+'_'+i).attr("src","./public/images/admin/star0.gif");
				//$('#star_'+id+'_'+i).removeClass('star1');
				//$('#star_'+id+'_'+i).addClass('star0');
			}
			for(i=1; i<=stars; i++){
				$('#star_'+id+'_'+i).attr("src","./public/images/admin/star1.gif");
			}	
		}
	});
}
//设置星级(添加与编辑)
function addstars(sid,stars){
	for(i=1; i<=5; i++){
		$('#star_'+i).attr("src","./public/images/admin/star0.gif");
	}
	for(i=1; i<=stars; i++){
		$('#star_'+i).attr("src","./public/images/admin/star1.gif");
	}
	$('#'+sid+'_stars').val(stars);
}
//设置连载
function setcontinu(id,string){
	//var width = document.body.scrollWidth;
	//var height = document.body.scrollHeight;
	$('#showbg').css({width:$(window).width(),height:$(window).height()});	
	$('#ct_'+id).after('<span class="continu" id="htmlcontinu">连载至<input type="text" size="8" maxlength="15" value="'+string+'" name="continuajax" id="continuajax" onMouseOver="this.select();">集 <input type="button" value="确定" onclick="ajaxcontinu('+id+',continuajax.value);" class="navpoint"/><input type="button" value="取消" onclick="hidecontinu()" class="navpoint"/></span>');
	var offset = $('#ct_'+id).offset();
	$('#htmlcontinu').css({left:offset.left,top:offset.top});
}
//取消连载
function hidecontinu(){
	$('#showbg').css({width:0,height:0});
	$('#htmlcontinu').remove();
}
//AJAX连载
function ajaxcontinu(id,value){
	if(value==0){
		$('#ct_'+id).html('<img src="./public/images/admin/ct.gif" style="margin-top:10px" class="navpoint" onClick="setcontinu('+id+',0);">');
	}else{
		$('#ct_'+id).html('<sup onClick=setcontinu('+id+',"'+value+'") class="navpoint">'+value+'</sup>');
	}
	$.get('?s=admin-vod-ajaxcontinu-id-'+id+'-continu-'+value);
	hidecontinu();
}
/*滚动
$(window).scroll(function() { 		
	$("#div0").css({left:'50%',top:$(this).scrollTop()+100});
});
*/
//绑定分类
function setbind(event,cid,bind){
	$('#showbg').css({width:$(window).width(),height:$(window).height()});	
	var left = event.clientX+document.body.scrollLeft-70;
	var top = event.clientY+document.body.scrollTop+5;
	$.ajax({
		url: '?s=admin-xml-setbind-cid-'+cid+'-bind-'+bind,
		cache: false,
		async: false,
		success: function(res){
			if(res.indexOf('status') > 0){
				alert('对不起,您没有该功能的管理权限!');
			}else{
				$("#setbind").css({left:left,top:top,display:""});			
				$("#setbind").html(res);
			}
		}
	});
}
//取消绑定
function hidebind(){
	$('#showbg').css({width:0,height:0});
	$('#setbind').hide();
}
//提交绑定分类
var submitbind = function (cid,bind){
	$.ajax({
		url: '?s=admin-xml-insertbind-cid-'+cid+'-bind-'+bind,
		success: function(res){
			$("#bind_"+bind).html(" <a href='javascript:void(0)' onClick=setbind(event,'"+cid+"','"+bind+"');>已绑定</a>");
			hidebind();
		}
	});	
}
//pop弹出层：引用网页
function divwindow(strPath,Msg){
	var htm = '<iframe src="'+strPath+'" width="100%" height="95%" frameborder="0" scrolling="auto" style="overflow-x:hidden;"></iframe>';
	$("#dialog>#dia_title>span").html(Msg);
	$("#dialog>#dia_content").html(htm);
	$("#dialog").jqmShow();
}

jQuery.fn.extend({
	tag_init:function(){
		var event = $(this).attr('event');
		if(!event){
			event = 'click';
		}

		$(this).on(event,function(){
			var href = $(this).attr('data-href');
			var tag = $(this).attr('data-tag');
			if(!$(this).hasClass('curr')){
				$(this).addClass('curr').siblings().removeClass('curr');
				$("[data-role='tag-flush']").hide();
				if($(this).find("[data-role='tag-flush']").length>0){
					$(this).find("[data-role='tag-flush']").show();
				}else{
					if(href != null){
						$(this).append("<em class='tag-flush' data-role='tag-flush'>flush</em>");
						$(this).find("[data-role='tag-flush']").click(function(){
							$(this).parent().tag_load();
						});
					}
				}
				var box = $("[data-role='tag-content'][data-for='"+tag+"']");
				if(box.length>0){
					$(box).show().siblings().hide();
				}else{
					$(this).tag_load();
				}
				if(tag){	
					window.location.hash=tag;
				}
			}
		});
		var o = $(this);
		var url = window.location.toString();
		var tag = url.split("#")[1]; 
		if(!tag){
            // 兼容默认选项
            if($(o).parent().find('.curr').size()>0){
                var tag = $(o).parent().find('.curr').attr('data-tag');
            }else{
                var tag = $(o).first().attr('data-tag');
            }

			window.location.hash=tag;
		}
		$(window).hashchange(function(){
			var url = window.location.toString();
			var tag = url.split("#")[1]; 
			if(tag){
				$(o).filter("[data-tag='"+tag+"']").click();
			}else{
				$(o).first().click();
			}
		});
		return $(this);
	},
	tag_load:function(options){
		var options = $.extend({
			'href' : $(this).attr('data-href'),
			'tag' : $(this).attr('data-tag'),
			'reload' : $(this).attr('reload'),
			'event' : $(this).attr('event'),
			'close_able' : $(this).attr('close-able')
		}, options);
		$.show_loading();
		$.get(options.href,options.args,function(rs){
			$.close_loading();
			var rs = $.parseJSON(rs);
			if(rs.msg_code=='100000'){
				var box = $("[data-role='tag-content'][data-for='"+options.tag+"']");
				if(box.length>0){
					$(box).html(rs.msg_content).siblings().hide();
				}else{
					var box = $("<div data-role='tag-content' data-for='"+options.tag+"'>" + rs.msg_content + "</div>");
					$("[data-role='tag-body']").append($(box));
					$(box).siblings().hide();
				}
				//自动给分页加JS
				$(box).on('click',"[data-role='bx-page'] a",function(){
					$.show_loading();
					var url = $(this).attr('href');
					$.get(url,{},function(rs){
						$.close_loading();
						var rs = $.parseJSON(rs);
						if(rs.msg_code=='100000'){
							$(box).html(rs.msg_content)
						}else{
							$.show_error(rs.msg_content);
						}
					});
					return false;
				});
			}else{
				$.show_error(rs.msg_content);
			}
		});
		return $(this);
	}
});

var dialog_index = 5000;
// test

jQuery.extend({
	//弹出框
	showdiv:function(options,call_back){
		options = $.extend({
			id:'dialog' + Math.floor(Math.random()*32),
			title: '',
			css:{},
			resize: true,
			remove: true,
			foxed: false,
			show_close:true,
			content:'正在加载中，请稍后...'
		}, options);

		_str = 	'<div data-role="'+options.id+'"><div class="maskLayer" style="z-index: ' + dialog_index + ';"><iframe height="3056"> </iframe></div>' +
				'<div class="dialog" style="z-index: ' + (dialog_index + 1) + ';">' +
				'<div class="dDc">' +
					'<span class="tl"></span><span class="tr"></span><span class="br"></span><span class="bl"></span>' +
				'</div>' +
				'<div class="dCt">' +
					'<div class="dHd" data-role="dialog-header">';
		//多层弹窗
		dialog_index ++;
		if(options.title != null && options.title != ''){
			_str += '<h4 class="title" data-role="dialog-title">'+options.title+'</h4>';
		}
		if(options.show_close){
			_str += '<div class="option"><a class="icon i-close" href="javascript:void(0)" data-role="dialog-close">关闭</a></div>';
		}
		_str += '</div><div class="dBd" data-role="dialog-content">'+options.content+'</div>';
		_str += '</div></div></div>';
		box = $(_str);
		$(box).find(".dialog").css(options.css);
		$(box).appendTo("body").hide().fadeIn('fast');
		var top = (($(window).height() / 2) - ($(box).find('.dialog').outerHeight() / 2)) ;
		if(top + $(box).verticalOffset > 50 ){
			var top = top + $(box).verticalOffset
		}
		var left = (($(window).width() / 2) - ($(box).find('.dialog').outerWidth() / 2));
		if( top < 0 ) top = 0 - $(box).verticalOffset;
		if( left < 0 ) left = 0;
		// IE6 fix
//		if( $.support.msie && parseInt($.support.version) <= 6 ) top = top + $(window).scrollTop();

		$(box).find('.dialog').css({
			top: top + 'px',
			left: left + 'px'
		});
		$(box).find('.maskLayer').css({
			height: $(document).height() + 'px'
		});
		if(options.show_close){
			$(box).find("[data-role='dialog-close']").click(function(){
				$("[data-role='"+options.id+"']").fadeOut('slow',function(){
					$("[data-role='"+options.id+"']").remove();
				});
			});
		}
		if(call_back){
			call_back();
		}
	},
	//弹出确认框
	show_confirm:function(title,msg,ok_callback,esc_callback){
		if(msg == ''){
			msg = "您确认进行此操作吗？";
		}
		var str = "<span class='dialog-tips-alert'>"+ msg +"</span> <span class='space20'></span><button data-role='bt-confirm-ok' class='ui-button'>确认操作</button> <span class='space10'></span> <button data-role='dialog-close' class='ui-button'>取消操作</button><span class='blank10'></span>";
		$.showdiv({
			'id':'bx-dialog-confirm',
			'title':title,
			'content':str
		});
		if(ok_callback){
			$("[data-role='bx-dialog-confirm'] [data-role='bt-confirm-ok']").click(function(){
				$("[data-role='bx-dialog-confirm']").fadeOut('slow');
				ok_callback();
			});
		};
		if(esc_callback){
			$("[data-role='bx-dialog-confirm'] [data-role='dialog-close']").click(function(){
				esc_callback();
			});
		}
	},
	//根据地址弹出框
	showdiv_by_url:function(url,args,options,call_back){
		options = $.extend({
			id:'dialog' + Math.floor(Math.random()*32),
			title: '',
			css:{},
			resize: true,
			remove: true,
			foxed: false,
			content:'正在加载中，请稍后...'
		}, options);
		$.show_loading();
		$.get(url,args,function(rs){
			$.close_loading();
			var rs = $.parseJSON(rs);
			if(rs.msg_code==100000){
				options.content = rs.msg_content;
				$.showdiv(options);
				if(call_back){
					call_back();
				}
			}else{
				$.show_error(rs.msg_content);
			}
			//自动给分页加JS
			$("[data-role='"+options.id+"']").on('click',"[data-role='bx-page'] a",function(){
				var url = $(this).attr('href');
				$("[data-role='"+options.id+"']").remove();
				$.showdiv_by_url(url,args,options,call_back);
				return false;
			});
		});
	},
	//显示正在加载中
	show_loading:function(){
		var _str = 	'<div data-role="bx-loading"><div class="maskLayer" style="z-index: ' + dialog_index + ';"><iframe height="3056"> </iframe></div><div class="dialog" style="z-index: ' + (dialog_index+1) + ';width:50px;height:54px;"><img src="/images/loading.gif"/></div></div>'
		dialog_index ++;
		box = $(_str);
		$(box).appendTo("body");
		var top = (($(window).height() / 2) - ($(box).find('.dialog').outerHeight() / 2)) ;
		if(top + $(box).verticalOffset > 50 ){
			var top = top + $(box).verticalOffset
		}
		var left = (($(window).width() / 2) - ($(box).find('.dialog').outerWidth() / 2));

		// IE6 fix
	//	if( $.support.msie && parseInt($.support.version) <= 6 ) top = top + $(window).scrollTop();

		$(box).find('.dialog').css({
			top: top + 'px',
			left: left + 'px'
		});
		$(box).find('.maskLayer').css({
			height: $(document).height() + 'px'
		});
	},
	//关闭加载中
	close_loading:function(){
		$("[data-role='bx-loading']").remove();
	},
	//刷新页面
	flush:function(){
		_str = 	'<div data-role="bx-loading"><div class="maskLayer" style="z-index: ' + dialog_index + ';"><iframe height="3056"> </iframe></div><div class="dialog" style="z-index: ' + (dialog_index+1) + ';width:50px;height:54px;"><img src="' + config.cdn_i +'/comm/style/images/flush.gif"/></div></div>'
		dialog_index ++;
		box = $(_str);
		$(box).appendTo("body");
		var top = (($(window).height() / 2) - ($(box).find('.dialog').outerHeight() / 2)) ;
		if(top + $(box).verticalOffset > 50 ){
			var top = top + $(box).verticalOffset
		}
		var left = (($(window).width() / 2) - ($(box).find('.dialog').outerWidth() / 2));

		// IE6 fix
		//if( $.support.msie && parseInt($.support.version) <= 6 ) top = top + $(window).scrollTop();

		$(box).find('.dialog').css({
			top: top + 'px',
			left: left + 'px'
		});
		$(box).find('.maskLayer').css({
			height: $(document).height() + 'px'
		});
		//刷新
		location.reload();
	},
	goto:function(href){
		location.href = href;
	},
	sleep:function(time,call_back){
		setTimeout(call_back,time);
	},
	show_success:function(goto_url){
		if(goto_url){
			$.showdiv({
				'show_close':false,
				'content':"<center style='margin:20px 0px'><span class='dialog-tips-success'>操作成功，系统将在<b data-role='lose-time'>3</b> 秒后 <a href='"+goto_url+"'>跳转</a> ！</span></center>"
			});
			var timer = $.timer(1000, function(){
				var lose_time = parseInt($("[data-role='lose-time']").html());
				if(lose_time == 1){
					$.goto(goto_url);
				}else{
					$("[data-role='lose-time']").html(lose_time - 1);
				}
			});
		}else{
			$.showdiv({
				'id':'bx-success',
				'css':{'width':'350px'},
				'show_close':false,
				'content':"<center style='margin:20px 0px'><span class='dialog-tips-success'>操作成功！</span></center>"
			});
			var timer = $.timer(1000, function(){
				$("[data-role='bx-success']").remove();
				timer.stop();
			});
		}
	},
	show_error:function(error_msg,close_time){
		$.showdiv({
			'id':'bx-error',
			'css':{'width':'350px'},
			'content':error_msg
		});
		if(close_time){
			var timer = $.timer(close_time, function(){
				$("[data-role='bx-error']").remove();
				timer.stop();
			});
		}
	},
    valideform:function(role){
		alert(role);
		return false;
	}
});

///将插件写入jQuery
jQuery.fn.extend({
	//居中
	center:function(){
		var box = $(this);
		$(box).x_center();
		$(box).y_center();
	},
	x_center:function(){
		var box = $(this);
		var left = (($(window).width() / 2) - ($(box).outerWidth() / 2));
		var left = (($(window).width() / 2) - ($(box).outerWidth() / 2));
		$(box).css({
			left: left + 'px'
		});
	},
	y_center:function(){
		var box = $(this);
		var top = (($(window).height() / 2) - ($(box).outerHeight() / 2)) ;
		if(top + $(box).verticalOffset > 50 ){
			var top = top + $(box).verticalOffset
		}
		// IE6 fix
		//if( $.support.msie && parseInt($.support.version) <= 6 ) top = top + $(window).scrollTop();
		$(box).css({
			top: top + 'px'
		});
	},

	//城市列表
	city_select:function(parent_id,options,change_call,select_class){
		var select_cls = arguments[3] || 'select city_select';
		options = $.extend({
			show_level:10,
			title:'省份,市/洲,县/区,乡/街道'
		}, options);
		citys = '';
		var city_box = $(this);		//show box
		change = function(_id,selected_id,change_call, select_cls){
			$(city_box).find("[data-role='city-loading']").remove();
			if(_id =='0'){
				_id = '1';
			}

			var city = citys[_id];
			var child = city.child;

			var level = parseInt(city.city_level) + 1;

			$(city_box).find("select[level='"+(_id=='1'?(level+1):level)+"']").next().remove();
			$(city_box).find("select[level='"+(_id=='1'?(level+1):level)+"']").remove();

			if(child !=undefined&&($(city_box).find("select[level='1']").length == 0 && _id == '1'|| _id !='1')){

				var _title = options.title.split(',');
				var select_str = "<select name='city_id[]' data-role='select-citys' level='"+ level +"'  class='"+select_cls+"' ><option value='0'><b>"+ _title[level-1] +"</b></option>";
				$.each(child,function(m,n){
					var o = citys[n];
					select_str += "<option value='"+o.id+"'>"+o.city_name+"</option>";
				});
				select_str += "</select>";
				var select = $(select_str);
				if(selected_id){
					$(select).val(selected_id);
				}
				$(select).change(function(){
					var v = $(this).val();
					if(v=='0'&&level>1){
						if(level == 2){
							$(city_box).find("select[level='"+(level+1)+"']").remove();
						}
						return;
					}
					if(level < options.show_level){
						change(v,'',change_call,select_cls);
					}
				});

				$(city_box).append($(select));
			}
			if(change_call){
				change_call(_id);
			}
		};
		$(city_box).append("<span data-role='city-loading'>请稍后，加载中...</span>");

		$.get("/ajax/city.list",{},function(data){
			var rs = $.parseJSON(data);
			if(rs.msg_code != 100001){
				alert(rs.msg_content);
			}else{
				citys = rs.msg_content;
			}

			var p_id = parent_id.toString().split("/");
			var num = p_id.length > options.show_level ? options.show_level : p_id.length;

			for(var i=0 ; i < num ; i++ ){
				var _id = p_id[i];
				if(i < num ){
					selected_id = p_id[i+1];
					change(_id,selected_id,change_call,select_cls);
				}else{
					change(_id,'',change_call,select_cls);
				}
			}
		});
	}
});




(function($) {

    $.fn.extend({
        hashchange: function(callback) {
            this.bind('hashchange', callback);

            if (location.hash)//if location.hash is not empty,fire the event when load,make ajax easy
            {
                callback();
            }
        },
        openOnClick: function(href) {
            if (href === undefined || href.length == 0)
                href = '#';
            return this.click(function(ev) {
                if (href && href.charAt(0) == '#') {
                    // execute load in separate call stack
                    window.setTimeout(function() { $.locationHash(href) }, 0);
                } else {
                    window.location(href);
                }
                ev.stopPropagation();
                return false;
            });
        }
    });

    // IE 8 introduces the hashchange event natively - so nothing more to do
    if ($.support.msie && document.documentMode && document.documentMode >= 8) {
        $.extend({
            locationHash: function(hash) {
                if (!hash)//get hash value
                {
                    if (location.hash.charAt(0) == '#') {
                        return location.hash.substr(1, location.hash.length-1);
                    }
                    return location.hash;
                }
                if (!hash) hash = '#';
                else if (hash.charAt(0) != '#') hash = '#' + hash;
                location.hash = hash;
            }
        });
        return;
    }

    var curHash;
    // hidden iframe for IE (earlier than 8)
    var iframe;

    $.extend({
        locationHash: function(hash) {
            if (!hash)//get hash value
            {
                if (location.hash.charAt(0) == '#') {
                    return location.hash.substr(1, location.hash.length - 1);
                }
                return location.hash;
            }
            if (curHash === undefined) return;

            if (!hash) hash = '#';
            else if (hash.charAt(0) != '#') hash = '#' + hash;

            location.hash = hash;

            if (curHash == hash) return;
            curHash = hash;

            if ($.support.msie) updateIEFrame(hash);
            $.event.trigger('hashchange');
        }
    });

    $(document).ready(function() {
        curHash = location.hash;
        if ($.support.msie) {
            // stop the callback firing twice during init if no hash present
            if (curHash == '') curHash = '#';
            // add hidden iframe for IE
            iframe = $('<iframe />').hide().get(0);
            $('body').prepend(iframe);
            updateIEFrame(location.hash);
            setInterval(checkHashIE, 100);
        } else {
            setInterval(checkHash, 100);
        }
    });
    $(window).unload(function() { iframe = null });

    function checkHash() {
        var hash = location.hash;
        if (hash != curHash) {
            curHash = hash;
            $.event.trigger('hashchange');
        }
    }

    if ($.support.msie) {
        // Attach a live handler for any anchor links
        $('a[href^=#]').live('click', function() {
            var hash = $(this).attr('href');
            // Don't intercept the click if there is an existing anchor on the page
            // that matches this hash
            if ($(hash).length == 0 && $('a[name=' + hash.slice(1) + ']').length == 0) {
                $.locationHash(hash);
                return false;
            }
        });
    }

    function checkHashIE() {
        // On IE, check for location.hash of iframe
        var idoc = iframe.contentDocument || iframe.contentWindow.document;
        var hash = idoc.location.hash;
        if (hash == '') hash = '#';

        if (hash != curHash) {
            if (location.hash != hash) location.hash = hash;
            curHash = hash;
            $.event.trigger('hashchange');
        }
    }

    function updateIEFrame(hash) {
        if (hash == '#') hash = '';
        var idoc = iframe.contentWindow.document;
        idoc.open();
        idoc.close();
        if (idoc.location.hash != hash) idoc.location.hash = hash;
    }

})(jQuery);

(function($) {
    $.tip = {
        conf: {
            timer: null,
            timerLength: 3E3,
            tipClass: ""
        },
        show: function(b, d) {
            clearTimeout($.tip.conf.timer);

            $(".tipbox")[0] || $("body").append('<div class="tipbox"></div>');
            $(".tipbox").attr("class", "tipbox " + $.tip.conf.tipClass);
            $(".tipbox").html(d);
            var e = $(".tipbox").outerWidth(),
                f = $(".tipbox").outerHeight();
            $(".tipbox").css({
                left: $(b).offset().left - e / 2 + "px",
                top: $(b).offset().top - f - 10 + "px"
            }).fadeIn();
            $.tip.conf.timer = setTimeout(function() {
                    $(".tipbox").fadeOut()
                },
                $.tip.conf.timerLength)
        }
    }
})(jQuery);