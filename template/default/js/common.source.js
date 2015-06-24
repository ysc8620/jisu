$(function(){
	var addCollet = true;
	var OOFA = Cookie.get("V114LA");
	var movHistory = Cookie.get("movHistory");
	var uid;
	if(!Cookie.get('collid')){ $(".titsub h6 span").show();}
    uid = Cookie.get('videoUid');
	if(OOFA && (uid<1 || !uid || uid=='undefined')){
		//判断登陆获取用户数据
		$.ajax({
			type:'post',
			async:false,//把异步请求false才能读到内部的变量
			url:'/?ct=account&ac=ajax_get_current_userinfo',
			success:function(data){
				var data = $.evalJSON(data);
				uid = data['user_id'];

                Cookie.set('videoUid',uid,new Date(new Date().getTime() + 1000 * 3600 * 24 * 365),"/",'.php369.com');
				var logHtml = '<p class="loged" uid="'+uid+'"><a href="/?ct=account" class="u">'+data['show_name']+'</a><a href="/?ct=account&ac=logout">退出</a></p>';
				$(".userlog").html(logHtml);
			}
		})
	};

	//处理左右分栏高度
	$("#balance .comwrap").find(".sideCont").each(function(){
		var dimHeight = $(this).height();
		// $(this).parent().find(".leftSide").height(dimHeight);
	});

	//首页内容切换
	$(".contabs").find("li").hover(function(){
		var tab_index = $(this).index();
		$(this).addClass("current").siblings("li").removeClass("current");
		$(this).parents(".ingrep").find(".ingrep-Cont").hide();
		$(this).parents(".ingrep").find(".ingrep-Cont").eq(tab_index).show();
		$(".ingrep-Cont").eq(tab_index).molazy();
	});

	//lazyload
	$("img[data-original]").lazyload({
		effect:"fadeIn",
		skip_invisible:false,
		threshold : 500
	});
	$("a#collet").click(function(){
		AddFavorite('http://v.php369.com/','php369影视，最多、最全的影视导航'); return false;
	});

	//播放记录
	var canAdd = true;//var reurl;
	var history = {
		tar:"#hisStatu ul",
		//add:function(title,id,url,chapter,nxurl){
		add:function(Params){
			if(typeof Params != 'object' && Params == 'undefined') return;
			if($(this.tar).find("li").size() > 7) return; //没有登陆前提下最多记录7条
			var TPL = '';var host = window.location.href;
			var id = Params.id, title =  Params.title, url = Params.href, chapter = Params.chapter, nxurl = Params.nxurl;
			//this.tinyurl(url);//缩短网址，减少cookie体积;

			this.judge(id)
			if(!canAdd){ $("li[id='"+id+"']").remove(); }//排重
			if(typeof chapter == 'undefined' && typeof nxurl == 'undefined'){
				TPL += '<li id="'+id+'"><a href="'+host+'" target="_blank">'+title+'</a><span><a href="'+url+'" target="_blank">继续看</a><a href="javascript:;" class="del">X</a></span></li>';
			}else{
				TPL += '<li id="'+id+'"><a href="'+host+'" target="_blank">'+title+'</a><i>'+chapter+'</i><span><a href="'+nxurl+'" target="_blank">下一集</a><em>|</em><a href="'+url+'" target="_blank">继续看</a><a href="javascript:;" class="del">X</a></span></li>';
			};
			if(typeof($("#noHis")[0]) != 'undefined'){
				$("#noHis").remove();//删除无记录提示
			}
			$(this.tar).prepend(TPL);
			this.rebuild();//写入cookie
		},
		del:function(){
			if($(this.tar).find("li").size() == 0){
				var TPL = '<li id="noHis">您还没有观看记录~</li>';
				$(this.tar).html(TPL);
				Cookie.clear("movHistory");
			}else{
				this.rebuild();
			}
		},
		judge:function(id){
			$.each($(this.tar).find("li"), function(){
				var idstu = $(this).attr("id");
				if(idstu === id){ canAdd = false; }
			});
			return canAdd;
		},
		tinyurl:function(href){
			$.ajax({
	            type: "get",
	            url:'http://dwz6.com/',
	            data:"ac=api&url=" + href + '&status=tinyurl',
	            dataType:'jsonp',
	            jsonp:'jsonpcallback',
	            async:false,
	            success:function(data){
	                var status = data['status'];
	                if(status == '-1'){
	                    reurl = href;
	                }else{
	                	reurl = data['tinyurl'];
	                }
	                return reurl;
	            },
	            error:function(data){
	                return href;
	            }
	        }) 
	    },
		rebuild:function(){
			var hisArr = [];
			$.each($(this.tar).find("li"), function (){
			var hisLiSize = $(this).children().size();
				hisObj = {}
				hisObj.title = $(this).find("a").eq(0).text();
				hisObj.url = $(this).find("a").eq(0).attr("href");
				hisObj.id = $(this).attr("id");
				if(hisLiSize == 3){
					hisObj.chapter = $(this).find("i").text();
					hisObj.nxurl = $(this).find("span a").eq(0).attr("href");
				}
				hisArr.push(hisObj);
			});
			hisJson = $.toJSON(hisArr)
			Cookie.set("movHistory",hisJson)
		},
		init:function(){
			if(!movHistory){
				var TPL = '<li id="noHis">您还没有观看记录~</li>';
				$(this.tar).html(TPL);
			}else{
				movHistory = $.evalJSON(movHistory);
				var hisHtm = '';
				$.each(movHistory, function (i,data){
					if(typeof data['chapter'] == 'undefined' && typeof data['nxurl'] == 'undefined'){
						hisHtm += '<li id="'+data['id']+'"><a href="'+data['url']+'" target="_blank">'+data['title']+'</a><span><a href="'+data['url']+'" target="_blank">继续看</a><a href="javascript:;" class="del">X</a></span></li>';
					}else{
						hisHtm += '<li id="'+data['id']+'"><a href="'+data['url']+'" target="_blank">'+data['title']+'</a><i>'+data['chapter']+'</i><span><a href="'+data['nxurl']+'" target="_blank">下一集</a><em>|</em><a href="'+data['url']+'" target="_blank">继续看</a><a href="javascript:;" class="del">X</a></span></li>';
					}
				});
				$(this.tar).html(hisHtm);
			};
		}
	};
	history.init();

	$("a[history='1']").live("click", function(){
		var channel = $("body").attr("channel");
		var id = $("#moid").attr("sn"), 
			title = $("#moid").text(), 
			href = $(this).attr("href");
		switch(channel){
			case'movie':
				history.add({
					'title'   : title,
					'id'      : id,
					'href'    : href
				});
			break;
			default:
				var chapter = $(this).text(),
					nxurl = (typeof($(this).next("a").attr("href")) == 'undefined') ? href : $(this).next("a").attr("href");
				history.add({
					'title'   : title,
					'id'      : id,
					'href'    : href,
					'chapter' : chapter,
					'nxurl'   : nxurl
				});
			break;
		}
	});
	$("#hisLogin").live("click", function(){
		$(".history").removeClass("hover");
		//$("#triLogin").trigger("click");
		$(".setFloat").show();
		triLogin = true;//设置登录框可以打开
		return false;
	});
	$(".hisCont").find("a.del").live("click", function(){
		$(this).parents("li").fadeOut(500,function(){
			$(this).remove();
			history.del();
		});
	});

	//回到顶部
	var gotoTop = {
		id: "#gotop",
		clickMe : function(){
			$('html,body').animate({scrollTop : '0px'},{ duration:500});
		},
		toggleMe : function() {
			if($(window).scrollTop() == 0) {
				$(this.id).stop().animate({'opacity': 0}, "slow");
			} else {
				$(this.id).stop().animate({'opacity': 1}, "slow");
			}
		},
		init : function() {
			$(this.id).css('opacity', 0);
			$(this.id).click(function(){
				gotoTop.clickMe();
				return false;
			});
			$(window).bind('scroll resize', function(){
				gotoTop.toggleMe();
			});
		}
	};
	gotoTop.init();

	//边栏广告固定
	if(typeof $("#sideAds")[0]!='undefined'){
		var sideAds = $('#sideAds');
		var offset = sideAds.offset(),jqWindow = $(window);
		var scrollhandlefn = function(){
			if (Number(jqWindow.scrollTop())> Number(offset.top)){
				if(!sideAds.hasClass("fixBox")){
					sideAds.addClass("fixBox");
				}
			}
			else{
				if(sideAds.hasClass("fixBox")){
					sideAds.removeClass("fixBox");
				}
			}
		}
		scrollhandlefn();
		jqWindow.scroll(function() {
			scrollhandlefn();
		});
	}

	//添加hover
	$(".history, .sumgroup li, .caseList li, .comcaseRow li, .spitMvList li, .gloComList li, .rowMvlist li, .usHistoryList li, .usform td span, .seleList ul li").hover(function(){
		$(this).addClass("hover");
	},function(){
		$(this).removeClass("hover");
	})
	$(".foldlist").find("li").hover(function(){
		$(this).addClass("hover").siblings("li").removeClass("hover");
		$(this).molazy();//触发延迟加载图片
	});
	$(".foldlist").find("li").eq(0).molazy();//分类内页边栏第一个加载;

	//幻灯片切换
	if(typeof($(".sliCont")[0]) != 'undefined'){
		$(".snum").switchable('.sliCont > ul > li', { 
			effect: 'fade',
			circular:'true', 
			speed: .5
		}).autoplay();
		var api = $(".snum").switchable();
		$('.sliCont .tk-right').click(function(){
			api.next();
		});
		$('.sliCont .tk-left').click(function(){
			api.prev();
		});
	}

	//明星资料页幻灯片
	if( typeof($(".sumimgs")[0]) != 'undefined' ){
		$(".sus").switchable('.sumimgs > ul > li', { 
			effect: 'scroll',
			circular:'true',
			speed: .5
		}).autoplay();
		var api = $('.sus').switchable();
		$('.srgt').click(function(){
			api.next();
		});
		$('.slft').click(function(){
			api.prev();
		});
	}
	
	//{S:搜索框}
	var queryList;//隐藏搜索框建议
	var KeywordItems;
	var currentKey = 0;
	$(".queryList ul li").live("hover", function(){
		currentKey = $(this).index();
		$(this).addClass("hover").siblings("li").removeClass("hover");
		$(".tcont").eq($(this).index()).show().siblings(".tcont").hide();
	}).live("click", function(){
		var kw = $(this).attr("data-title");
		$("#searchTxt").val(kw);
		$("#js-query-data").hide();
		//searchform();
		window.location.href='/sech-'+kw+'.html'; 
		//return false;
	});
	$("a.goplay").live("click", function(){
		$("#js-query-data").hide();
		//return false;
	})
	$(".queryList").bind({
		mouseover:function(){
			queryList = true;
		},
		mouseout:function(){
			queryList = false;
		}
	});
	$("#searchform").submit(function(){
		searchform();
		$("#js-query-data").hide();
		return false;
	});
	var delaySearch;;
	$("#searchTxt").focus();
	$("#searchTxt").bind({
		keydown:function(){
			clearTimeout(delaySearch);
		},
		keyup:function(e){
			var e = e || event, keyCode = e.keyCode;
			var keyword =  $("#searchTxt").val();
			if(keyword == '') return;//搜索为空
			if(keyCode == 40){//down
				currentKey++;
				searchMod.selectItem();
			}else if(keyCode == 38){//up
				currentKey--;
				searchMod.selectItem();
			}else if(keyCode == 27){//esc
				$("#js-query-data").hide();
			}else{//query data
				if(keyword != ''){
					$(".search").append('<em class="serLoad"></em>');
				}
				delaySearch = setTimeout(function(){
					searchMod.queryData(keyword, function(){
						$("#js-query-data").show();
					});
				}, 500)
			}
		}
	});
	var queryClassName = ['Params','电影','电视剧','动漫','综艺'];
	var hotwords = ['神枪狙击','小时代','辣妈正传','巨轮','十年再爱你','神盾局特工'];
	var searchMod = {
		queryData : function(value, callback){
			$.ajax({
				tpye:'post',
				url:'/?ct=index&ac=search_tips',
				data:{'q':value},
				dataType:'jsonp',
				jsonp:'callback',
				//jsonpCallback:'anymouse',
				success:function(json){
					var code = json['code'];
					var str  = json['str'];
					var listTPL = '';
					var summTPL = '';
					if(code == 0 && str == 'success'){
						var data = json['data'];
						for(var i = 0;i < data.length;i++){
							var actors = (data[i]['act'] == '') ? '——' : data[i]['act'];
							switch(data[i]['class']){
								case'1'://电影
									listTPL += '<li id="'+data[i]['id']+'" data-title="'+data[i]['title']+'">'+data[i]['title']+' (电影)<em></em></li>';
									summTPL += '<dl class="tcont"><dt><a href="'+data[i]['url']+'" target="_blank"><img src="'+data[i]['img']+'" alt="'+data[i]['title']+'" /></a>'
													+'<div><p>'+data[i]['title']+'</p><p>主演：<span>'+actors+'</span></p></div></dt><dd>'
													+'<a href="'+data[i]['url']+'" class="goplay" target="_blank">立即播放</a></dd></dl>';
								break;
								case'2'://电视剧
									listTPL += '<li id="'+data[i]['id']+'" data-title="'+data[i]['title']+'">'+data[i]['title']+' (电视剧)<em></em></li>';
									summTPL += '<dl class="tcont"><dt><a href="'+data[i]['url']+'" target="_blank"><img src="'+data[i]['img']+'" alt="'+data[i]['title']+'" /></a>'
													+'<div><p>'+data[i]['title']+'</p>'
														+'<p>更新至'+data[i]['updateNum']+'集</p><p>主演：<span>'+actors+'</span></p></div></dt><dd>'
													+'<a href="'+data[i]['url']+'" class="goplay" target="_blank">立即播放</a></dd></dl>';
								break;
								case'3'://动漫
									listTPL += '<li id="'+data[i]['id']+'" data-title="'+data[i]['title']+'">'+data[i]['title']+' (动漫)<em></em></li>';
									summTPL += '<dl class="tcont"><dt><a href="'+data[i]['url']+'" target="_blank"><img src="'+data[i]['img']+'" alt="'+data[i]['title']+'" /></a>'
													+'<div><p>'+data[i]['title']+'</p>'
														+'<p>更新至'+data[i]['updateNum']+'集</p><p>主演：<span>'+actors+'</span></p></div></dt><dd>'
													+'<a href="'+data[i]['url']+'" class="goplay" target="_blank">立即播放</a></dd></dl>';
								break;
								case'4'://综艺
									listTPL += '<li id="'+data[i]['id']+'" data-title="'+data[i]['title']+'">'+data[i]['title']+' (综艺)<em></em></li>';
									summTPL += '<dl class="tcont"><dt><a href="'+data[i]['url']+'" target="_blank"><img src="'+data[i]['img']+'" alt="'+data[i]['title']+'" /></a>'
													+'<div><p>'+data[i]['title']+'</p>'
														+'<p>更新至'+data[i]['updateNum']+'期</p><p>主演：<span>'+actors+'</span></p></div></dt><dd>'
													+'<a href="'+data[i]['url']+'" class="goplay" target="_blank">立即播放</a></dd></dl>';
								break;
							}
						}
						$("#js-calldata-list").html(listTPL).find("li").eq(0).addClass("hover");
						$("#js-calldata-preview").html(summTPL).find(".tcont").eq(0).show();
					}else{
						$("#js-calldata-list").html('<p>木有数据噢~试下其他关键字?</p>');
						$("#js-calldata-preview").html('');
					}
					$(".serLoad").remove();
				},
				error:function(){
					$("#js-calldata-list").html('<p>搜索数据请求错误，请稍后在试~</p>');
				}
			});
			if(callback){
				callback();
			}
		},
		init:function(){
			var len = hotwords.length;
			var ram = Math.floor(Math.random() * len);
			$("#searchTxt").attr("placeholder",hotwords[ram]);
		},
		selectItem:function(){
			KeywordItems = $("#js-calldata-list li")
	        if (!KeywordItems) return;
	        var len = KeywordItems.length;

	        if (currentKey < 0) {
	            currentKey = len - 1;
	        }
	        else if (currentKey >= len) {
	                currentKey = 0;
	        }
	        $("#searchTxt").val(KeywordItems.eq(currentKey).attr("data-title"));
	        KeywordItems.eq(currentKey).addClass('hover').siblings('li').removeClass('hover');
	        $(".tcont").eq(currentKey).show().siblings(".tcont").hide();
	    }
	}
	//searchMod.init();
	//{S:搜索框}

	$(".inTitle ol li").live("click", function(){
		$(this).addClass("current").siblings("li").removeClass();
		$(".gloComList ul").eq($(this).index()).show().siblings(".gloComList ul").hide();
		$(".gloComList ul").eq($(this).index()).molazy();
	});

	//排行榜
	$(".trendNavi li").click(function(){
		$(this).addClass("active").siblings("li").removeClass("active");
	})

	//详细页
	$(".playurl ul li").live("click", function(){
		$(this).addClass("current").siblings("li").removeClass();
		$(".playurl dd").eq($(this).index()).show().siblings(".playurl dd").hide();
	});
	if(typeof($(".playurl")[0]) != 'undefined'){
		$(".playurl ul li").each(function (i){
			if(i > 4) $(this).hide();
		});
		$(".playurl dd").each(function(){
			var judhei = $(this).height();
			if(judhei >= 220){
				var emTPL ='<em class="swHiCh">展开更多↓</em>';
				$(this).addClass("stu").append(emTPL);
			};
		});
		$(".swHiCh").live("click",function(){
			$(this).parent().removeClass("stu");
			$(this).remove();
		});
		if($(".contSums dd").height() > 100){
			$(".contSums dd").css("height","100px");
			$(".contSums dd span").css("display","inline-block");
		};
	}
	$(".contSums dd span").click(function(){
		if($(this).find("i").attr("class") == 'cos'){
			$(".contSums dd").css("height","100px");
			$(".contSums dd span").find("i").removeClass("cos")
		}else{
			$(".contSums dd").css("height","auto");
			$(this).find("i").toggleClass("cos");
		}
	});
	//关掉收藏提示
	$("#collid").click(function(){
		Cookie.set("collid","clicked");
		$(this).parent().hide();
	});
	//会员中心收藏
	$("a.col").click(function (e){
		if($(this).hasClass("posted")){
			alert('您已收藏，无须重复操作。');
			return false;
		}
		var vid = $(this).parent().parent().find("a.tit").attr("vid");
		ajaxCheckPost(e,vid);
	});
	if(typeof($("#moid")[0]) != 'undefined'){
		checkCollet();
	};
	if(typeof($(".usHistoryList")[0]) != 'undefined'){
		$(".usHistoryList li").each(function(){
			var vid = $(this).find("a.tit").attr("vid");
			checkCollet(vid);
		})
	};
	//判断是否已经收藏过
	function checkCollet(vid){
            var vid  = vid || $("#moid").attr("sn");
            if(vid && uid){
                $.ajax({
                    type:'post',
                    url:'/?ct=account&ac=ajax_get_isfav',
                    data:{uid:uid,vid:vid},
                    success:function(st){
                        if(st == '1'){
                            if(typeof($(".titsub")[0]) != 'undefined'){
                                $(".titsub h6 a").addClass("posted").html("<em></em>已收藏");
                            }else{
                                $("a[vid='"+vid+"']").parent().find("a.col").addClass("posted");
                            }
                        }
                    }
                });
            }		
	}
	//提交影片收藏
	$(".titsub h6 a").click(function (e){
		if(!OOFA){
			if(confirm('您好，收藏请先登陆~')){
				window.location.href = '/?ct=login';
				return false;
			}
		}else if($(this).hasClass("posted")){
			alert('您已收藏，无须重复操作。')
			return false;
		}else{
			ajaxCheckPost(e);
		}
	})
	function ajaxCheckPost(event,vid){
		var cate = $("#moid").attr("cagegory") || '';
		var id   = $("#moid").attr("sn") || vid;
		$.ajax({
			type:'post',
			url:'/?ct=account&ac=ajax_add_favourites',
			data:{'class':cate,'vid':id},
			success:function(){
				if(typeof($(".titsub")[0]) != 'undefined'){
					$(".titsub h6 a").addClass("posted").html("<em></em>已收藏");
				}else{
					$("a.tit[vid='"+id+"']").parent().find("a.col").addClass("posted");
				}
				setPosEle(event);
				addCollet = false;
			},
			error:function(){
				alert('很抱歉，收藏失败，请稍后再试！')
			}
		})
	}

	//删除收藏
	$("a.d").click(function(){
		var vid = $(this).attr("vid");
		if (confirm('确认要删除吗？')) {
			$.ajax({
				type:'post',
				url:'/?ct=account&ac=ajax_del&type=1',
				data:{'vid':vid,'uid':uid},
				success:function(){
					$("a.d[vid='"+vid+"']").parent().parent().fadeOut('slow',function(){
						$(this).remove();
					});
				},
				error:function(){
					alert('很抱歉，删除失败，请稍后再试！')
				}
			})
		};
	});
	//删除观看纪录
	$("span.ctr").find("a.del").click(function(){
		var vid = $(this).parent().parent().find("a.tit").attr("vid");
		if(confirm('确认要删除吗？')){
			$.ajax({
				type:'post',
				url:'/?ct=account&ac=ajax_del&type=2',
				data:{'vid':vid,'uid':uid},
				success:function(){
					$("a.tit[vid='"+vid+"']").parent().fadeOut('slow',function(){
						$(this).remove();
						checkHisSts();
					});
					//还要重组观看纪录的cookie和UI面板
					$(".hisCont ul").find("li[id='"+vid+"']").remove();
					history.del();
				},
				error:function(){
					alert('很抱歉，删除失败，请稍后再试！')
				}
			})
		};
	});
	function checkHisSts(){
		if($(".usHistoryList").children("li").size() == '0'){
			$(".usHistoryList").html("<li>没有观看记录。</li>");
		};
	};

	/*/边栏锚点导航
	if(typeof($(".fixnavi")[0]) != 'undefined'){
		var posArr = [];var fixnaHm = '';
		$.each($("h6[pos]"), function(){
			var posPram = $(this).offset().top;
			var posTxt = $(this).text();
			var num = $(this).attr("pos");
			posArr.push(posPram);
			fixnaHm += '<a href="javascript:;" set="'+num+'">'+posTxt+'</a>';
		});
		$(".fixnavi dd").html(fixnaHm);
		$(".fixnavi dd a").each(function (i){
			if(i%2 === 1){
				$(this).addClass("nth2")
			}
		});
		$("a[set]").click(function(){
			var setPram = Number($(this).index())
			$("html, body").animate({scrollTop : posArr[setPram]+'px'},{ duration:500});
		});

		var curMax = 0;
		$(window).bind('scroll resize', function(){
			var _top = $(window).scrollTop();
			var fakePos = $("h6[pos]");
			var max = 0;
			fakePos.each(function(){
				if(_top > $(this).offset().top - 30){
					max = fakePos.index(this);
				}
			});
			if(curMax != max){
				curMax = max;
				$("a[set]").eq(max).addClass("current").siblings("a[set]").removeClass("current");
			}
		});
	}*/

	//会员中心
	var seleCan = true, triLogin = true;
	$("#triLogin").click(function(){
		// $(".setFloat").toggle();
		// return false;
	});
	$(".setFloat").bind({
		mouseover:function(){
			triLogin = true;
		},
		mouseout:function(){
			triLogin = false;
		}
	});

	$("em.placeho").click(function(){
		$(this).hide().parent().find("input").focus();
	});
	$(".comform li input").each(function(){
		if($(this).val() != ''){
			$(this).parent().find(".placeho").hide();
		};
	});
	$(".comform li input").bind({
		focus:function(){
			$(this).parent().find(".placeho").hide();
		},
		blur:function(){
			if($(this).val() == ''){
				$(this).parent().find(".placeho").show();
			}
		},
		keydown:function(){
			$(this).parent().removeClass("error").find("span").remove();
		}
	});
	$("#usReset").click(function(){
		if(confirm('确定要重置所有数据吗？')){
			$("input[type='text']").val('');
			$("input[type='checkbox'], input[type='radio']").removeAttr("checked");
			$("#sel-province, select[name='birthday[year]'], select[name='birthday[month]'], select[name='birthday[day]'], #sel-city").find("option").removeAttr("selected");
		}
	});

	//注册成功下拉模拟下拉选框
	if(typeof($(".seleList")[0]) != 'undefined'){
		$(".seleList").find("span").bind({
			click:function(){
				$(this).next("ul").toggle();
			},
			mouseover:function(){
				seleCan = true;
			},
			mouseout:function(){
				seleCan = false;
			}
		});
		$(".seleList ul li").live({
			click:function(){
				var seltxt = $(this).text();
				var selval = $(this).attr("value");
				$(this).parent().parent().find("em").text(seltxt);
				$(this).parent().parent().find("ul").hide();
				$(this).parent().parent().find("input[type='hidden']").val(selval);
				return false;
			},
			mouseenter:function(){
				$(this).addClass("hover");
			},
			mouseleave:function(){
				$(this).removeClass("hover");
			}
		});
	};
	$("#js-getProvince").find("li").click(function(){
		var pid = $(this).attr("value");
		$.get("/?ct=register&ac=ajax_get_city",{'pid':pid},function (d){
			$("#js-getCity").parent().find("span").find("em").html('选择市');
			data = $.evalJSON(d);
			data = data['msg'];
			var phtml = '';
			$.each(data, function (i,items){
				phtml += '<li py="'+items['all_letter']+'" value="'+items['id']+'">'+items['letter']+' '+items['name']+'</li>';
			})
			$("#js-getCity").html(phtml);
		});
	});
	$("#sel-province").change(function(){
		var pid = $(this).val();
		getCity(pid);
	});
	if(typeof($("#sel-province")[0]) != 'undefined' && $("#sel-province").val() != ''){
		var pid = $("#sel-province").val();
		getCity(pid);
	};
	function getCity(provid,callback){
		$.get("/?ct=register&ac=ajax_get_city",{'pid':provid},function (d){
			$("#sel-city").html('<option>选择市</option>');
			data = $.evalJSON(d);
			data = data['msg'];
			var phtml = '';
			var cityId = $("#sel-city").attr("city");
			$.each(data, function (i,items){
				if(cityId == items['id'] && cityId != '0'){
					phtml += '<option py="'+items['all_letter']+'" value="'+items['id']+'" selected="selected">'+items['letter']+' '+items['name']+'</option>';
				}else{
					phtml += '<option py="'+items['all_letter']+'" value="'+items['id']+'">'+items['letter']+' '+items['name']+'</option>';
				}
			})
			$("#sel-city").append(phtml);
		});
		if(typeof(callback) == "function") callback();
	}

	//点击页面触发行为
	$(document).click(function(ele){
		if(!seleCan){$(".seleList ul").hide();}
		//if(!triLogin){$(".tri-tick-ins").remove();}
		if(!queryList){$(".queryList").hide();}
	});

	//收藏+1的callback处理
	function setPosEle(ele){
		//var ele = ele || ele.target;
		if(!addCollet){
			alert('请勿重复操作~');
			return false;
		}
		var left = ele.pageX, 
			top = ele.pageY, 
			width = ele.srcElement.offsetWidth, 
			height = ele.srcElement.offsetHeight;
		var addPosArr = [left,top,width,height];
		var sty_left = left - (width/2), sty_top = top - (height/2);
		var em = '<em id="upCouse" style="width:'+width+'px;height:'+height+'px;left:'+sty_left+'px;top:'+sty_top+'px;">+1</em>';
		$("body").append(em);
		$("#upCouse").animate({
			"top":sty_top - 40,
			"opacity":"0",
			"fontSize":"18px"
		},800,function(){
			$(this).remove();
			addCollet = true;
		});
	};
	//换一批
	if(typeof($("#chageList")[0]) != 'undefined'){
		$("#chageList").click(function(){
			guessList();
		});
		function guessList(){
			var rnd = new Date().getTime();
			$.get('/?ct=account&ac=ajax_get_enjoy',{'change':rnd},function (data){
				var vhtml = '';
				$(data).each(function (i,items){
					vhtml += '<li><a href="'+items['extra']['ext']['vurl']+'" target="_blank"><img src="'+items['extra']['default']['imageLink']+'" alt="'+items['title']+'"><cite>'+items['title']+'</cite></a></li>';
				});
				$("ul.asComlist").html(vhtml);
			},'json')
		}
		guessList();
	}
	
	//百度分享
	if(typeof($("#bdshell_js")[0]) != 'undefined'){
			$("#bdshell_js").attr("src","http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + Math.ceil(new Date()/3600000))
	}

	//S:娱乐
	$(".hn-focus a, .hn-rowCom-list li, .hn-row02-focus").bind({
		mouseover:function(){
			$(this).addClass('hover');
		},
		mouseout:function(){
			$(this).removeClass('hover');
		}
	})
	//幻灯片切换
	if(typeof($(".nh-aside-slide")[0]) != 'undefined'){
		$(".hn-aside-nums").switchable('.nh-aside-slide > ul > li', { 
			effect: 'fade',
			circular:'true', 
			speed: .5
		}).autoplay();
		var api = $(".hn-aside-nums").switchable();
		$('#js-aside-right').click(function(){
			api.next();
		});
		$('#js-aside-left').click(function(){
			api.prev();
		});
	}
	$('.hn-slide ul').switchable('#js_hn_content > ol > li', {
		triggerType: 'mouse',
		effect: 'scroll',
		vertical:true,
		triggers:'li'
	}).autoplay().carousel();
	$('#nh_dis_num').switchable('#nh-scroll-music > ul > li', {
		triggerType: 'click',
		effect: 'scroll',
		steps: 5,
		visible: 5
	}).autoplay().carousel();
	var nh_dis_num = $("#nh_dis_num").switchable();
	$('#JS_nh_right').click(function(){
		nh_dis_num.next();
	});
	$('#JS_nh_left').click(function(){
		nh_dis_num.prev();
	});
	//时尚潮流
	$("#js_hnRow_tabs li").click(function(){
		var index = $(this).index();
		$(this).addClass('current').siblings('li').removeClass('current');
		$("#js_hnRow_content ul").eq(index).show().siblings("#js_hnRow_content ul").hide();
	})
	//金牌制作
	$('.hn-spec-num').switchable('#hn-spec-wrap > ul > li', {
		effect: 'scroll',
		steps: 5,
		visible: 5
	}).autoplay().carousel();
	//E:娱乐
});
//ready end

var nls_init = 1;
var nls_length = $(".notict li").size();
function newsListScroll(){
	$(".notict li").hide();
	$(".notict li").eq(nls_init).fadeIn();
	nls_init += 1;
	if(nls_init === nls_length){
		nls_init = 0;
	}
}

function AutoScroll(obj, height) {
    $(obj).animate({
        marginTop: height+"px"
    }, 800, function () {
        $(this).css({ marginTop: "0px" }).find("li:last").insertBefore(obj + " li:first").hide().fadeIn();
    });
};

function searchform(){
	var q = $('#searchTxt').val(), t = $('input[name=t]').val();
	if(t){
		self.location.href='/sech-'+t+'-'+q+'.html';  
	}else{
		self.location.href='/sech-'+q+'.html';
	}
};

//添加收藏
function AddFavorite(url,title){
    try{
        window.external.addFavorite(url, title);
    }
    catch(e){
        try{
            window.sidebar.addPanel(title, url, "");
        }
        catch(e){
            alert("加入收藏失败，请使用Ctrl+D进行添加");
        }
    }
};
function setHome(obj,hostname) {
    if (!$.browser.msie) {
        alert("您的浏览器不支持自动设置主页，请使用浏览器菜单手动设置。")
        return;
    }
    var host = hostname;
    if (!host) {
        host = window.location.href;
    }
    obj.style.behavior = 'url(#default#homepage)';
    obj.setHomePage(host);
};

//兼容firefox、ie浏览器event事件
if(window.addEventListener) { FixPrototypeForGecko(); } 
function FixPrototypeForGecko(){
	try{
		HTMLElement.prototype.__defineGetter__("runtimeStyle",element_prototype_get_runtimeStyle);
		window.constructor.prototype.__defineGetter__("event",window_prototype_get_event);
		Event.prototype.__defineGetter__("srcElement",event_prototype_get_srcElement);
		Event.prototype.__defineGetter__("fromElement", element_prototype_get_fromElement);
		Event.prototype.__defineGetter__("toElement", element_prototype_get_toElement);
	}catch(e){
	
	}
} 
function element_prototype_get_runtimeStyle() { return this.style; }
function window_prototype_get_event() { return SearchEvent(); }
function event_prototype_get_srcElement() { return this.target; } 
function element_prototype_get_fromElement() {
	var node;
	if(this.type == "mouseover") node = this.relatedTarget;
	else if (this.type == "mouseout") node = this.target;
	if(!node) return;
	while (node.nodeType != 1)
		node = node.parentNode;
	return node;
}
 
function element_prototype_get_toElement() {
	var node;
	if(this.type == "mouseout") node = this.relatedTarget;
	else if (this.type == "mouseover") node = this.target;
	if(!node) return;
	while (node.nodeType != 1)
		node = node.parentNode;
	return node;
}
 
function SearchEvent(){
	if(document.all) return window.event; 
	func = SearchEvent.caller; 
	while(func!=null){
		var arg0=func.arguments[0]; 
		if(arg0 instanceof Event) {
			return arg0;
		}
		func=func.caller;
	}
	return null;
}

//兼容IE的placeholder,type=password还没有更好的解决方案，用一个浮动层解决
$(function(){
	if(!placeholderSupport()){   // 判断浏览器是否支持 placeholder
		$('[placeholder]').focus(function() {
			var input = $(this);
			if (input.val() == input.attr('placeholder')) {
				input.val('');
				input.removeClass('placeholder');
			}
		}).blur(function() {
			var input = $(this);
			if (input.val() == '' || input.val() == input.attr('placeholder')) {
				input.addClass('placeholder');
				input.val(input.attr('placeholder'));
			}
		}).blur();
	};
})
function placeholderSupport() {
	return 'placeholder' in document.createElement('input');
}

//图片延迟加载
(function($) {
    $.extend($.fn, {
        molazy : function(container){
        	$(this).find('img').each(function (){
        		var _self  = $(this);
		        var org = _self.attr("data-original");
		        if(org){
		        	_self.attr('src', org).removeAttr('data-original');
		        }
		    });
        }
    });
})(jQuery);

//格式化json数据
(function($) {   
    $.type = function(o) {   
        var _toS = Object.prototype.toString;   
        var _types = {   
            'undefined': 'undefined',   
            'number': 'number',   
            'boolean': 'boolean',   
            'string': 'string',   
            '[object Function]': 'function',   
            '[object RegExp]': 'regexp',   
            '[object Array]': 'array',   
            '[object Date]': 'date',   
            '[object Error]': 'error'   
        };   
        return _types[typeof o] || _types[_toS.call(o)] || (o ? 'object' : 'null');   
    };
    var $specialChars = { '\b': '\\b', '\t': '\\t', '\n': '\\n', '\f': '\\f', '\r': '\\r', '"': '\\"', '\\': '\\\\' };   
    var $replaceChars = function(chr) {   
        return $specialChars[chr] || '\\u00' + Math.floor(chr.charCodeAt() / 16).toString(16) + (chr.charCodeAt() % 16).toString(16);   
    };   
    $.toJSON = function(o) {   
        var s = [];   
        switch ($.type(o)) {   
            case 'undefined':   
                return 'undefined';   
                break;   
            case 'null':   
                return 'null';   
                break;   
            case 'number':   
            case 'boolean':   
            case 'date':   
            case 'function':   
                return o.toString();   
                break;   
            case 'string':   
                return '"' + o.replace(/[\x00-\x1f\\"]/g, $replaceChars) + '"';   
                break;   
            case 'array':   
                for (var i = 0, l = o.length; i < l; i++) {   
                    s.push($.toJSON(o[i]));   
                }   
                return '[' + s.join(',') + ']';   
                break;   
            case 'error':   
            case 'object':   
                for (var p in o) {   
                    s.push('"' + p + '"' + ':' + $.toJSON(o[p]));   
                }   
                return '{' + s.join(',') + '}';   
                break;   
            default:   
                return '';   
                break;   
        }   
    };   
    $.evalJSON = function(s) {   
        if ($.type(s) != 'string' || !s.length) return null;   
        return eval('(' + s + ')');   
    };   
})(jQuery);

//cookie
Cookie = {
    set: function (name, value, expires, path, domain) {
        if (typeof expires == "undefined") {
            expires = new Date(new Date().getTime() + 1000 * 3600 * 24 * 365);
        }

        document.cookie = name + "=" + escape(value) + ((expires) ? "; expires=" + expires.toGMTString() : "") + ((path) ? "; path=" + path : "; path=/") + ((domain) ? ";domain=" + domain : "");

    },
    get: function (name) {
        var arr = document.cookie.match(new RegExp("(^| )" + name + "=([^;]*)(;|$)"));
        if (arr != null) {
            return unescape(arr[2]);
        }
        return null;
    },
    clear: function (name, path, domain) {
        if (this.get(name)) {
            document.cookie = name + "=" + ((path) ? "; path=" + path : "; path=/") + ((domain) ? "; domain=" + domain : "") + ";expires=Fri, 02-Jan-1970 00:00:00 GMT";
        }
    }
};

/**ie6 png**/
function pngfix(img){
    if (window.XMLHttpRequest) {return}
    if($.browser.msie && ($.browser.version == "6.0") && !$.support.style){
        var imgStyle = img.style.cssText;
        var strNewHTML = "<img src='/static/images/holder.png' class=\"" + img.className + "\" title=\"" + img.title + "\" style=\"width:" + img.clientWidth + "px; height:" + img.clientHeight + "px;" + imgStyle + ";" + "filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + img.src + "', sizingMethod='scale');\" />";
        img.outerHTML = strNewHTML;
    }
};
