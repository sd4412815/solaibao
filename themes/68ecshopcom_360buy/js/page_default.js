$(document).ready(function() {
    objBase.LoadImages($("img"));
    var elem_mnavbox   = $("#main-nav")[0];
    var elem_arrow     = $("#sale-box .tab-arrow")[0];
    var elem_sb_lis    = $("#sale-box li");
    var elem_lbuy_box  = $("#limtbuy-box")[0]; 
    var elem_flist     = $("#float-list")[0];
    var elem_fl_dls    = $("#float-list dl");
    var elem_slidebar  = $("#slide .mfpSlide-nav")[0];
    var elem_tvbanner  = $("#topver-banner")[0];
    var elem_noticebox = $("#notice-box")[0];
    var cur_slide_id   = 0;
    var acty_frame = $("#acty-frame")[0];

    var nav_min_top;
    if (elem_lbuy_box) {
        nav_min_top = objBase.GetElemPosition(elem_lbuy_box).y;
    }
    else if ($("#sale-box")[0]) {
        nav_min_top = objBase.GetElemPosition($("#sale-box")[0]).y;
    }
    //var nav_min_top = elem_lbuy_box ? objBase.GetElemPosition(elem_lbuy_box).y : objBase.GetElemPosition($("#sale-box")[0]).y;

    attachSlide();  //绑定轮播图
    attachNotice(); //绑定通知栏
    fixElems();

    $(".first-show").hover(function() {
        $('#float-list dl dt').removeClass("hover");
    });

    $("#sale-box li").hover(function(e) {
        var idx = $(this).index() - 1;        
        var _this = $(this)[0];
        elem_arrow.style.left = (idx == 0 ? _this.offsetLeft : _this.offsetLeft - 2) + "px";
        elem_arrow.style.width = (idx == 0 ? _this.offsetWidth - 1 : _this.offsetWidth + 1) + "px";
        for (var i = 0; i < elem_sb_lis.length; i++)
            $("#con_two_" + (i + 1))[0].style.display = (_this == elem_sb_lis[i]) ? "block" : "none";
    });

    $("#slide").hover(function() {
        $('#float-list dl dt').removeClass("hover");
    });

    $('#toplist-0 li').hover( function () { $('#toplist-0 li').removeClass('hover'); $(this).addClass('hover')});
    $('#toplist-1 li').hover( function () { $('#toplist-1 li').removeClass('hover'); $(this).addClass('hover')});
    $('#toplist-2 li').hover( function () { $('#toplist-2 li').removeClass('hover'); $(this).addClass('hover')});
    $('#toplist-3 li').hover( function () { $('#toplist-3 li').removeClass('hover'); $(this).addClass('hover')});
    $('#toplist-4 li').hover( function () { $('#toplist-4 li').removeClass('hover'); $(this).addClass('hover')});

    if (acty_frame) {
        var tmr = window.setInterval(function() {
            if (acty_frame.getAttribute("frame-height")) {
                window.clearInterval(tmr);
            }
        }, 1);
    }
    else {
        $(window).scroll(function() {
            docScrollFix();
        });    
    }

    function docScrollFix() {
        if (objBase.DocScrollTop() > nav_min_top) {
            $("#main-nav-repl")[0].style.display = "block";
            elem_mnavbox.setAttribute("locked", "locked");
            elem_mnavbox.style.cssText = isIE6 ? "position: absolute; top: " + docScrollTop() + "px; z-index: 1000" : "position: fixed; top: 0px; z-index: 1000";
            for (var i = 0; i < elem_fl_dls.length; i++) {
                elem_fl_dls[i].style.display = "none";
                objBase.GetElems("DT", elem_fl_dls[i])[0].className = "";
                objBase.GetElems("DD", elem_fl_dls[i])[0].style.display = "none";
            }
        }
        else {
            $("#main-nav-repl")[0].style.display = "none";
            elem_mnavbox.removeAttribute("locked");
            elem_mnavbox.style.cssText = "";
            for (var i = 0; i < elem_fl_dls.length; i++) {
                elem_fl_dls[i].style.display = "block";
            }
        }        
    } 

    $(window).resize(function() {
        fixElems();
    });

    function attachSlide() {
        var elems = $("#topver-banner li");        
        var slide2 = function(id_) {
            for (var i = 0; i < elems.length; i++)
                elems[i].style.display = (elems[i].getAttribute("relate-id") == id_) ? "block" : "none";
        };
        slide2(0);        
        
        var _titles = $("ul.mfpSlide-nav li");
        var _bodies = $("ul.mfpSlide-con li");
        var _count = _titles.length;
        var _current = 0;
        var _intervalID = null;
        var stop = function() {
            window.clearInterval(_intervalID);
        };
        var slide = function(opts) {
	        if (opts)
                _current = opts.current || 0;
            else 
                _current = (_current >= (_count - 1)) ? 0 : (++_current);

            if (_current == cur_slide_id)
                return;

            _bodies.filter(":visible").fadeOut();
		    _bodies.eq(_current).fadeIn();
            slide2(_current);
            cur_slide_id = _current;

	        _titles.removeClass("selected").eq(_current).addClass("selected");
        };
        var go = function() {
	        stop();
	        _intervalID = window.setInterval(function() { 
                slide(); 
            }, 5000);
        };
        var itemMouseOver = function(target, items) {
	        stop();
	        var i = $.inArray(target, items);
	        slide({ current: i });
        };
        _titles.hover(
            function() {
                if($(this).attr('class') != 'cur')
                    itemMouseOver(this, _titles);
                else
                    stop();
            },
            go
        );
        _bodies.hover(stop, go);
        go();
    }

    function attachNotice() {
        if (elem_noticebox) {
            startMarquee(33, 1, 3000, elem_noticebox);
        }
    }

    function startMarquee(lh_, speed_, delay_, elem_) { 
        var t; 
        var p = false;         
        elem_.innerHTML += elem_.innerHTML; 
        elem_.onmouseover = function() { p = true; } 
        elem_.onmouseout = function() { p = false; } 
        elem_.scrollTop = 0; 
        function start() { 
            t = window.setInterval(function() {
                if (elem_.scrollTop % lh_ != 0) { 
                    elem_.scrollTop +=1; 
                    if (elem_.scrollTop >= elem_.scrollHeight / 2)
                    elem_.scrollTop = 0; 
                }
                else { 
                    clearInterval(t);
                    window.setTimeout(start, delay_); 
                }
            }, speed_); 

            if (!p)
                elem_.scrollTop += 1;
        } 
        window.setTimeout(start, delay_); 
    }

    function fixElems() {
        try {            
            if (acty_frame) {
                var tmr = window.setInterval(function() {
                    if (acty_frame.getAttribute("frame-height")) {
                        window.clearInterval(tmr);
                        fix(parseInt(acty_frame.getAttribute("frame-height"), 10));
                    }
                }, 1);
            }
            else {
                fix(0);
            }
        }
        catch(e) {}
        function fix(height_) {
            var left_v = objBase.GetElemPosition(elem_flist).x + elem_flist.offsetWidth;
            if (!elem_slidebar) {
                return;
            }
            elem_slidebar.style.cssText = "visibility: visible; left: " + (left_v + 6) + "px;";
            elem_tvbanner.style.cssText = "visibility: visible; left: " + (left_v + 770) + "px;";
            var cnt = $(".topacty-imgbox").length;
            if (cnt > 0) {
                var top_v = 0;
                for (var i = 0; i < cnt; i++) {
                    top_v += $(".topacty-imgbox")[i].offsetHeight;
                }
                elem_tvbanner.style.top = (177 + top_v + height_) + "px";
            }
        }
    }
});