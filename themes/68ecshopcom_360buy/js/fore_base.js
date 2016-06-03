var IE, FIREFOX, CHROME, OPERA, SAFARI
var isIE7, isIE6, isIE8, isIE9, isIE10, isIE11;

var SERVER = {
	DOMAIN_NAME: "www.no5.com.cn",
	CDN_DOMAIN_NAME: "photo.no5.com.cn",
	IP: ["123.103.13.224",
		 "123.103.13.225",
		 "123.103.13.232"]
};

var cart = {
    add: function(pid) {
	    if(window.name=="shoppingcar"){window.name="";}
        var httpHost = (objBase.IsLocalAccess()) ? "http://192.168.0.191" : "";
        var url = httpHost + "/shopping/checkout_addtocart.asp?productid=" + String(pid) + "&r=" + new Date().getTime(); var w=1024,h=800,l,t,aw=window.screen.availWidth,ah=window.screen.availHeight;
        if (aw<=w||ah<=h){ w=aw-30; h=ah-180;}l=parseInt((aw-w)/2,10)-5; t=parseInt((ah-h)/2,10)-60;if (t<30){ h=ah-180; t=parseInt((ah-h)/2,10)-60; } if (aw>=1440){ w=1280; l=parseInt((aw-w)/2,10)-5;}
        var nw=window.open (url,"shoppingcar","width="+w+",height="+h+",top="+t+",left="+l+",menubar=1,location=1,toolbar=1,scrollbars=1,resizable=1");nw.focus();            
    },
    del: function(pid, callback_) {
        $.ajax({  
            type: "get",
            dataType: "json",
            url: "/ajax/cart_del.asp?pid=" + String(pid),
            success: function(ret) { if (callback_) { callback_(); } }
        });                   
    },
    refreshBar: function() {
        $.ajax({  
            type: "get",
            dataType: "json",
            url: "/ajax/get_page_cart_bar.asp",  
            success: function(ret) { $("#page-cartbar .shoplist")[0].innerHTML = ret[0]; $("#page-cartbar .trigger")[0].innerHTML = "购物车<strong>" + String(ret[1]) + "</strong>件"; }
        });           
    }
};

var LINK = {
    gotop: function() {
        $("html, body").animate({scrollTop:0}, "fast");
    },
    onlineService: function(flag_) {
	    var w = 700, h = 500;
	    var t = (window.screen.availHeight - h) / 2;
	    var l = (window.screen.availWidth - w) / 2;
		var nw = window.open ("http://www.no5.com.cn/OnLineService_2011/RedirectToNewChat.asp?actflag=" + flag_,"ols2009","width="+w+",height="+h+",top="+t+",left="+l+",scrollbars=0,menubar=0,resizable=0,toolbar=0,resizable=0");
		nw.focus();
    }
};

/*---------- json parse begin ----------*/
window.jsonParse=function(){var r="(?:-?\\b(?:0|[1-9][0-9]*)(?:\\.[0-9]+)?(?:[eE][+-]?[0-9]+)?\\b)",k='(?:[^\\0-\\x08\\x0a-\\x1f"\\\\]|\\\\(?:["/\\\\bfnrt]|u[0-9A-Fa-f]{4}))';k='(?:"'+k+'*")';var s=new RegExp("(?:false|true|null|[\\{\\}\\[\\]]|"+r+"|"+k+")","g"),t=new RegExp("\\\\(?:([^u])|u(.{4}))","g"),u={'"':'"',"/":"/","\\":"\\",b:"\u0008",f:"\u000c",n:"\n",r:"\r",t:"\t"};function v(h,j,e){return j?u[j]:String.fromCharCode(parseInt(e,16))}var w=new String(""),x=Object.hasOwnProperty;return function(h,
j){h=h.match(s);var e,c=h[0],l=false;if("{"===c)e={};else if("["===c)e=[];else{e=[];l=true}for(var b,d=[e],m=1-l,y=h.length;m<y;++m){c=h[m];var a;switch(c.charCodeAt(0)){default:a=d[0];a[b||a.length]=+c;b=void 0;break;case 34:c=c.substring(1,c.length-1);if(c.indexOf("\\")!==-1)c=c.replace(t,v);a=d[0];if(!b)if(a instanceof Array)b=a.length;else{b=c||w;break}a[b]=c;b=void 0;break;case 91:a=d[0];d.unshift(a[b||a.length]=[]);b=void 0;break;case 93:d.shift();break;case 102:a=d[0];a[b||a.length]=false;
b=void 0;break;case 110:a=d[0];a[b||a.length]=null;b=void 0;break;case 116:a=d[0];a[b||a.length]=true;b=void 0;break;case 123:a=d[0];d.unshift(a[b||a.length]={});b=void 0;break;case 125:d.shift();break}}if(l){if(d.length!==1)throw new Error;e=e[0]}else if(d.length)throw new Error;if(j){var p=function(n,o){var f=n[o];if(f&&typeof f==="object"){var i=null;for(var g in f)if(x.call(f,g)&&f!==n){var q=p(f,g);if(q!==void 0)f[g]=q;else{i||(i=[]);i.push(g)}}if(i)for(g=i.length;--g>=0;)delete f[i[g]]}return j.call(n,
o,f)};e=p({"":e},"")}return e}}();
/*---------- json parse end ----------*/

