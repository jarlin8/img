/*Superfish*/
(function($){var methods=function(){var c={bcClass:"sf-breadcrumb",menuClass:"sf-js-enabled",anchorClass:"sf-with-ul",menuArrowClass:"sf-arrows"},ios=function(){var ios=/iPhone|iPad|iPod/i.test(navigator.userAgent);if (ios) $(window).on('load',function(){$("body").children().on("click",$.noop)});return ios}(),wp7=function(){var style=document.documentElement.style;return"behavior"in style&&("fill"in style&&/iemobile/i.test(navigator.userAgent))}(),toggleMenuClasses=function($menu,o){var classes=c.menuClass;
if(o.cssArrows)classes+=" "+c.menuArrowClass;$menu.toggleClass(classes)},setPathToCurrent=function($menu,o){return $menu.find("li."+o.pathClass).slice(0,o.pathLevels).addClass(o.hoverClass+" "+c.bcClass).filter(function(){return $(this).children(o.popUpSelector).hide().show().length}).removeClass(o.pathClass)},toggleAnchorClass=function($li){$li.children("a").toggleClass(c.anchorClass)},toggleTouchAction=function($menu){var touchAction=$menu.css("ms-touch-action");touchAction=touchAction==="pan-y"?
"auto":"pan-y";$menu.css("ms-touch-action",touchAction)},applyHandlers=function($menu,o){var targets="li:has("+o.popUpSelector+")";if($.fn.hoverIntent&&!o.disableHI)$menu.hoverIntent(over,out,targets);else $menu.on("mouseenter.superfish",targets,over).on("mouseleave.superfish",targets,out);var touchevent="MSPointerDown.superfish";if(!ios)touchevent+=" touchend.superfish";if(wp7)touchevent+=" mousedown.superfish";$menu.on("focusin.superfish","li",over).on("focusout.superfish","li",out).on(touchevent,
"a",o,touchHandler)},touchHandler=function(e){var $this=$(this),$ul=$this.siblings(e.data.popUpSelector);if($ul.length>0&&$ul.is(":hidden")){$this.one("click.superfish",false);if(e.type==="MSPointerDown")$this.trigger("focus");else $.proxy(over,$this.parent("li"))()}},over=function(){var $this=$(this),o=getOptions($this);clearTimeout(o.sfTimer);$this.siblings().superfish("hide").end().superfish("show")},out=function(){var $this=$(this),o=getOptions($this);if(ios)$.proxy(close,$this,o)();else{clearTimeout(o.sfTimer);
o.sfTimer=setTimeout($.proxy(close,$this,o),o.delay)}},close=function(o){o.retainPath=$.inArray(this[0],o.$path)>-1;this.superfish("hide");if(!this.parents("."+o.hoverClass).length){o.onIdle.call(getMenu(this));if(o.$path.length)$.proxy(over,o.$path)()}},getMenu=function($el){return $el.closest("."+c.menuClass)},getOptions=function($el){return getMenu($el).data("sf-options")};return{hide:function(instant){if(this.length){var $this=this,o=getOptions($this);if(!o)return this;var not=o.retainPath===
true?o.$path:"",$ul=$this.find("li."+o.hoverClass).add(this).not(not).removeClass(o.hoverClass).children(o.popUpSelector),speed=o.speedOut;if(instant){$ul.show();speed=0}o.retainPath=false;o.onBeforeHide.call($ul);$ul.stop(true,true).animate(o.animationOut,speed,function(){var $this=$(this);o.onHide.call($this)})}return this},show:function(){var o=getOptions(this);if(!o)return this;var $this=this.addClass(o.hoverClass),$ul=$this.children(o.popUpSelector);o.onBeforeShow.call($ul);$ul.stop(true,true).animate(o.animation,
o.speed,function(){o.onShow.call($ul)});return this},destroy:function(){return this.each(function(){var $this=$(this),o=$this.data("sf-options"),$hasPopUp;if(!o)return false;$hasPopUp=$this.find(o.popUpSelector).parent("li");clearTimeout(o.sfTimer);toggleMenuClasses($this,o);toggleAnchorClass($hasPopUp);toggleTouchAction($this);$this.off(".superfish").off(".hoverIntent");$hasPopUp.children(o.popUpSelector).attr("style",function(i,style){return style.replace(/display[^;]+;?/g,"")});o.$path.removeClass(o.hoverClass+
" "+c.bcClass).addClass(o.pathClass);$this.find("."+o.hoverClass).removeClass(o.hoverClass);o.onDestroy.call($this);$this.removeData("sf-options")})},init:function(op){return this.each(function(){var $this=$(this);if($this.data("sf-options"))return false;var o=$.extend({},$.fn.superfish.defaults,op),$hasPopUp=$this.find(o.popUpSelector).parent("li");o.$path=setPathToCurrent($this,o);$this.data("sf-options",o);toggleMenuClasses($this,o);toggleAnchorClass($hasPopUp);toggleTouchAction($this);applyHandlers($this,
o);$hasPopUp.not("."+c.bcClass).superfish("hide",true);o.onInit.call(this)})}}}();$.fn.superfish=function(method,args){if(methods[method])return methods[method].apply(this,Array.prototype.slice.call(arguments,1));else if(typeof method==="object"||!method)return methods.init.apply(this,arguments);else return $.error("Method "+method+" does not exist on jQuery.fn.superfish")};$.fn.superfish.defaults={popUpSelector:"ul,.sf-mega",hoverClass:"sfHover",pathClass:"overrideThisToUse",pathLevels:1,delay:800,
animation:{opacity:"show"},animationOut:{opacity:"hide"},speed:"normal",speedOut:"fast",cssArrows:true,disableHI:false,onInit:$.noop,onBeforeShow:$.noop,onShow:$.noop,onBeforeHide:$.noop,onHide:$.noop,onIdle:$.noop,onDestroy:$.noop};$.fn.extend({hideSuperfishUl:methods.hide,showSuperfishUl:methods.show})})(jQuery);

