var region = {
    returnArrya: function(n, p) {
        for (var j = 0; j < K.kind_region[n].length; j++) {
            if (K.kind_region[n][j][0] == p) {
                return K.kind_region[n][j]
            }
        }
    },
    addList: function(p, n) {
        $(".dptctdrp_list").eq(n).empty();
        for (var j = 0; j < p.length; j++) {
        	var op = "<li style='padding:0px 0px;'><a href='javascript:;' pid='" + p[j][0] + "'>" + p[j][1] + "</a></li>";
            if(p[j][0]!=33 && p[j][0]!=34 && p[j][0]!=35){
            	$(".dptctdrp_list").eq(n).append(op)
            }
        }
    }
};

function bind1() {
    $(".dptctdrp_list").eq(1).find("a").unbind("click");
    $(".dptctdrp_list").eq(1).find("a").bind("click",
    function(e) {
        outTextTerritory($(this).text(), 1, $(this).attr("pid"));
        $(".h5_dptctdrp a").eq(2).html('<em>请选择县/区</em><i></i>');
        $(".dptctdrp_list").hide();
        $(".dptctdrp_list").eq(2).show();
        $(".h5_dptctdrp a").removeClass("h5_dptdrp_on");
        $(".h5_dptctdrp a").eq(2).addClass("h5_dptdrp_on");
        region.addList(K.kind_region[$(this).attr("pid")], 2);
        bind2();
        bindH5();
    });
}
function bind2() {
    $(".dptctdrp_list").eq(2).find("a").unbind("click");
    $(".dptctdrp_list").eq(2).find("a").bind("click",
    function(e) {
        outTextTerritory($(this).text(), 2, $(this).attr("pid"));
        $(".depit_citydrp").hide();
        $(".dOrder-city-sel").removeClass("dOrder_city_on");
        $(".h5_dptctdrp a").removeClass("h5_dptdrp_on");
        for (var i = 0; i < $(".dOrder-city-sel b").length; i++) {
            $(".dOrder-city-sel b").eq(i).text($(".h5_dptctdrp a").eq(i).text()).attr("pid", $(".h5_dptctdrp a").eq(i).attr("pid"));
        }
        setCookie("user_province", $(".h5_dptctdrp a").eq(0).attr("pid") + "_" + $(".h5_dptctdrp a").eq(1).attr("pid") + "_" + $(".h5_dptctdrp a").eq(2).attr("pid"), 12);
        $(".ajax_deli_city").text($(".h5_dptctdrp a").eq(0).text());
        var wareId = $(".h5_dptctdrp a").eq(0).attr("pid");
        var proId = $("#comment_goodsid").val();
        initRespal(wareId, proId, -1);
    });
}
function bindH5() {
    $(".h5_dptctdrp a").unbind("click");
    $(".h5_dptctdrp a").bind("click",
    function(e) {
        var ind = $(".h5_dptctdrp a").index($(this));
        $(".h5_dptctdrp a").removeClass("h5_dptdrp_on");
        $(this).addClass("h5_dptdrp_on");
        $(".dptctdrp_list").hide();
        $(".dptctdrp_list").eq(ind).show()
    });
}
function outTextTerritory(text, index, pid) {
    $(".h5_dptctdrp a").eq(index).html('<em>'+text+'</em><i></i>').attr("pid", pid)
}
$(function(){
	//初始化送货地址
	   if (userArray[0] != "") {
	        var sa1 = region.returnArrya(1, userArray[0]);
	        $(".dOrder-city-sel b").eq(0).text(sa1[1]).attr("pid", sa1[0]);
	        $(".h5_dptctdrp a").eq(0).html('<em>'+sa1[1]+'</em><i></i>').attr("pid", sa1[0]);
	        if (userArray[1] != "" && userArray[1] != undefined) {
	            var sa = region.returnArrya(userArray[0], userArray[1]);
	            $(".dOrder-city-sel b").eq(1).text(sa[1]).attr("pid", sa[0]);
	            $(".h5_dptctdrp a").eq(1).html('<em>'+sa[1]+'</em><i></i>').attr("pid", sa[0]);
	            if (userArray[2] != "" && userArray[2] != undefined) {
	                var sa = region.returnArrya(userArray[1], userArray[2]);
	                $(".dOrder-city-sel b").eq(2).text(sa[1]).attr("pid", sa[0]);
	                $(".h5_dptctdrp a").eq(2).html('<em>'+sa[1]+'</em><i></i>').attr("pid", sa[0]);
	            }
	        }
	    } else {
	        $(".dOrder-city-sel b").eq(0).text("北京").attr("pid", 2);
	        userArray[0] = 2;
	    }
	    if (!userArray[1]) {
	        $(".h5_dptctdrp a").eq(1).html('<em>请选择市</em><i></i>');
	        $(".h5_dptctdrp a").eq(2).html('<em>请选择县/区</em><i></i>');
	    }
	    
	    region.addList(K.kind_region[1], 0);
	    region.addList(K.kind_region[userArray[0]], 1);
	    bindH5();
	    $(".h5_dptctdrp a").eq(2).unbind("click");
	    if (userArray[1] != undefined && userArray[1] != "" && userArray[1] != "undefined") {
	        region.addList(K.kind_region[userArray[0]], 1);
	        bindH5();
	        $(".h5_dptctdrp a").eq(2).unbind("click");
	    }
	    if (userArray[2] != undefined && userArray[2] != "" && userArray[2] != "undefined" && userArray[1] != undefined && userArray[1] != "" && userArray[1] != "undefined") {
	        region.addList(K.kind_region[userArray[1]], 2);
	        bindH5();
	    }
	    bind1();
	    bind2();
	    $(".dOrder-city-sel").click(function(e) {
	        $(".dOrder-city-sel").addClass("dOrder_city_on");
	        $(".depit_citydrp").show();
	        $(".h5_dptctdrp a").removeClass("h5_dptdrp_on");
	        $(".dptctdrp_list").hide();
	        e.stopPropagation();
	    });
	    $("body").click(function(e) {
	        $(".depit_citydrp").hide();
	        $(".dOrder-city-sel").removeClass("dOrder_city_on");
	    });
	    $(".dptctdrp_list,.depit_citydrp").click(function(e) {
	        e.stopPropagation();
	    });
	    $(".dptctdrp_list").eq(0).find("a").bind("click",
	    function(e) {
	        outTextTerritory($(this).text(), 0, $(this).attr("pid"));
	        $(".h5_dptctdrp a").eq(1).html('<em>请选择市</em><i></i>');
	        $(".h5_dptctdrp a").eq(2).html('<em>请选择县/区</em><i></i>');
	        $(".dptctdrp_list").hide();
	        $(".dptctdrp_list").eq(1).show();
	        $(".h5_dptctdrp a").removeClass("h5_dptdrp_on");
	        $(".h5_dptctdrp a").eq(1).addClass("h5_dptdrp_on");
	        region.addList(K.kind_region[$(this).attr("pid")], 1);
	        $(".h5_dptctdrp a").eq(2).unbind("click");
	        bind1();
	    });
	
	
	$('#_tzgotourl').live('click',function(){
		var tzactid =$('#_tzactid').val();
		var pid = $('#comment_goodsid').val();
		//window.location.href='http://sale.jiuxian.com/tz/set_list.htm?activityId='+tzactid+'&productId='+pid;
		window.open('http://sale.jiuxian.com/tz/set_list.htm?activityId='+tzactid+'&productId='+pid);
		
	})
	
	
	
	
	
	
	//放大镜
	 $(".show-pic").jqueryzoom();   
	//小图轮换
	var smallImg = $(".show-list-con li").length;
	var hideNum = smallImg-5;
	var smallWidth = $(".show-list-con li").width()+6;
	var curPos = 0;
	hideNum>0?hideNum=hideNum:hideNum=smallImg;
	$(".show-list-next").bind('click',function(){
		if(hideNum!=smallImg && curPos < hideNum)
		{
			curPos++;
			if(!$(".show-list-con").find("ul").is(":animated"))
			{
				$(".show-list-con ul").stop().animate({marginLeft:-smallWidth*curPos},250);
			}
		}else
		{
			curPos=hideNum;
		}
	})
	$(".show-list-prev").bind('click',function(){
		if(curPos>0)
		{
			curPos--;
			if(!$(".show-list-con").find("ul").is(":animated"))
			{
				$(".show-list-con ul").stop().animate({marginLeft:-smallWidth*curPos},250);
			}
		}else
		{
			curPos=0;
		}
	})
//选套装
	$(".set-con span").live('click',function(){
		var tzSum = $(this).find("b").attr('nmb');
			//tzSum = tzSum.substr(0,tzSum.length-1);
		$(".set-con span").removeClass("setOn");
		$(this).addClass("setOn");

		$(".dPrice-pri p em").text(cxPri);
		
		var singlePri = $(".setOn em").text();
		singlePri=singlePri.substr(1).substring(0,singlePri.length-3);
		var cxPri =  singlePri+"";
		if(cxPri.indexOf('.')==-1)
		{
			cxPri=cxPri+".00";
		}
		$(".dPrice-pri p em").text(cxPri);
		 jQuery("#_dmdyhtzyxz").html("已选择<span>"+tzSum+"瓶&nbsp;&nbsp;￥"+singlePri+"/瓶</span>").show();
		$('#_nub').val(tzSum);
	})
//购物数量加减
	
	$(".buyNub-nub-top").bind('click',function(){
		var buySum = $('#_nub').val();
		buySum= parseInt(buySum);
		var singlePri = $(".setOn em").text();
		singlePri=singlePri.substr(1).substring(0,singlePri.length-3);//price
		var tzSum = $(".set-con").find("span").eq(buySum).find("b").attr('nmb');
		//tzSum = tzSum.substr(0,tzSum.length-1).substr(1);//number
		var reg=new RegExp("^[1-9][0-9]*$");
		if(!reg.test(buySum)||buySum>99999){
			buySum=1;
		}else{
			buySum++;
		    checkProNum(buySum,1);
		}
		
		if(buySum>=99)
		{
		
		 buySum=99;
		 $(".buyNub-nub").find("i").eq(1).addClass("buyNub-nub-top-s");
		}
	else
		{
		 $(".buyNub-nub").find("i").eq(1).removeClass("buyNub-nub-top-s");
		}
		
		if(buySum>1)
		{
			$(".buyNub-nub").find("i").removeClass("buyNub-nub-blus");
		}
		$(".buyNub-nub").find('input').val(buySum);
		if($(".dOrder-set .set-con").length>0)
			{
				for(var i=0;i<$(".dOrder-set span").length;i++)
					{
					
					
						if(parseInt($(".set-con").find("span").eq(i).find("b").attr('nmb'))==buySum)
							{
								$(".set-con").find("span").removeClass("setOn");
								$(".set-con").find("span").eq(i).addClass("setOn");
								var singlePri = $(".set-con").find("span").eq(i).find("em").text();
								singlePri=singlePri.substr(1).substring(0,singlePri.length-3);
								var cxPri =  singlePri+"";
								if(cxPri.indexOf('.')==-1)
								{
									cxPri=cxPri+".00";
								}
								$(".dPrice-pri p em").text(cxPri);
							}
					}
				jQuery("#_dmdyhtzyxz").html("已选择<span>"+buySum+"瓶&nbsp;&nbsp;￥"+singlePri+"/瓶</span>").show();
			}
		
	});
	$(".buyNub-nub-blus-c").bind('click',function(){
		var buySum = $('#_nub').val();
		buySum= parseInt(buySum);;
		var reg=new RegExp("^[1-9][0-9]*$");
		var tzSum = $(".set-con").find("span").eq(buySum).find("b").attr('nmb');
		//tzSum = tzSum.substr(0,tzSum.length-1).substr(1);//number
		if(!reg.test(buySum)){
			buySum=1;
		}else{
			buySum--;
		    checkProNum(buySum,1);
		}
		
		if(buySum>=99)
		{
		
		 buySum=99;
		 $(".buyNub-nub").find("i").eq(1).addClass("buyNub-nub-top-s");
		}
	else
		{
		 $(".buyNub-nub").find("i").eq(1).removeClass("buyNub-nub-top-s");
		}
		if(buySum<=1)
		{
			buySum=1;
			$(".buyNub-nub").find("i").eq(0).addClass("buyNub-nub-blus");
		}		
		$(".buyNub-nub").find('input').val(buySum);
		if($(".dOrder-set .set-con").length>0)
		{
			var singlePri = $('#_baknowPriceStr').val();
			
			
			for(var i=0;i<$(".dOrder-set span").length;i++)
				{
				
				if(parseInt($(".set-con").find("span").eq(0).find("b").attr('nmb'))>buySum)
				{
				$(".set-con").find("span").removeClass("setOn");
				}else{
					if(parseInt($(".set-con").find("span").eq(i).find("b").attr('nmb'))<=buySum)
						{
							$(".set-con").find("span").removeClass("setOn");
							$(".set-con").find("span").eq(i).addClass("setOn");
							singlePri = $(".set-con").find("span").eq(i).find("em").text();
							singlePri=singlePri.substr(1).substring(0,singlePri.length-3);
				
							
						}
				}
				}
			var cxPri =  singlePri+"";
			if(cxPri.indexOf('.')==-1)
			{
				cxPri=cxPri+".00";
			}
			$(".dPrice-pri p em").text(cxPri);
			jQuery("#_dmdyhtzyxz").html("已选择<span>"+buySum+"瓶&nbsp;&nbsp;￥"+singlePri+"/瓶</span>").show();
		}
		
	});
//一周排行榜
	var nRank=$(".dLeft-ranking li").index($(this));
	for(nRank=0;nRank<=2;nRank++){
		$(".dLeft-ranking li").eq(nRank).find(".rUnfold-nub2").attr('class','rUnfold-nub');
	}
	$(".dLeft-ranking li").last().children(".rank-packup").css('border-bottom','none');
	$(".dLeft-ranking li").last().children(".rank-unfold").css('border-bottom','none');
	$(".dLeft-ranking").find("li").bind('mouseover',function(){
		$(this).children(".rank-unfold").show();
		$(this).children(".rank-packup").hide();
		$(this).siblings("li").find(".rank-unfold").hide();
		$(this).siblings("li").find(".rank-packup").show();
	});
//优惠套装
 /*$(".detailSetInfo").find(".detailSetEmble ul li").each(function(){
 	if($(this).length>3)
 	{
 		$(this).parents(".detailSetEmble").css('overflow-x','scroll');
 	}
 })*/
	
//商品介绍
	var porLs,porLss;
	$(".productTabTit li").bind('click',function(){
		
		porLs = $(".productTabTit li").index($(this));
		if(!$(this).hasClass('on'))
		{			
			$('#answerArea').attr('class','detBox_title');
		}
		
		$(".productTabTit li").removeClass("on");
		$(".float-detBox-title li").removeClass("on");
		$(".float-detBox-title li").eq(porLs).addClass("on");
		$(this).addClass("on");
		$(".dRight-wrap").find(".dRight-tab-con").hide();
		$(".dRight-wrap").find(".dRight-tab-con").eq(porLs).show();

		
		if(porLs==1){
			var plnubs=$('#_plnub').html();
			initpl(plnubs);
		}else if(porLs==2){
			initzx(zxtype);
			getCountConsultContent();//函数定义在：detail_function.js
		}
	});
	
	
	$(".float-detBox-title li").bind('click',function(){
		porLss = $(".float-detBox-title li").index($(this));
		$(".productTabTit li").removeClass("on");
		$(".float-detBox-title li").removeClass("on");
		$(".productTabTit li").eq(porLss).addClass("on");
		$(this).addClass("on");
		$(".dRight-wrap").find(".dRight-tab-con").hide();
		$(".dRight-wrap").find(".dRight-tab-con").eq(porLss).show();
		if(porLss==1){
			var plnubs=$('#_plnub').html();
			initpl(plnubs);
		}else if(porLss==2){
			initzx(zxtype);
			getCountConsultContent();
		}
	});
//商品回答
	$(".u-consulting-title li").bind('click',function(){
		var ansLs = $(".u-consulting-title li").index($(this));
		$(".u-consulting-title li").removeClass("on");
		$(this).addClass("on");
		$(".u-consulting-info-box").find(".u-consulting-info-con").hide();
		$(".u-consulting-info-box").find(".u-consulting-info-con").eq(ansLs).show();
		zxtype=ansLs;
		initzx(ansLs);
	});
//商品介绍固定定位
	
	var answerTop = $("#answerArea").offset().top;
	$(window).bind('scroll',function(){
		
			
			
			if($(document).scrollTop()>answerTop){
				//$(".float-detBox-title-bg").show();
				$("#answerArea").attr('class','detBox_title_fixed');
				$("#answerArea").find('.detailSetBtn-box').show();
			}else
			{
				$("#answerArea").attr('class','detBox_title');
				$("#answerArea").find('.detailSetBtn-box').hide();
				//$(".float-detBox-title-bg").hide();
			}
		
		
	})
//横幅定位
	var toTop = $(".detail-box2").offset().top;
	$("#answerArea .productTabTit li").bind('click',function(){
		 $(window).scrollTop(toTop);
	})
//二维码显隐
	$(".detailCode").bind('mouseover',function(){
		$(this).addClass("detailCode-on")
		$(".detailCode-t").show();
	}).bind('mouseout',function(){
		$(this).removeClass("detailCode-on")
		$(".detailCode-t").hide();
	});
//评分
	$(".u-comments-star a").mouseover(function(){
		var px = $(".u-comments-star a").index($(this));
		$(".u-comments-star a").css('background-position','-63px -34px');
		for(var i=0;i<=px; i++)
		{
			$(".u-comments-star a").eq(i).css('background-position','-85px -34px');
		}
		score = px+1;
	});


//收藏商品
	$(".u-cell").bind('click',function(){
		jQuery.ajax({
			 type:'post',
			 url:'/pro/selectLoginSession.htm?t='+getCurDate(),
			 data:{},
			 dataType:'json',
			 async:true,
			 success:function(data){
				 if(data && data.code == 0 && data.loginUser){
					 $.getJSON("/pro/saveCollect.htm",{'proId':goodsId},function(data){
						if(data){
							if(data.code == -1){
								alert('收藏失败!');
								return false;
							}else if(data.code == 0){
								alert('收藏成功!');
								$(this).find("em").show().animate({'top':'-24px','opacity':'0.3'},500,function(){
									$(".u-cell").find("a").text("已收藏");
									$(this).remove();
								});
								ga('send','event','store','button','goods-'+goodsId);
								return false;
							}else if(data.code == 1){
								//alert("您还没有登录，请先登录！");
								window.location.href=domain_passprot+'/login.htm';
								return false;
							}else if(data.code == 2){
								alert("您已经收藏过此商品了！");
								$(this).find("em").show().animate({'top':'-24px','opacity':'0.3'},500,function(){
									$(".u-cell").find("a").text("已收藏");
									$(this).remove();
								});
								return false;
							}
						}
					 });
				 }else{
				     //alert("您还没有登录，请先登录！");
					 window.location.href=domain_passprot+'/login.htm';
				     return false;
				 }
			 }
	    });
		
			
	})
//浏览历史
	 viewhis(goodsId);
	 $("#_viewll").load("/pro/getViewHisInfo.htm?t="+getCurDate(), {},function() {
		 	var sPages = $(".d-history-list").find("li").length;
		 	var sWidth = $(".d-history-list").find("li").width();
		 	var _thisMove = $(".d-history-list ul");
		 	var curPages = 1;
			$(".u-paging .next").click(function(){
				if(!_thisMove.is(":animated"))
				{
					curPages++;
					if(curPages==sPages)
					{
						$(this).addClass("next-stop");
					}
					if(curPages>sPages)
					{
						curPages=sPages;
					}else
					{
						_thisMove.animate({'marginLeft':-(sWidth*(curPages-1))},300);
						$(".u-paging .prve").removeClass("prve-stop");
					}
				}
				$(".u-paging b").text(curPages);
			})
			$(".u-paging .prve").click(function(){

				if(!_thisMove.is(":animated"))
				{
					curPages--;
					if(curPages==1)
					{
						$(this).addClass("prve-stop");
						curPages=1
					}else
					{
						$(this).removeClass("prve-stop");
						$(".u-paging .next").removeClass("next-stop");
					}
					if(curPages>=1)
					{

						_thisMove.animate({'marginLeft':-(sWidth*(curPages-1))},300);
						
					}else
					{
						$(this).addClass("prve-stop");
						curPages=1;
					}
				}
				$(".u-paging b").text(curPages);
			})
		 
	 });
	
 	
//酒友评论显示限制
	var potCon = $(".jyScore-comText").text().length;
	var comHei = $(".jyScore-comText").height();
	if(comHei>72)
	{
		$(".jyScore-comText").css({'height':'69px','overflow':'hidden'});
	}
//IE隐藏固定定位
	if ($.browser.msie && ($.browser.version == "6.0") && !$.support.style) { 
		$(".float-detBox-title-bg").remove();
	} 
//加入购物车
	$(".buyNub-buy .u-buy").live('click',function(){
		toCart(1,goodsId,0,0,0,null);
		setTimeout(function(){
		$("#u-buy-layId").hide();
		$("#u-buy-layId2").hide();
		$('.depict-order,.buyNub-buy').css('z-index','2');
		return false;
	},5000); 
	});
	
	$("#_ysiscanyd").live('click',function(){
		toAdvance(goodsId,nub);
	});
	
	//加入购物车
	//$(".buyNub-buy .u-buy").click(function(){
	//	orderBuy(goodsId);
	//});

	$("#u-buy-layId .u-buy-close,#u-buy-layId .u-buy-g,#u-buy-layId2 .u-buy-close,#u-buy-layId2 .u-buy-g").click(function(){
		$("#u-buy-layId").hide();
		$("#u-buy-layId2").hide();
		$('.depict-order,.buyNub-buy').css('z-index','1');
		return false;
	})
//优惠类目
	$(".pri-con-pre").live('mouseover',function(){
		$(this).find(".u-coupons-prompt").show();
	});
	$(".pri-con-pre").live('mouseout',function(){
		$(".u-coupons-prompt").hide();
	})
	$(".u-coupons-prompt").live('mouseout',function(){
		$(".u-coupons-prompt").hide();
	})
	$(".u-coupons-prompt i").live('click',function(){
		$(".u-coupons-prompt").hide();
	})
	
//set content area height
	//setContHeigth();
//判断是否存在套装，设置margin值

//点击评论跳转
	$(".jyScore-com a").click(function(){
		$(".productTabTit").find("li").eq(1).trigger("click")	
	})	
//送货地址
//	$("body").click(function(e){
//		$(".depit_citydrp").hide();
//		$(".dOrder-city-sel").removeClass("dOrder_city_on");
//	});
//	$(".depit_citydrp").click(function(event){
//		event.stopPropagation();
//	});	
//	$(".dOrder-city-sel").click(function(event){
//		event.stopPropagation();
//		$(this).addClass('dOrder_city_on');
//		$(".depit_citydrp").show();
//	});
//悬浮购物车
	$(".detailSetBtn-box .detailSetBtn").click(function(){
		toCart(2,goodsId,0,0,0,null);
		setTimeout(function(){
			$("#u-buy-laycl").hide();
			$("#u-buy-laycl2").hide();
			return false;
	},5000); 
	});
	$("#u-buy-laycl .u-buy-close,#u-buy-laycl2 .u-buy-close,.u-buy-gon-small .u-buy-g").click(function(){
		$("#u-buy-laycl").hide();
		$("#u-buy-laycl2").hide();
	});
	
	
	//最终购买弹出层
	$("#u-buy-left_one .u-buy-close,#u-buy-left_one .u-buy-g, #u-buy-left_two .u-buy-g, #u-buy-left_two .u-buy-close").live('click',function(){
		$('#u-buy-left_one').hide();
		$('#u-buy-left_two').hide();
		
	});
	//
	$(".dLeft-weiboPic").mouseover(function(){
		$(".detailCode-r").show();
	}).mouseout(function(){
		$(".detailCode-r").hide();
	})
	
	var tzpricecallback = function(data){
		 for(var id in data){   
		     var prices = data[id];
			 jQuery("[goodId='"+id+"']").html('￥'+prices["np"]);
			 jQuery("[markGoodId='"+id+"']").html('￥'+prices["mp"]);
			 
		}

	};
	 	var proIds = [];
	  //获取商品当前价
		 $("[goodId]").each(function(){
		    var id=$(this).attr("goodId");
			proIds.push(id);
		});	
		
       getProductActPrice(proIds.join(","), tzpricecallback);

})