var objBase = new ClsBase(); objBase.GetBrowser(); //基础类实例

/*----- document.ready begin -----*/

(function () {
    var isReady = false; //判断onDOMReady方法是否已经被执行过  
    var readyList = [];  //把需要执行的方法先暂存在这个数组里  
    var timer;           //定时器句柄  
    ready = function (fn) {
        if (isReady) fn.call(document);
        else readyList.push(function () { return fn.call(this); });
        return this;
    }
    var onDOMReady = function () {
        for (var i = 0; i < readyList.length; i++)
        readyList[i].apply(document);
        readyList = null;
    }
    var bindReady = function (evt) {
        if (isReady) return;
        isReady = true;
        onDOMReady.call(window);
        if (document.removeEventListener)
            document.removeEventListener("DOMContentLoaded", bindReady, false);
        else if (document.attachEvent) {
            document.detachEvent("onreadystatechange", bindReady);
            if (window == window.top) {
                clearInterval(timer);
                timer = null;
            }
        }
    };

    if (document.addEventListener)
        document.addEventListener("DOMContentLoaded", bindReady, false);
    else if (document.attachEvent) {
        document.attachEvent("onreadystatechange", function () {
            if ((/loaded|complete/).test(document.readyState))
                bindReady();
        });
        if (window == window.top) {
            timer = setInterval(function () {
                try {
                    isReady || document.documentElement.doScroll('left'); //在IE下用能否执行doScroll判断dom是否加载完毕  
                }
                catch (e) { return; }
                bindReady();
            }, 5);
        }
    }
})();

/*----- document.ready end -----*/

/*----- window resize begin -----*/

var onWindowResize = function() { 
	var queue = [], //事件队列 
	indexOf = Array.prototype.indexOf || function() {
		var i = 0, length = this.length; 
		for( ; i < length; i++ ) { 
			if (this[i] === arguments[0]) return i;
		} 
		return -1;
	};

	var isResizing = {}, //标记可视区域尺寸状态， 用于消除 lte ie8 / chrome 中 window.onresize 事件多次执行的 bug 
	lazy = true, //懒执行标记 
	listener = function(e) { //事件监听器 
		var h = window.innerHeight || (document.documentElement && document.documentElement.clientHeight) || document.body.clientHeight;
		var w = window.innerWidth || (document.documentElement && document.documentElement.clientWidth) || document.body.clientWidth;

		if ( h === isResizing.h && w === isResizing.w) return;
		else { 
			e = e || window.event;
			var i = 0, len = queue.length;
			for( ; i < len; i++) { queue[i].call(this, e); }
			isResizing.h = h,
			isResizing.w = w;
		}
	}

	return { 
		add: function(fn) {
			if (typeof fn === 'function') {
				if (lazy) { //懒执行 
					if (window.addEventListener) window.addEventListener('resize', listener, false);
					else window.attachEvent('onresize', listener);
					lazy = false; 
				} 
				queue.push(fn);
			}
			else { } 
			return this; 
		},
		remove: function(fn) {
			if (typeof fn === 'undefined') queue = [];
			else if (typeof fn === 'function') {
				var i = indexOf.call(queue, fn);
				if (i > -1) queue.splice(i, 1);
			} 
			return this;
		}
	};

}.call(this);