/* Sidr */
(function(e){var t=false,n=false;var r={isUrl:function(e){var t=new RegExp("^(https?:\\/\\/)?"+"((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|"+"((\\d{1,3}\\.){3}\\d{1,3}))"+"(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*"+"(\\?[;&a-z\\d%_.~+=-]*)?"+"(\\#[-a-z\\d_]*)?$","i");if(!t.test(e)){return false}else{return true}},loadContent:function(e,t){e.html(t)},addPrefix:function(e){var t=e.attr("id"),n=e.attr("id");if(typeof t==="string"&&""!==t){e.attr("id",t.replace(/([A-Za-z0-9_.\-]+)/g,"sidr-id-$1"))}if(typeof n==="string"&&""!==n&&"sidr-inner"!==n){}e.removeAttr("style")},execute:function(r,s,o){if(typeof s==="function"){o=s;s="sidr"}else{if(!s){s="sidr"}}var u=e("#"+s),a=e(u.data("body")),f=e("html"),l=u.outerWidth(true),c=u.data("speed"),h=u.data("side"),p=u.data("displace"),d=u.data("onOpen"),v=u.data("onClose"),m,g,y,b=s==="sidr"?"sidr-open":"sidr-open "+s+"-open";if("open"===r||"toggle"===r&&!u.is(":visible")){if(u.is(":visible")||t){return}if(n!==false){i.close(n,function(){i.open(s)});return}t=true;if(h==="left"){m={left:l+"px"};g={left:"0px"}}else{m={right:l+"px"};g={right:"0px"}}if(a.is("body")){y=f.scrollTop();f.css("overflow-x","hidden").scrollTop(y);a.addClass("sidr-show")}if(p){a.addClass("sidr-animating").css({width:a.width(),position:"absolute"}).animate(m,c,function(){e(this).addClass(b)})}else{setTimeout(function(){e(this).addClass(b)},c)}u.css("display","block").animate(g,c,function(){t=false;n=s;if(typeof o==="function"){o(s)}a.removeClass("sidr-animating")});d()}else{if(!u.is(":visible")||t){return}t=true;if(h==="left"){m={left:0};g={left:"-"+l+"px"}}else{m={right:0};g={right:"-"+l+"px"}}if(a.is("body")){y=f.scrollTop();f.removeAttr("style").scrollTop(y)}a.addClass("sidr-animating").animate(m,c).removeClass(b);u.animate(g,c,function(){u.removeAttr("style").hide();a.removeAttr("style");e("html").removeAttr("style");t=false;n=false;if(typeof o==="function"){o(s)}a.removeClass("sidr-animating");a.removeClass("sidr-show")});v()}}};var i={open:function(e,t){r.execute("open",e,t)},close:function(e,t){r.execute("close",e,t)},toggle:function(e,t){r.execute("toggle",e,t)},toogle:function(e,t){r.execute("toggle",e,t)}};e.sidr=function(t){if(i[t]){return i[t].apply(this,Array.prototype.slice.call(arguments,1))}else{if(typeof t==="function"||typeof t==="string"||!t){return i.toggle.apply(this,arguments)}else{e.error("Method "+t+" does not exist on jQuery.sidr")}}};e.fn.sidr=function(t){var n=e.extend({name:"sidr",speed:200,side:"left",source:null,renaming:true,body:"body",displace:true,onOpen:function(){},onClose:function(){}},t);var s=n.name,o=e("#"+s);if(o.length===0){o=e("<div />").attr("id",s).appendTo(e("body"))}o.addClass("sidr").addClass(n.side).data({speed:n.speed,side:n.side,body:n.body,displace:n.displace,onOpen:n.onOpen,onClose:n.onClose});if(typeof n.source==="function"){var u=n.source(s);r.loadContent(o,u)}else{if(typeof n.source==="string"&&r.isUrl(n.source)){e.get(n.source,function(e){r.loadContent(o,e)})}else{if(typeof n.source==="string"){var a="",f=n.source.split(",");e.each(f,function(t,n){a+='<div class="sidr-inner">'+e(n).html()+"</div>"});if(n.renaming){var l=e("<div />").html(a);l.find("*").each(function(t,n){var i=e(n);r.addPrefix(i)});a=l.html()}r.loadContent(o,a)}else{if(n.source!==null){e.error("Invalid Sidr Source")}}}}return this.each(function(){var t=e(this),n=t.data("sidr");if(!n){t.data("sidr",s);if("ontouchstart" in document.documentElement){t.bind("touchstart",function(e){var t=e.originalEvent.touches[0];this.touched=e.timeStamp});t.bind("touchend",function(e){var t=Math.abs(e.timeStamp-this.touched);if(t<200){e.preventDefault();i.toggle(s)}})}else{t.click(function(e){e.preventDefault();i.toggle(s)})}}})}})(jQuery);

