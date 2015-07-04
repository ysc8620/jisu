$(function(){

	// 收藏
	$(".collect i").mouseenter(function(){
		$(this).siblings('span').fadeIn();
	})

	$(".collect").mouseleave(function(){
		$(this).find('span').fadeOut();
	})


	$(".collect").click(function(){

		$(this).hide();
		$(this).parents('.collection').find('.cancel').show();
		$(this).parents('.collection').find('.cancel').children('span').show();
		$(this).parents('.collection').find('.cancel').children('span').text('已收藏');



	})


	$(".cancel").mouseleave(function(){
		$(this).find('span').hide();
		$(this).find('span').text('取消');
	})

	$(".cancel i").mouseenter(function(){
		$(this).siblings('span').show();
	})

	$(".cancel").click(function(){
		$(this).hide();
		$(this).parents('.collection').find('.collect').show();
		$(this).parents('.collection').find('.collect').children('span').text('收藏');
	})

    // start
    var addCollet = true;
    var OOFA = Cookie.get("V114LA");
    var uid;
    if(OOFA){
        //判断登陆获取用户数据
        $.ajax({
            type:'post',
            async:false,//把异步请求false才能读到内部的变量
            url:'/?ct=account&ac=ajax_get_current_userinfo',
            success:function(data){
                var data = $.evalJSON(data);
                uid = data['user_id'];
                var logHtml = '<p class="loged" uid="'+uid+'"><a href="/?ct=account" class="u">'+data['show_name']+'</a><a href="/?ct=account&ac=logout">退出</a></p>';
                $(".userlog").html(logHtml);
            }
        })
    };

    $(".cancel").click(function(){
        if(!$('.collect').hasClass("posted")){
            alert('您没有收藏，无须此操作。');
            return false;
        }
        var vid = $('.collectVid').val();
        delCollection(vid);
    })
    //会员中心收藏
    $(".collect").click(function (e){
        if($(this).hasClass("posted")){
            alert('您已收藏，无须重复操作。');
            return false;
        }
        var vid = $('.collectVid').val();
        ajaxCheckPost_new(e,vid);
    });
    $(".collect").click(function (e){
        if(!OOFA){
            if(confirm('您好，收藏请先登陆~')){
                window.location.href = '/?ct=login';
                return false;
            }
        }else if($(this).hasClass("posted")){
            alert('您已收藏，无须重复操作。')
            return false;
        }else{
            ajaxCheckPost_new(e);
        }
    })
    if(typeof($(".collection")[0]) != 'undefined'){
        checkCollet_new();
    };
    if(typeof($(".usHistoryList")[0]) != 'undefined'){
        $(".usHistoryList li").each(function(){
            var vid = $('.collectVid').val();
            checkCollet_new(vid);
        })
    };
    //判断是否已经收藏过
    function checkCollet_new(vid){
        var vid  = vid || $('.collectVid').val();
        if(vid && uid){
            $.ajax({
                type:'post',
                url:'/?ct=account&ac=ajax_get_isfav',
                data:{uid:uid,vid:vid},
                success:function(st){
                    if(st == '1'){
                       $(".collect").hide();
                       $(".cancel").show();
                    }
                }
            });
        }
    }
    //提交影片收藏
    function ajaxCheckPost_new(event,vid){
        var cate = $(".videoClass").val() || '';
        var id   = $(".collectVid").val() || vid;
        if(cate<1 || id<1)return false;
        $.ajax({
            type:'post',
            url:'/?ct=account&ac=ajax_add_favourites',
            data:{'class':cate,'vid':id},
            success:function(){
                //$(".collect").addClass("posted").html("<em></em>已收藏");
                $(".collect").addClass("posted");
                $(".collect").hide();
                $(".collect").parents('.collection').find('.cancel').show();
                $(".collect").parents('.collection').find('.cancel').children('span').show();
                $(".collect").parents('.collection').find('.cancel').children('span').text('已收藏');

                //setPosEle(event);
                addCollet = false;
            },
            error:function(){
                alert('很抱歉，收藏失败，请稍后再试！')
            }
        })
    }

    //删除收藏
    function delCollection(vid){
        if(vid<1 || uid<1)return false;
        if (confirm('确认要删除吗？')) {
            $.ajax({
                type:'post',
                url:'/?ct=account&ac=ajax_del&type=1',
                data:{'vid':vid,'uid':uid},
                success:function(){
                    $(".collect").removeClass("posted");
                    $('.cancel').hide();
                    $('.cancel').parents('.collection').find('.collect').show();
                    $('.cancel').parents('.collection').find('.collect').children('span').text('收藏');
                },
                error:function(){
                    alert('很抱歉，删除失败，请稍后再试！')
                }
            })
        };
        return false;
    }
    // end


	//评分
	var score = $(".start dt").text();
	if(score>10){
		score=10;
		$(".start dl dt b").text(score);
	}
	if(score==0){

		$(".start dl dd").removeClass();

	}else if(score<2){

		$(".start dl dd").removeClass().eq(0).addClass('mark');


	}else{

		var scoreNum = Math.floor(score/2);
		var scoreRound = Math.round(score/2)-1;
		$(".start dl dd:lt("+scoreNum+")").removeClass().addClass('fullStart');
		$(".start dl dd").eq(scoreRound).addClass('mark');

	}

	//右侧评价
	$(".evaluation ul li a").click(function(){


		var vid = $(".collectVid").val();
		var cookievideo = Cookie.get('video_'+vid);
		 if(cookievideo==vid){

            alert('您已经操作过了');
            return false;

        }else{

        	var ofLeft = $(this).position().left;
			var link1 = $(this).parents('.evaluation').find('.link1');
			var textNum = parseInt($(this).siblings('b').text());

        	$(this).siblings('b').text(textNum+1);
			link1.css('left',ofLeft);
			link1.show();
			link1.animate({
				top: -60,
				opacity: 0
			},700);
			
			shardText();

        }

		
		

	})


	//分享闪烁
	function shardText(){

		var timeInd = 0;
		timer = setInterval(function(){

			if(timeInd<2){
				$(".movieDeRight .shard").fadeToggle();
				timeInd++;
			}else{
				clearInterval(timer);
			}
			
		},300)

	}

	$(".detailsType h2>a").click(function(){
		var deInd =$(".detailsType h2>a").index(this);
		$(".deTypeUl>li").hide().eq(deInd).show();
		$(".detailsType h2 a").removeClass('titleHv');
		$(this).addClass('titleHv');
		
	
	})


	$(".filmCritics h2 a").click(function(){
		var filmInd = $(".filmCritics h2 a").index(this);
		$(".filmCon .fitem").hide().eq(filmInd).show();
		$(".filmCritics h2 a").removeClass('titleHv');
		$(this).addClass('titleHv');
	})


	$(".filmNum a").click(function(){

		var fInd = $(".filmNum a").index(this);
		$(".filmText ul").hide().eq(fInd).show();
		$(".filmNum a").removeClass('flNumHv');
		$(this).addClass('flNumHv');

	})


	//文字省略
	function textMore(className){
		$(".textAll").hide();
		$(".packUp").hide();
		$(".textMore").click(function(){
			$(this).parent().hide();
			$(this).parents('p').find('.textAll').show();
			$(this).parents('p').find('.packUp').show();
		})

		$(".packUp").click(function(){
			
			$(this).parent().hide();
			$(this).parents('p').find('.textOmit').show();
			
		})
	}

	textMore();

$(".youLike h2 a").click(function(){
		var ylInd = $(".youLike h2 a").index(this);
		$(".youLike .youLikeItem").hide();
		$(".youLike .youLikeItem").eq(ylInd).show();
		$(".youLike h2 a").removeClass('titleHv').eq(ylInd).addClass('titleHv');

	})


	// 分类检索
	$(".movieType ul li").mouseenter(function(){

		var typeInd = $(".movieType ul li").index(this);
		$(".movieType ul li a").removeClass('typeHv').eq(typeInd).addClass('typeHv');
		$(".typeCon .typeItem").hide().eq(typeInd).show();

	})


	//点击线路
	$(".playItem ul li a").click(function(){
		$(".playItem ul li a").removeClass('plHv');
		$(this).addClass('plHv');
		var tsLeft = $(this).position().left;
		$(".triangle").css('left',tsLeft+30);

	})

	//单选按钮
	$(".mlBottomLeft a").click(function(){
		$(this).siblings('a').removeClass('radioEd');
		$(this).addClass('radioEd');
	})



	//播放页侧边栏
	var ind = 0;
	var plLength = $("#playSidebarCon .moduleTwo").length;
	$(".dePlaySidebar .page .par").click(function(){
		
		if(ind==0){
			
			ind=plLength-1;
			playPage(ind);

		}else{
			
			ind--;
			playPage(ind);
		}


	})

	$(".dePlaySidebar .page .next").click(function(){
		
		if(ind==plLength-1){

			ind=0;
			playPage(ind);
		}else{

			
			ind++;
			playPage(ind);
		}
		
		

	})


	function playPage(ind){

		$("#playSidebarCon .moduleTwo").fadeOut().eq(ind).fadeIn();
		$(".dePlaySidebar .pageNum i").text(ind+1);

	}

})