/*----- window resize end -----*/

/*----- base begin -----*/
function ClsBase() {
	var _t = this;
	_t.parentDoc = getParentDocument();
		
	_t.TAG_SUC = "suc";
	_t.TAG_ERR = "err";
	_t.GetBrowser = function() {
		var brw = navigator.userAgent.toLowerCase();
		var ret = (brw.match(/msie ([\d.]+)/))             ? IE = true :
				  (brw.match(/firefox\/([\d.]+)/))         ? FIREFOX = true :
				  (brw.match(/chrome\/([\d.]+)/))          ? CHROME	= true :
				  (brw.match(/opera.([\d.]+)/))            ? OPERA	= true :
				  (brw.match(/version\/([\d.]+).*safari/)) ? SAFARI = true : null;
        
		if (IE) {
		    if      (navigator.appVersion.indexOf("MSIE 10")  != -1) isIE10 = true;
		    else if (navigator.appVersion.indexOf("MSIE 9.0") != -1) isIE9  = true;
		    else if (navigator.appVersion.indexOf("MSIE 8.0") != -1) isIE8  = true;
		    else if (navigator.appVersion.indexOf("MSIE 7.0") != -1) isIE7  = true;
		    else if (navigator.appVersion.indexOf("MSIE 6.0") != -1) isIE6  = true;
		    else ret = null;
		}
		if (!ret) {
		    if (brw.indexOf("trident") != -1) {
		        ret = true;
		        IE  = false;
		        isIE11 = navigator.appVersion.indexOf("rv:11.0") != -1;
		    }
		}
		if (isIE6) ret = null;
		return ret;
	};

    _t.CheckUserSession = function(suc_callback_, fai_callback_) {
        objBase.ReadData("/ajax/get_user_session.asp", [["a", "1"]], null, false, function(ret) {
            if (ret[0]) { if (suc_callback_) suc_callback_(); }
            else { if (fai_callback_) fai_callback_(); }
        });    
    };
	
	//清除与当前元素无关的层
	_t.ClearLayers = function(elem_evt) {	
		if (!elem_evt) {
		    for (var tag in objProc.Layers) {
		        if (tag != "lyrPageLoad")
		            _t.Hide(objProc.Layers[tag]);
		    }		
		    return;
		}
		var elem;
		for (var tag in objProc.Layers) {
		    if (tag != "lyrPageLoad") {
			    elem = objProc.Layers[tag];
			    if (!(_t.IsObjOfParent(elem_evt, elem) || elem_evt == elem)) _t.Hide(elem);
			}
		}
	};

	//按需下载图片
    _t.LoadImages = function(elem_imgs) {
        for (var i = 0; i < elem_imgs.length; i++) {
            if (elem_imgs[i].getAttribute("real-url")) {  
                if (_t.DocScrollTop() + document.documentElement.clientHeight > (_t.GetElemPosition(elem_imgs[i]).y + 10))
                changeRealImageUrl(elem_imgs[i])
            }
        }
    };

    var changeRealImageUrl = function(elem_) {
        window.setTimeout(function() {
            if (elem_.getAttribute("real-url")) {
                elem_.setAttribute("src", elem_.getAttribute("real-url"));
                elem_.removeAttribute("real-url");  
            }
        }, 100);      
    };

	//判断当前元素是否为另一元素的子元素
	_t.IsObjOfParent = function(obj, parentObj) {
		if (!obj || !parentObj) return false;
		try{
			while (obj != undefined && obj != null && obj.tagName.toUpperCase() != "HTML") {
				if (obj == parentObj) return true;
				obj = obj.parentNode;
			}
		}
		catch(e) { return false; }
		return false; 
	};

	_t.GetPopWinTop = function() { return IE ? window.screenTop + 25 : CHROME ? 115 : FIREFOX ? 165 : 0; };
    _t.CreateElement = function(tag_name, elem_id, parent_node, class_name) { var elem_new = (parent_node == _t.parentDoc.body ? parent.document : document).createElement(tag_name); if (elem_id) { elem_new.setAttribute("id", elem_id); } (parent_node || document.body).appendChild(elem_new); if (class_name) { elem_new.className = class_name; } return elem_new; };
	_t.IsLocalAccess = function() { var hn = document.location.hostname; if (hn == SERVER.DOMAIN_NAME) { return false; } for (var i = 0; i < SERVER.IP.length; i++) { if (hn == SERVER.IP[i]) return false; } return true; };		
	_t.DocAttachComplete = function() { document.body.style.visibility = "visible"; document.body.style.background = "none"; document.body.focus(); };
	_t.IsArray = function(obj) { return Object.prototype.toString.call(obj) === '[object Array]'; };
	_t.GetScrollTop = function(isParent) { var doc = isParent ? parent.document : document; return doc.documentElement.scrollTop; };
	_t.GetScrollHeight = function(isParent) { var doc = isParent ? parent.document : document; return doc.documentElement.scrollHeight; };
	_t.GetClientWidth = function(isParent) { var doc = isParent ? parent.document : document; return doc.documentElement.clientWidth; };
	_t.GetClientHeight = function(isParent) { var doc = isParent ? parent.document : document; return doc.documentElement.clientHeight; };
	_t.ConvertNull = function(paramValue, convertValue){ if (paramValue == null) return convertValue; return paramValue; };
	_t.GetProdUrl = function(prodId) { return ("http://" + (_t.IsLocalAccess() ? document.location.hostname : SERVER.DOMAIN_NAME)) + "/goods/" + prodId + ".html"; };
	_t.Display = function(ctr) { if (!ctr) return; if (ctr.tagName == "FORM") {ctr.style.display = "block"; return; } if (ctr.length) { for (var i = 0; i < ctr.length; i++){ ctr[i].style.display = "block"; } } else { ctr.style.display = "block"; } };
	_t.DisplayNone = function(ctr) { if (!ctr) return; if (ctr.tagName == "FORM") {ctr.style.display = "none"; return; } if (ctr.length) { for (var i = 0; i < ctr.length; i++){ ctr[i].style.display = "none"; } } else { ctr.style.display = "none"; } };
	_t.Visible = function(ctr) { if (ctr) ctr.style.visibility = "visible"; };
	_t.Hide = function(ctr) { if (!ctr) return; ctr.style.visibility = "hidden"; };
	_t.DisableObject = function (obj, dis) { if (!obj) return; if (obj.length) { for (var i = 0; i < obj.length; i++) { obj[i].disabled = dis; } } else obj.disabled = dis; };
	_t.GetElems = function(elemName, obj) { return (obj || document).getElementsByTagName(elemName); };
	_t.GetElemsByClassName = function(elemName, obj, className) { var arr = []; var elems = _t.GetElems(elemName, obj), elem; for (var i = 0; i < elems.length; i++) { elem = elems[i]; if (elem.className == className) arr[arr.length] = elem;} return arr; };
	_t.GetElemsByClassName2 = function(obj, className) { var arr = []; var elems = obj.childNodes, elem; for (var i = 0; i < elems.length; i++) { elem = elems[i]; if (elem.className == className) arr[arr.length] = elem;} return arr; };
	_t.GetElemsByAttribute = function(elemName, obj, attrName, attrValue) { var arr = []; var elems = _t.GetElems(elemName, obj), elem; for (var i = 0; i < elems.length; i++) { elem = elems[i]; if (elem.getAttribute(attrName) == attrValue) arr[arr.length] = elem; } return arr; };
	_t.GetElemIndex = function (curObj, objs) {for (var i = 0; i < objs.length; i++) { if (objs[i] == curObj) return i; } };
	_t.FixPosition =  function(obj, top_num, left_num, visible, width_num, height_num) { if (!obj) return; obj.style.top = top_num + "px"; obj.style.left = left_num + "px"; if (width_num) obj.style.width = width_num + "px"; if (height_num) obj.style.height = height_num + "px"; obj.style.visibility = visible ? visible : "visible"; };
	_t.GetEventObj = function(e, cancelBubble) { var evt = e || window.event; if (cancelBubble) { if (evt.stopPropagation) evt.stopPropagation(); else evt.cancelBubble = true; } return evt.srcElement || evt.target; };
	_t.GetEventFromObj = function(e) { var evt = e || window.event; return evt.fromElement; };
	_t.GetEventToObj = function(e) { var evt = e || window.event; return evt.toElement; };
	_t.GetEventTargetObj = function(e) { var evt = e || window.event; return evt.relatedTarget; };
	_t.GetFrameEventObj = function(elem_frame, e, cancelBubble) { var evt = e || elem_frame.contentWindow.event; if (cancelBubble) { if (evt.stopPropagation) evt.stopPropagation(); else evt.cancelBubble = true; } return evt.srcElement || evt.target; };
	_t.CancelBubble = function(e) { var evt = e || window.event; if (evt.stopPropagation) evt.stopPropagation(); else evt.cancelBubble = true; };
	_t.GetEventButton = function(e, cancelBubble) { var evt = e || window.event; if (cancelBubble) _t.CancelBubble(e); return evt.button; };
	_t.GetEventKeyCode = function(e, cancelBubble) { var evt = e || window.event; if (cancelBubble) _t.CancelBubble(e); return evt.keyCode; };
	_t.DisableButtons = function (obj, dis) { var btns = _t.GetElems("button", obj); for (var i = 0; i < btns.length; i++) { btns[i].disabled = dis; } };	
	_t.GetMousePosition = function(e) { var evt = e || window.event; return{x: evt.clientX, y: evt.clientY} };
	_t.DocScrollTop = function() { return (document.documentElement && document.documentElement.scrollTop) ? document.documentElement.scrollTop : document.body.scrollTop; };
	_t.DocScrollLeft = function() { return (document.documentElement && document.documentElement.scrollLeft) ? document.documentElement.scrollLeft : document.body.scrollLeft; };
	_t.FrameDocScrollTop = function(elem_frame) { var frame_doc = elem_frame.contentWindow.document; return (frame_doc.documentElement && frame_doc.documentElement.scrollTop) ? frame_doc.documentElement.scrollTop : frame_doc.body.scrollTop; };
	_t.AddClass = function(elem, class_name) { elem.className = elem.className.replace(new RegExp(" " + class_name + "\\b"), "") + " " + class_name; };
	_t.RemoveClass = function(elem, class_name) { elem.className = elem.className.replace(new RegExp(class_name + "\\b"), ""); };
	_t.GetframeDoc = function(frameId) { return document.getElementById(frameId).contentWindow.document; }
	_t.IsVisible = function(elem) { if (!elem) return false; return elem.style.visibility == "visible"; };
	_t.IsDisplay = function(elem) { if (!elem) return false; return elem.style.display == "block" || elem.style.display.indexOf("inline") != -1; };
	_t.ImageLoaded = function(elem) { if (elem.readyState) { return elem.readyState == "complete"; } else return elem.complete == true; };
	_t.GetImagePath = function(path_str) { return (_t.IsLocalAccess()) ? "/photo/" + path_str + "/" : "http://" + SERVER.CDN_DOMAIN_NAME + "/" + path_str + "/"; };
	_t.GetProdImagePath = function(path_tag, image_tag, file_name) { var image_path = (path_tag == 0) ? "/photo/product/" + image_tag + "photo" : "http://" + SERVER.CDN_DOMAIN_NAME + "/product/" + image_tag + "photo"; return image_path + "/" + file_name };
	_t.AddHidden = function (elem_parent, elem_name, elem_value) { var elem_hidden = document.createElement("input"); elem_hidden.setAttribute("type", "hidden"); elem_hidden.setAttribute("name", elem_name); if (elem_value) elem_hidden.setAttribute("value", elem_value); elem_hidden.setAttribute("id", elem_name); elem_parent.appendChild(elem_hidden); return elem_hidden; };
	_t.CtrlConvertTH = function(ctrl) { var ctrl_value = ctrl.value; var str = ctrl_value.Trim().ConvertHalfAngle(); if (ctrl_value != str) ctrl.value = str; };
    _t.Countdown = function(cdParams, from_cdn) { _t.LoadJS("/js/countdown-for-jquery.js?v=140609", function() { _t.Countdown(cdParams, from_cdn) }, null); };
    _t.BindEvent = function(elem_, event_name_, event_, use_capture_) { var event_name2; if (event_name_ == "focus") event_name2 = "onfocusin"; else if (event_name_ == "blur") event_name2 = "onfocusout"; else event_name2 = "on" + event_name_; if (elem_.addEventListener) elem_.addEventListener(event_name_, event_, use_capture_ ? true : false); else elem_.attachEvent(event_name2, event_); };
    _t.GetParentElem = function(elem_, node_name_, max_num_) { if (elem_.nodeName == node_name_) { return elem_; } var i = 0, elem_parent = elem_.parentNode; while (i < max_num_) { if (!elem_parent) { return; } if (elem_parent.nodeName == node_name_) { return elem_parent; } elem_parent = elem_parent.parentNode; i++; } };
    _t.GetNextElem = function(elem_, node_name_, max_num_) { if (elem_.nodeName == node_name_) { return elem_; } var i = 0, elem_next = elem_.nextSibling; while (i < max_num_) { if (!elem_next) { return; } if (elem_next.nodeName == node_name_) { return elem_next; } elem_next = elem_next.nextSibling; i++; } };

	//显示遮罩层
	_t.DisplayMask = function(zIndex) {
		var elem_mask = _t.parentDoc.getElementById("lyrMask");
		if (!elem_mask) elem_mask = _t.CreateElement("div", "lyrMask", _t.parentDoc.body);		
		if (isIE6) {
			elem_mask.style.width = _t.parentDoc.body.clientWidth + "px";
			elem_mask.style.height = ((_t.parentDoc.documentElement.clientHeight == 0) ? _t.parentDoc.body.clientHeight : _t.parentDoc.documentElement.clientHeight) + "px";
		}
		if (zIndex) elem_mask.style.zIndex = zIndex;
		_t.Visible(elem_mask);		
	};
	
	_t.HideMask = function() { var elem_mask = _t.parentDoc.getElementById("lyrMask"); if (!elem_mask) return; _t.Hide(elem_mask); elem_mask.style.zIndex = 10;	}; //隐藏遮罩层
	_t.DialogError = function(msg, closeEvent) { var evt = function() { _t.DialogError(msg, closeEvent); }; loadDialogJSFile(evt); }; //错误对话框(提问内容, 关闭事件)	
	_t.DialogInfo = function(msg, closeEvent, isReport) { var evt = function() { _t.DialogInfo(msg, closeEvent, isReport); }; loadDialogJSFile(evt); }; //信息对话框(提问内容, 关闭事件, 是否以报表形式显示)	
	_t.DialogQuestion = function(msg, btnConfirmText, confirmEvent, closeEvent, isReport, maskIndex) { var evt = function() { _t.DialogQuestion(msg, btnConfirmText, confirmEvent, closeEvent, isReport, maskIndex); }; loadDialogJSFile(evt); }; //提问对话框(提问内容, 继续按钮文本, 继续事件, 关闭事件, 是否以报表形式显示)	
	_t.DialogComplete = function(msg, closeEvent, delay) { var evt = function() { _t.DialogComplete(msg, closeEvent, delay); }; loadDialogJSFile(evt); }; //成功对话框(提问内容, 是否父窗口执行, 关闭事件)	
	
	//等待操作对话框(等待提示信息, 是否父窗口执行)
	_t.DialogWait = function(msg) {
	    var parentDoc = _t.parentDoc;
		var eleDialog = parentDoc.getElementById("waitBox");
		if (!eleDialog) {
			eleDialog = _t.CreateElement("div", "waitBox", parentDoc.body);
			eleDialog.innerHTML = "<input type=\"hidden\" value=\"\" /><div class=\"dlg-cnt\"></div>";
		}		
		_t.DisplayMask();
		eleDialog.childNodes[1].innerHTML = msg;
		_t.Visible(eleDialog);
	};
	
	//关闭等待对话框
	_t.CloseWaitDialog = function(hideMask) {
	    var parentDoc = _t.parentDoc;
		if (parentDoc.getElementById("waitBox")) _t.Hide(parentDoc.getElementById("waitBox"));
		if (hideMask) _t.HideMask();
	};	
	
	//动态加载js
	_t.LoadJS = function(js_path, callback, need_mask) {
		if (need_mask) _t.DisplayMask();
		var node_head = document.getElementsByTagName("head")[0];
		var node_script = document.createElement("script");
		node_script.setAttribute("type", "text/javascript");
		node_script.setAttribute("src", js_path);
		node_head.appendChild(node_script);		
		if (IE) {
		    node_script.onreadystatechange = function() {
			    if (!node_script.readyState || node_script.readyState == "loaded" || node_script.readyState == "complete") {
			        if (callback) callback();
                    clearScript(node_script);
			    }
		    };
		}
		else {
		    node_script.onload = function() {
			    if (!node_script.readyState || node_script.readyState == "loaded" || node_script.readyState == "complete") {
			        if (callback) callback();
			        clearScript(node_script);
			    }
		    };
		}
	};
	
	var clearScript = function(script) {
	    script.parentNode.removeChild(script);	    
        script = null;
	};	
	
	_t.ReadData = function(url, arr_param, event_ctrl, need_mask, callback, fail_callback, loadbar) {
	    if (event_ctrl) _t.DisableObject(event_ctrl, true);
	    if (need_mask) {
	        _t.DisplayMask();
	        _t.DialogWait("正在努力响应您的操作...");
	    }
	    var timestamp = new Date().getTime() + "-" + Math.ceil(Math.random() * 89999999 + 10000000);
	    var param_name, param_value, param_str;
	    if (arr_param) {
	        for (var i = 0; i < arr_param.length; i++) {
	            param_name  = arr_param[i][0];
	            param_value = arr_param[i][1];
	            param_str   = "&" + param_name + "=" + encodeURIComponent(param_value);
	            url += (i == 0) ? "?t=" + timestamp + param_str : param_str;
	        }
	    }
	    else url += "?t=" + timestamp;
        //document.location.href = url
        var obj_xhr = createXHR("get", url);
        obj_xhr.onreadystatechange = function() {
            if (obj_xhr.readyState == 4) {
                var ret = obj_xhr.responseText;
                if (event_ctrl) _t.DisableObject(event_ctrl, false);
                if (obj_xhr.status == 200) {
                    delete obj_xhr;
                    if (ret.length == 0) {
                        _t.DialogError("Return Is Empty.");
                        if (loadbar) _t.Hide(loadbar);
                        return;
                    }
                    var json = jsonParse(ret)
                    if (need_mask) {
                        window.setTimeout(function(){
                            callback(json);
                        }, 300);                        
                    }
                    else callback(json);
                    if (loadbar) _t.Hide(loadbar);
                    return;
                }
                displaySysError(ret);
                if (loadbar) _t.Hide(loadbar);
            }
        }        
	};
	//建立XMLHTTP对象
	var createXHR = function(method, url) {
	    var obj;
        if (window.XMLHttpRequest) {
	        obj = new XMLHttpRequest();
	        obj.open (method, url, true);
	        obj.send(null);
        } 
        else if(window.ActiveXObject) {
	        obj = new ActiveXObject("Microsoft.XMLHTTP");
	        obj.open (method, url, true);
	        obj.send();
        }
        return obj;
	};
	function loadDialogJSFile(evt) { _t.DisplayMask(); _t.LoadJS("/js/lib-dialogs.js?v=140822", evt, true); } //下载对话框部分程序文件
	function getParentDocument() { var doc = parent; while(doc != doc.parent) { doc = doc.parent; } return doc.document; } //获得最父级窗口
	_t.RecoverCtrlStyle = function(elem) {
		elem.className = elem.className.replace(/ txtHover/g, "").replace(/ txtFocus/g, "");
		elem.value = elem.value.Trim();
	};
    _t.GetElemPosition = function(obj) { if (!obj) return; for(var lx = 0, ly = 0; obj != null; lx += obj.offsetLeft, ly += obj.offsetTop, obj = obj.offsetParent); return { x:lx, y:ly }; };
}
/*----- base end -----*/

/*----- data begin -----*/
function ClsData() {
	var _t = this;	
	_t.RunPost = function(formid_, url_, callback, fail_callback) {
        $.ajax({
            type: "POST",
            cache: false,
            url: url_,
            data: $("#" + formid_).serialize(),
            success: function(data) { var json = jsonParse(data); if (callback) callback(json); },
            error: function(data) {
                alert(data.responseText)
            }
        });
	};
}
/*----- data end -----*/
String.prototype.ClearHtml = function(){return this.replace(/<[^>]*>/g, "");};
String.prototype.Trim = function() { var str = String(this); return str.replace(/(^\s*)|(\s*$)/g, "").replace(/(^　*)|(　*$)/g, ""); };
var displaySysError = function(msg){
	if (msg.length == 0) return;
	alert("\r\n系统错误，请与您的系统管理员联系！\r\n\r\n" + msg.ClearHtml());
};