jQuery(document).ready(function($) {
	// Main menu superfish
	$('ul.nav-menu, .top-menu').superfish({
		delay: 200,
		hoverClass: 'behover',
		animation: {
			opacity: 'show',
			height: 'show'
		},
		speed: 'normal'
	});

	// Mobile Menu
	$('#navigation-toggle').sidr({
		name: 'sidr-main',
		source: '#sidr-close, #site-nav',
		side: 'left',
		displace: false,
		onOpen: function onOpen() {
			$('.menu-but').toggleClass("menu-open");
		},

		onClose: function onClose() {
			$('.menu-but').toggleClass("menu-open");
		}
	});

	$("#sidr-id-overlay, .toggle-sidr-close").click(function() {
		$.sidr('close', 'sidr-main');
		return false;
	});

	$(window).resize(function() {
		$.sidr('close', 'sidr-main');
	});

	$(".menu-mobile-but, .off-mobile-nav").click(function() {
		$(".menu-but").toggleClass("menu-open");
	});

});

// jquery.wookmark 1.4.5
(function(t){"function"==typeof define&&define.amd?define(["jquery"],t):t(jQuery)})(function(t){function i(t){n(function(){var i,e;for(i in t)e=t[i],e.obj.css(e.css)})}function e(i){return t.trim(i).toLowerCase()}var s,h,o;o=function(t,i){return function(){return t.apply(i,arguments)}},h={align:"center",autoResize:!1,comparator:null,container:t("body"),ignoreInactiveItems:!0,itemWidth:0,fillEmptySpace:!1,flexibleWidth:0,direction:void 0,offset:2,onLayoutChanged:void 0,outerOffset:0,resizeDelay:50,possibleFilters:[]};var n=window.requestAnimationFrame||function(t){t()};s=function(){function s(i,e){this.handler=i,this.columns=this.containerWidth=this.resizeTimer=null,this.activeItemCount=0,this.itemHeightsDirty=!0,this.placeholders=[],t.extend(!0,this,h,e),this.update=o(this.update,this),this.onResize=o(this.onResize,this),this.onRefresh=o(this.onRefresh,this),this.getItemWidth=o(this.getItemWidth,this),this.layout=o(this.layout,this),this.layoutFull=o(this.layoutFull,this),this.layoutColumns=o(this.layoutColumns,this),this.filter=o(this.filter,this),this.clear=o(this.clear,this),this.getActiveItems=o(this.getActiveItems,this),this.refreshPlaceholders=o(this.refreshPlaceholders,this),this.sortElements=o(this.sortElements,this),this.updateFilterClasses=o(this.updateFilterClasses,this),this.updateFilterClasses(),this.autoResize&&t(window).bind("resize.wookmark",this.onResize),this.container.bind("refreshWookmark",this.onRefresh)}return s.prototype.updateFilterClasses=function(){for(var t,i,s,h,o=0,n=0,r=0,a={},l=this.possibleFilters;this.handler.length>o;o++)if(i=this.handler.eq(o),t=i.data("filterClass"),"object"==typeof t&&t.length>0)for(n=0;t.length>n;n++)s=e(t[n]),a[s]||(a[s]=[]),a[s].push(i[0]);for(;l.length>r;r++)h=e(l[r]),h in a||(a[h]=[]);this.filterClasses=a},s.prototype.update=function(i){this.itemHeightsDirty=!0,t.extend(!0,this,i)},s.prototype.onResize=function(){clearTimeout(this.resizeTimer),this.itemHeightsDirty=0!==this.flexibleWidth,this.resizeTimer=setTimeout(this.layout,this.resizeDelay)},s.prototype.onRefresh=function(){this.itemHeightsDirty=!0,this.layout()},s.prototype.filter=function(i,s){var h,o,n,r,a,l=[],f=t();if(i=i||[],s=s||"or",i.length){for(o=0;i.length>o;o++)a=e(i[o]),a in this.filterClasses&&l.push(this.filterClasses[a]);if(h=l.length,"or"==s||1==h)for(o=0;h>o;o++)f=f.add(l[o]);else if("and"==s){var u,c,d,m=l[0],p=!0;for(o=1;h>o;o++)l[o].length<m.length&&(m=l[o]);for(m=m||[],o=0;m.length>o;o++){for(c=m[o],p=!0,n=0;l.length>n&&p;n++)if(d=l[n],m!=d){for(r=0,u=!1;d.length>r&&!u;r++)u=d[r]==c;p&=u}p&&f.push(m[o])}}this.handler.not(f).addClass("inactive")}else f=this.handler;f.removeClass("inactive"),this.columns=null,this.layout()},s.prototype.refreshPlaceholders=function(i,e){for(var s,h,o,n,r,a,l=this.placeholders.length,f=this.columns.length,u=this.container.innerHeight();f>l;l++)s=t('<div class="wookmark-placeholder"/>').appendTo(this.container),this.placeholders.push(s);for(a=this.offset+2*parseInt(this.placeholders[0].css("borderLeftWidth"),10),l=0;this.placeholders.length>l;l++)if(s=this.placeholders[l],o=this.columns[l],l>=f||!o[o.length-1])s.css("display","none");else{if(h=o[o.length-1],!h)continue;r=h.data("wookmark-top")+h.data("wookmark-height")+this.offset,n=u-r-a,s.css({position:"absolute",display:n>0?"block":"none",left:l*i+e,top:r,width:i-a,height:n})}},s.prototype.getActiveItems=function(){return this.ignoreInactiveItems?this.handler.not(".inactive"):this.handler},s.prototype.getItemWidth=function(){var t=this.itemWidth,i=this.container.width()-2*this.outerOffset,e=this.handler.eq(0),s=this.flexibleWidth;if(void 0===this.itemWidth||0===this.itemWidth&&!this.flexibleWidth?t=e.outerWidth():"string"==typeof this.itemWidth&&this.itemWidth.indexOf("%")>=0&&(t=parseFloat(this.itemWidth)/100*i),s){"string"==typeof s&&s.indexOf("%")>=0&&(s=parseFloat(s)/100*i);var h=~~(.5+(i+this.offset)/(s+this.offset)),o=Math.min(s,~~((i-(h-1)*this.offset)/h));t=Math.max(t,o),this.handler.css("width",t)}return t},s.prototype.layout=function(t){if(this.container.is(":visible")){var i,e=this.getItemWidth()+this.offset,s=this.container.width(),h=s-2*this.outerOffset,o=~~((h+this.offset)/e),n=0,r=0,a=0,l=this.getActiveItems(),f=l.length;if(this.itemHeightsDirty||!this.container.data("itemHeightsInitialized")){for(;f>a;a++)i=l.eq(a),i.data("wookmark-height",i.outerHeight());this.itemHeightsDirty=!1,this.container.data("itemHeightsInitialized",!0)}o=Math.max(1,Math.min(o,f)),n=this.outerOffset,"center"==this.align&&(n+=~~(.5+(h-(o*e-this.offset))>>1)),this.direction=this.direction||("right"==this.align?"right":"left"),r=t||null===this.columns||this.columns.length!=o||this.activeItemCount!=f?this.layoutFull(e,o,n):this.layoutColumns(e,n),this.activeItemCount=f,this.container.css("height",r),this.fillEmptySpace&&this.refreshPlaceholders(e,n),void 0!==this.onLayoutChanged&&"function"==typeof this.onLayoutChanged&&this.onLayoutChanged()}},s.prototype.sortElements=function(t){return"function"==typeof this.comparator?t.sort(this.comparator):t},s.prototype.layoutFull=function(e,s,h){var o,n,r=0,a=0,l=t.makeArray(this.getActiveItems()),f=l.length,u=null,c=null,d=[],m=[],p="left"==this.align?!0:!1;for(this.columns=[],l=this.sortElements(l);s>d.length;)d.push(this.outerOffset),this.columns.push([]);for(;f>r;r++){for(o=t(l[r]),u=d[0],c=0,a=0;s>a;a++)u>d[a]&&(u=d[a],c=a);o.data("wookmark-top",u),n=h,(c>0||!p)&&(n+=c*e),(m[r]={obj:o,css:{position:"absolute",top:u}}).css[this.direction]=n,d[c]+=o.data("wookmark-height")+this.offset,this.columns[c].push(o)}return i(m),Math.max.apply(Math,d)},s.prototype.layoutColumns=function(t,e){for(var s,h,o,n,r=[],a=[],l=0,f=0,u=0;this.columns.length>l;l++){for(r.push(this.outerOffset),h=this.columns[l],n=l*t+e,s=r[l],f=0;h.length>f;f++,u++)o=h[f].data("wookmark-top",s),(a[u]={obj:o,css:{top:s}}).css[this.direction]=n,s+=o.data("wookmark-height")+this.offset;r[l]=s}return i(a),Math.max.apply(Math,r)},s.prototype.clear=function(){clearTimeout(this.resizeTimer),t(window).unbind("resize.wookmark",this.onResize),this.container.unbind("refreshWookmark",this.onRefresh),this.handler.wookmarkInstance=null},s}(),t.fn.wookmark=function(t){return this.wookmarkInstance?this.wookmarkInstance.update(t||{}):this.wookmarkInstance=new s(this,t||{}),this.wookmarkInstance.layout(!0),this.show()}});

jQuery(document).ready(function($){
	var fall_width = fallwidth.fall_width;
	$('.fall').wookmark({
		itemWidth: 90,
		autoResize: true,
		container: $('.post-fall'),
		offset: 10,
		outerOffset: 0,
		flexibleWidth: fall_width
	});

	$('.fall').removeClass('fall-off');
	$('.fall').addClass('fall-on');
})

// toc
jQuery(document).ready(function($){
	var flag = true
	$(window).scroll(function(){
		if(flag){
			var winH = $(window).innerHeight()/200;
			var scrollT = $(window).scrollTop()
			var len = $(".toch").length
			var lon = $(".toc-box-h").length
			for(var i=0;i<len;i++){
				var bannerGap = $(".toch")[i].offsetTop - scrollT
				if(bannerGap < winH){
					$(".toc-level").eq(i).addClass("active").siblings().removeClass("active")
				}
			}
			for(var i=0;i<lon;i++){
				var bannerGap = $(".toc-box-h")[i].offsetTop - scrollT
				if(bannerGap < winH){
					$(".toc-ul-box li").eq(i).addClass("active").siblings().removeClass("active")
				}
			}
		}
	});

	if ( $(".toc-level-2").length ) {
	} else {
		$(".toc-level-3,.toc-level-4,.toc-level-5,.toc-level-6").addClass("noh2")
	}

	if ( $(".toc-level-3").length ) {
	} else {
		$(".toc-level-4,.toc-level-5,.toc-level-6").addClass("noh3")
	}

	$(".toc-widget").click(function(){
		$(".widget-area .widget").toggleClass("tocshow");
		$(".widget-area .widget").addClass("tochide");
	});

	$(".toc-widget").click(function(){
		$(".be-toc-widget").removeClass("tochide");
	});

	if ($(".toc-main").length > 0) {
		$(".be-beshare-toc").removeClass("tocno");
	}

	$(".toc-widget").mouseover(function(){
		$(".toc-prompt").fadeOut();
	});
})