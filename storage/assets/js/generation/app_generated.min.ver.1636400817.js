/* Lazy Load XT 1.1.0 | MIT License */
!function(a,b,c,d){function e(a,b){return a[b]===d?t[b]:a[b]}function f(){var a=b.pageYOffset;return a===d?r.scrollTop:a}function g(a,b){var c=t["on"+a];c&&(w(c)?c.call(b[0]):(c.addClass&&b.addClass(c.addClass),c.removeClass&&b.removeClass(c.removeClass))),b.trigger("lazy"+a,[b]),k()}function h(b){g(b.type,a(this).off(p,h))}function i(c){if(z.length){c=c||t.forceLoad,A=1/0;var d,e,i=f(),j=b.innerHeight||r.clientHeight,k=b.innerWidth||r.clientWidth;for(d=0,e=z.length;e>d;d++){var l,m=z[d],q=m[0],s=m[n],u=!1,v=c||y(q,o)<0;if(a.contains(r,q)){if(c||!s.visibleOnly||q.offsetWidth||q.offsetHeight){if(!v){var x=q.getBoundingClientRect(),B=s.edgeX,C=s.edgeY;l=x.top+i-C-j,v=i>=l&&x.bottom>-C&&x.left<=k+B&&x.right>-B}if(v){m.on(p,h),g("show",m);var D=s.srcAttr,E=w(D)?D(m):q.getAttribute(D);E&&(q.src=E),u=!0}else A>l&&(A=l)}}else u=!0;u&&(y(q,o,0),z.splice(d--,1),e--)}e||g("complete",a(r))}}function j(){B>1?(B=1,i(),setTimeout(j,t.throttle)):B=0}function k(a){z.length&&(a&&"scroll"===a.type&&a.currentTarget===b&&A>=f()||(B||setTimeout(j,0),B=2))}function l(){v.lazyLoadXT()}function m(){i(!0)}var n="lazyLoadXT",o="lazied",p="load error",q="lazy-hidden",r=c.documentElement||c.body,s=b.onscroll===d||!!b.operamini||!r.getBoundingClientRect,t={autoInit:!0,selector:"img[data-src]",blankImage:"data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7",throttle:99,forceLoad:s,loadEvent:"pageshow",updateEvent:"load orientationchange resize scroll touchmove focus",forceEvent:"lazyloadall",oninit:{removeClass:"lazy"},onshow:{addClass:q},onload:{removeClass:q,addClass:"lazy-loaded"},onerror:{removeClass:q},checkDuplicates:!0},u={srcAttr:"data-src",edgeX:0,edgeY:0,visibleOnly:!0},v=a(b),w=a.isFunction,x=a.extend,y=a.data||function(b,c){return a(b).data(c)},z=[],A=0,B=0;a[n]=x(t,u,a[n]),a.fn[n]=function(c){c=c||{};var d,f=e(c,"blankImage"),h=e(c,"checkDuplicates"),i=e(c,"scrollContainer"),j=e(c,"show"),l={};a(i).on("scroll",k);for(d in u)l[d]=e(c,d);return this.each(function(d,e){if(e===b)a(t.selector).lazyLoadXT(c);else{var i=h&&y(e,o),m=a(e).data(o,j?-1:1);if(i)return void k();f&&"IMG"===e.tagName&&!e.src&&(e.src=f),m[n]=x({},l),g("init",m),z.push(m),k()}})},a(c).ready(function(){g("start",v),v.on(t.updateEvent,k).on(t.forceEvent,m),a(c).on(t.updateEvent,k),t.autoInit&&(v.on(t.loadEvent,l),l())})}(window.jQuery||window.Zepto||window.$,window,document),function(a){var b=a.lazyLoadXT;b.selector+=",video,iframe[data-src]",b.videoPoster="data-poster",a(document).on("lazyshow","video",function(c,d){var e=d.lazyLoadXT.srcAttr,f=a.isFunction(e),g=!1;d.attr("poster",d.attr(b.videoPoster)),d.children("source,track").each(function(b,c){var d=a(c),h=f?e(d):d.attr(e);h&&(d.attr("src",h),g=!0)}),g&&this.load()})}(window.jQuery||window.Zepto||window.$);

!function(d) {

    "use strict";

    Object.assign||Object.defineProperty(Object,"assign",{enumerable:!1,configurable:!0,writable:!0,value:function(e,r){"use strict";if(null==e)throw new TypeError("Cannot convert first argument to object");for(var t=Object(e),n=1;n<arguments.length;n++){var o=arguments[n];if(null!=o)for(var a=Object.keys(Object(o)),c=0,b=a.length;c<b;c++){var i=a[c],l=Object.getOwnPropertyDescriptor(o,i);void 0!==l&&l.enumerable&&(t[i]=o[i])}}return t}});

    "remove" in Element.prototype||(Element.prototype.remove=function(){this.parentNode&&this.parentNode.removeChild(this)});

    window.note = function(settings) {

        settings = Object.assign({},{
            callback:    false,
            content:     "",
            time:        4.5,
            type:        "info"
        }, settings);

        if(!settings.content.length) return;

        var create = function(name, attr, append, content) {
            var node = d.createElement(name);
            for(var val in attr) { if(attr.hasOwnProperty(val)) node.setAttribute(val, attr[val]); }
            if(content) node.insertAdjacentHTML("afterbegin", content);
            append.appendChild(node);
            if(node.classList.contains("note-item-hidden")) node.classList.remove("note-item-hidden");
            return node;
        };

        var noteBox = d.getElementById("notes") || create("div", { "id": "notes" }, d.body);
        var noteItem = create("div", {
                "class": "note-item",
                "data-show": "false",
                "role": "alert",
                "data-type": settings.type
            }, noteBox),
            noteItemText = create("div", { "class": "note-item-text" }, noteItem, settings.content),
            noteItemBtn = create("button", {
                "class": "note-item-btn",
                "type": "button",
            }, noteItem);

        var isVisible = function() {
            var coords = noteItem.getBoundingClientRect();
            return (
                coords.top >= 0 &&
                coords.left >= 0 &&
                coords.bottom <= (window.innerHeight || d.documentElement.clientHeight) &&
                coords.right <= (window.innerWidth || d.documentElement.clientWidth)
            );
        };

        var remove = function(el) {
            el = el || noteItem;
            el.setAttribute("data-show","false");
            window.setTimeout(function() {
                el.remove();
            }, 250);
            if(settings.callback) settings.callback(); // callback
        };

        noteItemBtn.addEventListener("click", function() { remove(); });

        window.setTimeout(function() {
            noteItem.setAttribute("data-show","true");
        }, 250);

        if(!isVisible()) remove(noteBox.firstChild);

        window.setTimeout(remove, settings.time * 1000);

    };

}(document);

if (avatar != 0) {
    $.post(domain + "/app/includes/js_controller.php", {
        function: 'avatars', 
        data: avatar
    }, function (e) {
        var jsonData = $.parseJSON(e);
        if(typeof jsonData[0] !== 'undefined')
        {
            for (var i = 0; i < avatar.length; i++) {
                document.getElementById(avatar[i]).setAttribute("src", jsonData[i]);
            }
        }
    })
};

function action_sidebar() {
    if ($('body').hasClass('sidebar-collapse') || $('body').hasClass('sidebar-open')) {
        if (window.innerWidth > 1026) {
            $.post(domain + "/app/includes/js_controller.php", {function: 'sidebar', setup: 1});
            $("body").removeClass("sidebar-collapse");
            $("body").removeClass("sidebar-open");
        } else {
            $.post(domain + "/app/includes/js_controller.php", {function: 'sidebar', setup: 1});
            $("body").removeClass("sidebar-collapse");
            $("body").removeClass("sidebar-open");
        }
    } else {
        if (window.innerWidth > 1026) {
            $.post(domain + "/app/includes/js_controller.php", {function: 'sidebar', setup: 0});
            $("body").removeClass("sidebar-open");
            $("body").addClass("sidebar-collapse");
        } else {
            $.post(domain + "/app/includes/js_controller.php", {function: 'sidebar', setup: 1});
            $("body").removeClass("sidebar-collapse");
            $("body").addClass("sidebar-open");
        }
    }
}

function action_treeview() {
    if ($(".treeview-menu").hasClass('menu-open')) {
        $(".treeview-menu").removeClass("menu-open");
        $( ".treeview-menu" ).slideUp();
    } else {
        $(".treeview-menu").addClass("menu-open")
        $( ".treeview-menu" ).slideDown();
    }
}

function set_options_data(data_id,change_data) {
    $.post( domain + "/app/includes/js_controller.php", { function: "set", option: data_id, change: change_data } );
    note({
        content: 'Сохранено',
        type: 'success',
        time: 3
    });
}

function set_options_data_select( name, value ) {
    $.post( domain + "/app/includes/js_controller.php", { function: "set", option: name, data: value } );
    note({
        content: 'Сохранено',
        type: 'success',
        time: 3
    });

    if( name == 'white_palette' ) {
        change_palette( value );
    } else if ( name == 'dark_palette' ) {
        change_palette( value );
    } else if ( name == 'background_image' ) {
        change_background_image( value );
    } else if ( name == 'graphics_container' ) {
       if( value == 'stretch' ) {
           $( '.container-fluid' ).css( 'max-width', '1920px' );
       } else if( value == 'static' ) {
           $( '.container-fluid' ).css( 'max-width', '1400px' );
       }
    }
}

function change_background_image( value ) {

    var str = domain + '/storage/cache/img/global/backgrounds/' + value;

    document.body.style.backgroundImage = 'url(' + str + ')';

}

function SaveInStorage(key, value) {
    if (typeof(Storage) !== 'undefined') {
        sessionStorage.setItem(key, value);
    }
}

function LoadFromStorage(key) {
    if (typeof(Storage) !== 'undefined') {
        return sessionStorage.getItem(key);
    }
    else {
        return '';
    }
}
//Notifications -->
var notifications = {};
var nonot = true;

function PlaySound(src) {
    var audio = new Audio(src);
    audio.play();
}

function main_notifications_icon_adjust(count,$html){
    if(count != 0){
        $('#main_notifications_badge').html(count);
        $('#main_notifications_badge').show();
        return true;
    }else{
        $('#main_notifications').html($html);
        $('#main_notifications_badge').html(false);
        $('#main_notifications_badge').hide();
        return false;
    }
}

var main_notifications_cooldown  = false;

function main_notifications_refresh(){
    $.ajax({
        type: 'POST',
        url: window.location.href,
        data: {entryid: 1},
        success: function(reuslt){
            if(IsJsonString(reuslt)){
                var data = jQuery.parseJSON(reuslt);
                SaveInStorage('notifications_count', data['count']);
                if(main_notifications_icon_adjust(data['count'],data['no_notifications'])){
                    if(nonot){$('#main_notifications').html('');}
                    data['notifications'].forEach(function(notification){
                        if(!notifications.hasOwnProperty(notification['id'])){
                            $('#main_notifications').prepend(notification['html']);
                            notifications[notification['id']] = true;
                            if(notification['seen'] == 0 && main_notifications_cooldown == false){
                                main_notifications_cooldown = true;
                                setTimeout(function(){main_notifications_cooldown = false;}, 3000)
                                PlaySound('storage/assets/sounds/Knock.mp3');
                            }
                        }
                    });

                    nonot = false;
                }else{
                    nonot = true;
                }
            }
        }
    });
}

function main_notifications_load(){
    var count_saved = LoadFromStorage('notifications_count');

    if($.isNumeric(count_saved)){
        main_notifications_icon_adjust(count_saved);
    }

    main_notifications_refresh();

    setInterval(main_notifications_refresh, 30000);
}

function main_notifications_chek(id){
    $.ajax({
        type: 'POST',
        url: window.location.href,
        data: {
            notific: id
        },
        success: function(){
            main_notifications_refresh();
        }
    });
}

//<-- Notifications
main_notifications_load();

function IsJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

$('#admin_idebar_right').click(function(){
    if($('.sidebar-right').hasClass("unshow"))
    {
        $('.sidebar-right').removeClass("unshow");
        SaveInStorage('panel_state', 'false');
    }
    else
    {
        $('.sidebar-right').addClass("unshow");
        SaveInStorage('panel_state', 'true');
    }
});

if(LoadFromStorage('panel_state') === 'true') {
    $('.sidebar-right').addClass("unshow");
} else {
    $('.sidebar-right').removeClass("unshow");
}

function updateURL(text) {
    if (history.pushState) {
        var baseUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
        var newUrl = baseUrl + text;
        history.pushState(null, null, newUrl);
    }
    else {
        console.warn('History API не поддерживается');
    }
}
$(".tooltip-js").on('mouseenter', function() {
    if ($('body').hasClass('sidebar-collapse')) {
    data = $(this).attr("data-tooltip-js");
    var offsetTop = $(this).offset().top + 7;
    var offsetLeft = $(this).offset().left + $(this).width() + 10;
    if (data.indexOf('srv-') != -1){
        offsetTop -= 10;
        offsetLeft += 30;
    }
    $('.box-button-'+data).css({
        top: offsetTop,
        left: offsetLeft -20,
        opacity: 0,
        display: 'inline-block'     
    }).animate({opacity: 1, left: offsetLeft},500);
    }
});
$(".tooltip-js").on('mouseleave', function() {
    data = $(this).attr("data-tooltip-js");
    $('.box-button-'+data).css({
        opacity: 0,
        display: 'none'
    });
    $('.box-button-'+data).offset({top: 0, left: 0});
});

if(location.href.includes("sb"))
{
    function SendAjaxSB(form, func, param1, param2, param3) {
        $.ajax({
            url:        domain+"app/modules/module_page_sb/includes/js_controller.php",
            type:       "POST",
            data:       $(form).serialize()+"&function="+func+"&param1="+param1+"&param2="+param2+"&param3="+param3,
            success: function(response) {
                var jsonData = $.parseJSON(response);
                if (!(typeof jsonData.success === 'undefined')){
                    note({
                        content: jsonData.success,
                        type: 'success',
                        time: 2
                    });
                    setTimeout(function(){ location.href = location.href; }, 5000);
                } else{
                    setTimeout(function(){doubleClickedCon = true;}, 1000);
                    note({
                        content: jsonData.error,
                        type: 'error',
                        time: 2
                    });
                    PlaySound('storage/assets/sounds/error.mp3');
                }
            }
        });
    }
    $(function() {
        $(".target").fadeOut(100);
        $("#home1").fadeIn(250);
        $("li.change").click(function(){
            let display = $( "#" + this.id + "1" ).css( "display" );
            if(display == 'none') { 
                $(".target").hide(400);
                $("#" + this.id + "1").show(400);
                $("li.change").removeClass("active-ma");
            }
            $(this).addClass("active-ma");
        });
    });
    $(document).on('input','[name="steam"]',function(){
        var steam = $('[name="steam"]');
        if(steam.val()){
            $.ajax({
                    type:   'POST',
                    url:    window.location.href, 
                    data:   'steamidload='+steam.val(),
                    cache:  false,
                    success:function(result){
                        if(result.trim()){
                            result=jQuery.parseJSON(result.trim());
                            if(result.img){
                                $('#img_profile').html('<img src="'+result.img+'">');
                            }else{
                                $('#img_profile').html('<img src="storage/cache/img/avatars_random/26.jpg">');}
                            }
                        }
                    });
            }else{
            $('#profile').html(false);
        }
    });
    function ChangeBlock() {
        if($('#selectgroup').val() == 'select') {
            $('#selectflags').show(600);
        } else {
            $('#selectflags').hide(600);
        }
    }
    if($('#selectgroup')) ChangeBlock();
    function Reason() {
        if($('#reason').val() == 'my') {
            $('#myreason').show(200);
        } else {
            $('#myreason').hide(200);
        }
    }
    let inProgress = false;
    let startFrom = 2; 
    $(document).ready(function() {
        $('#more_ajax').click(function() {
            let server = $('#server').val();
            console.log(startFrom);
            if (!inProgress) {
                $.ajax({
                    url:    location.href,
                    method: 'POST',
                    data: {
                        "startpagination" : startFrom,
                        "server" : server,
                        "type" : $('select[name=type]').val()
                    },
                    beforeSend: function() {
                        inProgress = true;
                    }
                }).done(function(data){
                    data = jQuery.parseJSON(data);
                    if(typeof data != 'string') {
                        $.each(data, function(index, data){
                            if(data.delete != '') {
                                del = "<th class='text-center'>" + data.delete + "</th>";
                                edit = "<th class='text-center'>" + data.edit + "</th>";
                            }
                            else 
                            {
                                del = '';
                                edit = '';
                            }
                            if(data.icon == 'ban') type = '<i class="zmdi zmdi-block zmdi-hc-fw"></i>';
                            else type = '<i class="zmdi zmdi-mic-off zmdi-hc-fw"></i>';
                            $("#res_bans").append("<tr><th class='text-center'>" + type + "</th><th class='text-center'>" + data.created + "</th><th class='text-center'>" + data.name + "</th><th class='text-center'>" + data.reason + "</th><th class='text-center'>" + data.length + "</th>" + del + edit + "</tr>");
                        });
                    } else {
                        note({
                            content: 'Данных нет!',
                            type: 'error',
                            time: 2
                        });
                        PlaySound('storage/assets/sounds/error.mp3');
                    }
                    inProgress = false;
                    startFrom += 1;
                });
            }
        });
        let acc = document.getElementsByClassName("hover-tb");
        for (i = 0; i < acc.length; i++) 
        {
            acc[i].addEventListener("click", function() 
            {
                let panel = this.nextElementSibling.children[0];            
                if (panel.children[0].style.maxHeight) 
                {
                    panel.children[0].style.maxHeight = null;
                }
                else 
                {
                    panel.children[0].style.maxHeight = panel.children[0].scrollHeight + "px";
                    $( '.opener' ).not( "#" + panel.children[0].id ).css('max-height', '');
                }
            });
        }
        $("#searchmore").click(function() {
            let display = $("#searchdiv").css( "display" );
            if(display == 'none')
                $("#searchdiv").slideDown("fast");
            else 
                $("#searchdiv").slideUp("fast");
        });
        $("#searchban").submit(function (e) {
            let serverid = $("#server").val();
            e.preventDefault();
            $.ajax({
                type:   "POST",
                url:    domain+"app/modules/module_page_sb/includes/js_controller.php",
                data:   $(this).serialize()+"&function=getbans&startpagination=1&server=" + serverid,
                success: function(data) {
                    data = jQuery.parseJSON(data);
                    $("#result").html('<center><img src="app/modules/module_page_sb/temp/img/loader.gif" style="max-width: 15%;"></center>');
                    $("#res_bans").html('');
                    if(typeof data != 'string') {
                        startFrom = 2;
                        $.each(data, function(index, data){
                            $('#result > center > img').remove();
                            if(data.delete != '') {
                                del = "<th class='text-center'>" + data.delete + "</th>";
                                edit = "<th class='text-center'>" + data.edit + "</th>";
                            }
                            else 
                            {
                                del = '';
                                edit = '';
                            }
                            if(data.icon == 'ban') type = '<i class="zmdi zmdi-block zmdi-hc-fw"></i>';
                            else type = '<i class="zmdi zmdi-mic-off zmdi-hc-fw"></i>';
                            console.log(data.icon);
                            $("#res_bans").append("<tr><th class='text-center'>" + type + "</th><th class='text-center'>" + data.created + "</th><th class='text-center'>" + data.name + "</th><th class='text-center'>" + data.reason + "</th><th class='text-center'>" + data.length + "</th>" + del + edit + "</tr>");
                        });
                        
                        $("#searchmore").slideDown();
                    } else {
                        $("#result").html("<div class='text-center' style='margin: 10px;'>" + data + "</div>");
                        
                    }
                    setTimeout(function() { inProgress = false; }, 500);
                }
            });
        });
    });
    function ChangeServer(serverid) {
        $.ajax({
            url:    location.href,
            method: 'POST',
            data: {
                "startpagination" : 1,
                "server" : serverid,
                "type": $('select[name=type]').val()
            },
            success: function(data) {
                data = jQuery.parseJSON(data);
                $("#result").html('<center><img src="app/modules/module_page_sb/temp/img/loader.gif" style="max-width: 15%;"></center>');
                $("#res_bans").html('');
                if(typeof data != 'string') {
                    startFrom = 2;
                    $.each(data, function(index, data){
                        $('#result > center > img').remove();
                        if(data.delete != '') {
                            del = "<th class='text-center'>" + data.delete + "</th>";
                            edit = "<th class='text-center'>" + data.edit + "</th>";
                        }
                        else 
                        {
                            del = '';
                            edit = '';
                        }
                        if(data.icon == 'ban') type = '<i class="zmdi zmdi-block zmdi-hc-fw"></i>';
                        else type = '<i class="zmdi zmdi-mic-off zmdi-hc-fw"></i>';
                        $("#res_bans").append("<tr><th class='text-center'>" + type + "</th><th class='text-center'>" + data.created + "</th><th class='text-center'>" + data.name + "</th><th class='text-center'>" + data.reason + "</th><th class='text-center'>" + data.length + "</th>" + del + edit + "</tr>");
                    });
                    $("#searchmore").slideDown();
                } else {
                    $("#result").html("<div class='text-center' style='margin: 10px;'>Нет информации</div>");
                    
                    $("#searchmore").slideUp();
                }
                setTimeout(function() { inProgress = false; }, 500);
            }
        });
    }
}$(document).ready(function(){
	$(document).on('input','[name="promocode"],[name="amount"],[name="steam"]',function(){
	var promocode=$('[name="promocode"]'),
		amount=$('[name="amount"]'),
		steam=$('[name="steam"]');

	if(promocode.val() && $.isNumeric(amount.val()) && steam.val()){

		$.ajax({
				type:'POST',
				url: window.location.href, 
				data:'promocode='+promocode.val()+'&amount='+amount.val()+'&steamid='+steam.val(),
				cache:false,
			success:function(result){
				if(result.trim()){
					result=jQuery.parseJSON(result.trim());
					if(result.result){
						$('#promoresult').html(result.result);
					}else{
						$('#promoresult').html(false);}
					}
				}
			});
	}else{
		$('#promoresult').html(false);
	}
});
	$(document).on('input','[name="steam"]',function(){
	var steam=$('[name="steam"]');

	if(steam.val()){

		$.ajax({
				type:'POST',
				url: window.location.href, 
				data:'steamidload='+steam.val(),
				cache:false,
			success:function(result){
				if(result.trim()){
					result=jQuery.parseJSON(result.trim());
					if(result.img){
						$('#profile').html('<img class="badge" width="64" src="'+result.img+'"><br><small>'+result.name+'</small>');
					}else{
						$('#profile').html(false);}
					}
				}
			});
	}else{
		$('#profile').html(false);
	}
});
	$('form').submit(function(event){
		if($(this).attr('data-default'))
		{
			var del = $(this).attr('data-get');
			event.preventDefault();
			var mess;
			$.ajax({
				type: $(this).attr('method'),
				url: window.location.href,
				data: new FormData(this),
				contentType: false,
				cache: false,
				processData: false,
				success: function(result){
					mess = jQuery.parseJSON(result.trim());
					if(mess.status)
					{
						if(mess.status == 'success')
						{
							setTimeout(function(){
						
								if(del){
									removeParam(del);
								}else{
									window.location.reload();
								}
							}, 4100);
						}
							note({
							  content: mess.text,
							  type: mess.status,
							  time: 4
							});
					}
					else if(mess.location)
					{	
						window.location.href = mess.location;
					}
					else 
					{
						$('#resultForm').html(mess.text);
						document.getElementById('punsh').click();
					}
				}
			});
		}
	});

        document.addEventListener("click", removeElem("col-md-6", "data-del", "delete"));
	});

	function removeElem(delElem, attribute, attributeName) {
	  if (!(delElem && attribute && attributeName)) return;
	  return function(e) {
	    let target = e.target;
	    if (!(target.hasAttribute(attribute) ?
	        (target.getAttribute(attribute) === attributeName ? true : false) : false)) return;
	    	removeParam(target.getAttribute('data-get'));
	    let elem = target;
	    while (target != this) {
	      if (target.classList.contains(delElem)) {
	        target.remove();
	        return;
	      }
	      target = target.parentNode;
	    }
	    return;
	  };
	}

	function removeParam(key) {
	  var splitUrl = window.location.href.split('?'),
	    rtn = splitUrl[0],
	    param,
	    params_arr = [],
	    queryString = (window.location.href.indexOf("?") !== -1) ? splitUrl[1] : '';
	  if (queryString !== '') {
	    params_arr = queryString.split('&');
	    for (var i = params_arr.length - 1; i >= 0; i -= 1) {
	      param = params_arr[i].split('=')[0];
	      if (param === key) {
	        params_arr.splice(i, 1);
	      }
	    }
	    rtn = rtn + '?' + params_arr.join('&');
	  }
	  window.location.href = rtn;
	}
!function(d) {

  "use strict";

  Object.assign||Object.defineProperty(Object,"assign",{enumerable:!1,configurable:!0,writable:!0,value:function(e,r){"use strict";if(null==e)throw new TypeError("Cannot convert first argument to object");for(var t=Object(e),n=1;n<arguments.length;n++){var o=arguments[n];if(null!=o)for(var a=Object.keys(Object(o)),c=0,b=a.length;c<b;c++){var i=a[c],l=Object.getOwnPropertyDescriptor(o,i);void 0!==l&&l.enumerable&&(t[i]=o[i])}}return t}});

  "remove" in Element.prototype||(Element.prototype.remove=function(){this.parentNode&&this.parentNode.removeChild(this)});

  window.note = function(settings) {

    settings = Object.assign({},{
      callback:    false,
      content:     "",
      time:        4.5,
      type:        "info"
    }, settings);

    if(!settings.content.length) return;

    var create = function(name, attr, append, content) {
      var node = d.createElement(name);
      for(var val in attr) { if(attr.hasOwnProperty(val)) node.setAttribute(val, attr[val]); }
      if(content) node.insertAdjacentHTML("afterbegin", content);
      append.appendChild(node);
      if(node.classList.contains("note-item-hidden")) node.classList.remove("note-item-hidden");
      return node;
    };

    var noteBox = d.getElementById("notes") || create("div", { "id": "notes" }, d.body);
    var noteItem = create("div", {
        "class": "note-item",
        "data-show": "false",
        "role": "alert",
        "data-type": settings.type
      }, noteBox),
      noteItemText = create("div", { "class": "note-item-text" }, noteItem, settings.content),
      noteItemBtn = create("button", {
        "class": "note-item-btn",
        "type": "button",
      }, noteItem);

    var isVisible = function() {
      var coords = noteItem.getBoundingClientRect();
      return (
        coords.top >= 0 &&
        coords.left >= 0 &&
        coords.bottom <= (window.innerHeight || d.documentElement.clientHeight) && 
        coords.right <= (window.innerWidth || d.documentElement.clientWidth) 
      );
    };
   
    var remove = function(el) {
      el = el || noteItem;
      el.setAttribute("data-show","false");
      window.setTimeout(function() {
        el.remove();
      }, 250);
      if(settings.callback) settings.callback(); // callback
    };

    noteItemBtn.addEventListener("click", function() { remove(); });

    window.setTimeout(function() {
      noteItem.setAttribute("data-show","true");
      PlaySound('storage/assets/sounds/'+settings.type+'.mp3');
    }, 250);

    if(!isVisible()) remove(noteBox.firstChild);

    window.setTimeout(remove, settings.time * 1000);

  };

}(document);class Accordion {
  constructor(el) {
    // Store the <details> element
    this.el = el;
    // Store the <summary> element
    this.sumiri = el.querySelector('.sumiri');
    // Store the <div class="content"> element
    this.contint = el.querySelector('.contint');

    // Store the animation object (so we can cancel it if needed)
    this.animation = null;
    // Store if the element is closing
    this.isClosing = false;
    // Store if the element is expanding
    this.isExpanding = false;
    // Detect user clicks on the summary element
    this.sumiri.addEventListener('click', (e) => this.onClick(e));
  }

  onClick(e) {
    // Stop default behaviour from the browser
    e.preventDefault();
    // Add an overflow on the <details> to avoid content overflowing
    this.el.style.overflow = 'hidden';
    // Check if the element is being closed or is already closed
    if (this.isClosing || !this.el.open) {
      this.open();
    // Check if the element is being openned or is already open
    } else if (this.isExpanding || this.el.open) {
      this.shrink();
    }
  }

  shrink() {
    // Set the element as "being closed"
    this.isClosing = true;
    
    // Store the current height of the element
    const startHeight = `${this.el.offsetHeight}px`;
    // Calculate the height of the summary
    const endHeight = `${this.sumiri.offsetHeight}px`;
    
    // If there is already an animation running
    if (this.animation) {
      // Cancel the current animation
      this.animation.cancel();
    }
    
    // Start a WAAPI animation
    this.animation = this.el.animate({
      // Set the keyframes from the startHeight to endHeight
      height: [startHeight, endHeight]
    }, {
      duration: 400,
      easing: 'ease-out'
    });
    
    // When the animation is complete, call onAnimationFinish()
    this.animation.onfinish = () => this.onAnimationFinish(false);
    // If the animation is cancelled, isClosing variable is set to false
    this.animation.oncancel = () => this.isClosing = false;
  }

  open() {
    // Apply a fixed height on the element
    this.el.style.height = `${this.el.offsetHeight}px`;
    // Force the [open] attribute on the details element
    this.el.open = true;
    // Wait for the next frame to call the expand function
    window.requestAnimationFrame(() => this.expand());
  }

  expand() {
    // Set the element as "being expanding"
    this.isExpanding = true;
    // Get the current fixed height of the element
    const startHeight = `${this.el.offsetHeight}px`;
    // Calculate the open height of the element (summary height + content height)
    const endHeight = `${this.sumiri.offsetHeight + this.contint.offsetHeight}px`;
    
    // If there is already an animation running
    if (this.animation) {
      // Cancel the current animation
      this.animation.cancel();
    }
    
    // Start a WAAPI animation
    this.animation = this.el.animate({
      // Set the keyframes from the startHeight to endHeight
      height: [startHeight, endHeight]
    }, {
      duration: 400,
      easing: 'ease-out'
    });
    // When the animation is complete, call onAnimationFinish()
    this.animation.onfinish = () => this.onAnimationFinish(true);
    // If the animation is cancelled, isExpanding variable is set to false
    this.animation.oncancel = () => this.isExpanding = false;
  }

  onAnimationFinish(open) {
    // Set the open attribute based on the parameter
    this.el.open = open;
    // Clear the stored animation
    this.animation = null;
    // Reset isClosing & isExpanding
    this.isClosing = false;
    this.isExpanding = false;
    // Remove the overflow hidden and the fixed height
    this.el.style.height = this.el.style.overflow = '';
  }
}

document.querySelectorAll('details').forEach((el) => {
  new Accordion(el);
});if ($('#nestable').length > 0) {
    !function (d, h, p, l) {
        var a = "ontouchstart" in p, c = function () {
            var t = p.createElement("div"), e = p.documentElement;
            if (!("pointerEvents" in t.style)) return !1;
            t.style.pointerEvents = "auto", t.style.pointerEvents = "x", e.appendChild(t);
            var s = h.getComputedStyle && "auto" === h.getComputedStyle(t, "").pointerEvents;
            return e.removeChild(t), !!s
        }(), s = {
            listNodeName: "ol",
            itemNodeName: "li",
            rootClass: "dd",
            listClass: "dd-list",
            itemClass: "dd-item",
            dragClass: "dd-dragel",
            handleClass: "dd-handle",
            placeClass: "dd-placeholder",
            noDragClass: "dd-nodrag",
            emptyClass: "dd-empty",
            group: 0,
            maxDepth: 5,
            threshold: 20
        };

        function i(t, e) {
            this.w = d(p), this.el = d(t), this.options = d.extend({}, s, e), this.init()
        }

        i.prototype = {
            init: function () {
                var s = this;
                s.reset(), s.el.data("nestable-group", this.options.group), s.placeEl = d('<div class="' + s.options.placeClass + '"/>'), d.each(this.el.find(s.options.itemNodeName), function (t, e) {
                    s.setParent(d(e))
                }), s.el.on("click", "button", function (t) {
                    if (!s.dragEl) {
                        var e = d(t.currentTarget);
                        e.data("action"), e.parent(s.options.itemNodeName)
                    }
                });
                var t = function (t) {
                    var e = d(t.target);
                    if (!e.hasClass(s.options.handleClass)) {
                        if (e.closest("." + s.options.noDragClass).length) return;
                        e = e.closest("." + s.options.handleClass)
                    }
                    e.length && !s.dragEl && (s.isTouch = /^touch/.test(t.type), s.isTouch && 1 !== t.touches.length || (t.preventDefault(), s.dragStart(t.touches ? t.touches[0] : t)))
                }, e = function (t) {
                    s.dragEl && s.dragMove(t.touches ? t.touches[0] : t)
                }, i = function (t) {
                    s.dragEl && (t.preventDefault(), s.dragStop(t.touches ? t.touches[0] : t))
                };
                a && (s.el[0].addEventListener("touchstart", t, !1), h.addEventListener("touchmove", e, !1), h.addEventListener("touchend", i, !1), h.addEventListener("touchcancel", i, !1)), s.el.on("mousedown", t), s.w.on("mousemove", e), s.w.on("mouseup", i)
            }, serialize: function () {
                var i = this;
                return step = function (t, e) {
                    var s = [];
                    return t.children(i.options.itemNodeName).each(function () {
                        var t = d(this), e = d.extend({}, t.data());
                        t.children(i.options.listNodeName);
                        s.push(e)
                    }), s
                }, step(i.el.find(i.options.listNodeName).first(), 0)
            }, serialise: function () {
                return this.serialize()
            }, reset: function () {
                this.mouse = {
                    offsetX: 0,
                    offsetY: 0,
                    startX: 0,
                    startY: 0,
                    lastX: 0,
                    lastY: 0,
                    nowX: 0,
                    nowY: 0,
                    distX: 0,
                    distY: 0,
                    dirAx: 0,
                    dirX: 0,
                    dirY: 0,
                    lastDirX: 0,
                    lastDirY: 0,
                    distAxX: 0,
                    distAxY: 0
                }, this.isTouch = !1, this.moving = !1, this.dragEl = null, this.dragRootEl = null, this.dragDepth = 0, this.hasNewRoot = !1, this.pointEl = null
            }, expandAll: function () {
                var t = this;
                t.el.find(t.options.itemNodeName).each(function () {
                    t.expandItem(d(this))
                })
            }, setParent: function (t) {
            }, unsetParent: function (t) {
            }, dragStart: function (t) {
                var e = this.mouse, s = d(t.target), i = s.closest(this.options.itemNodeName);
                this.placeEl.css("height", i.height()), e.offsetX = t.offsetX !== l ? t.offsetX : t.pageX - s.offset().left, e.offsetY = t.offsetY !== l ? t.offsetY : t.pageY - s.offset().top, e.startX = e.lastX = t.pageX, e.startY = e.lastY = t.pageY, this.dragRootEl = this.el, this.dragEl = d(p.createElement(this.options.listNodeName)).addClass(this.options.listClass + " " + this.options.dragClass), this.dragEl.css("width", i.width()), i.after(this.placeEl), i[0].parentNode.removeChild(i[0]), i.appendTo(this.dragEl), d(p.body).append(this.dragEl), this.dragEl.css({
                    left: t.pageX - e.offsetX,
                    top: t.pageY - e.offsetY
                });
                var a, o, n = this.dragEl.find(this.options.itemNodeName);
                for (a = 0; a < n.length; a++) (o = d(n[a]).parents(this.options.listNodeName).length) > this.dragDepth && (this.dragDepth = o)
            }, dragStop: function (t) {
                var e = this.dragEl.children(this.options.itemNodeName).first();
                e[0].parentNode.removeChild(e[0]), this.placeEl.replaceWith(e), this.dragEl.remove(), this.el.trigger("change"), this.hasNewRoot && this.dragRootEl.trigger("change"), this.reset()
            }, dragMove: function (t) {
                var e, s = this.options, i = this.mouse;
                this.dragEl.css({
                    left: t.pageX - i.offsetX,
                    top: t.pageY - i.offsetY
                }), i.lastX = i.nowX, i.lastY = i.nowY, i.nowX = t.pageX, i.nowY = t.pageY, i.distX = i.nowX - i.lastX, i.distY = i.nowY - i.lastY, i.lastDirX = i.dirX, i.lastDirY = i.dirY, i.dirX = 0 === i.distX ? 0 : 0 < i.distX ? 1 : -1, i.dirY = 0 === i.distY ? 0 : 0 < i.distY ? 1 : -1;
                var a = Math.abs(i.distX) > Math.abs(i.distY) ? 1 : 0;
                if (!i.moving) return i.dirAx = a, void (i.moving = !0);
                i.dirAx !== a ? (i.distAxX = 0, i.distAxY = 0) : (i.distAxX += Math.abs(i.distX), 0 !== i.dirX && i.dirX !== i.lastDirX && (i.distAxX = 0), i.distAxY += Math.abs(i.distY), 0 !== i.dirY && i.dirY !== i.lastDirY && (i.distAxY = 0)), i.dirAx = a;
                var o = !1;
                if (c || (this.dragEl[0].style.visibility = "hidden"), this.pointEl = d(p.elementFromPoint(t.pageX - p.body.scrollLeft, t.pageY - (h.pageYOffset || p.documentElement.scrollTop))), c || (this.dragEl[0].style.visibility = "visible"), this.pointEl.hasClass(s.handleClass) && (this.pointEl = this.pointEl.parent(s.itemNodeName)), this.pointEl.hasClass(s.emptyClass)) o = !0; else if (!this.pointEl.length || !this.pointEl.hasClass(s.itemClass)) return;
                var n = this.pointEl.closest("." + s.rootClass),
                    l = this.dragRootEl.data("nestable-id") !== n.data("nestable-id");
                if (!i.dirAx || l || o) {
                    if (l && s.group !== n.data("nestable-group")) return;
                    if (this.dragDepth - 1 + this.pointEl.parents(s.listNodeName).length > s.maxDepth) return;
                    var r = t.pageY < this.pointEl.offset().top + this.pointEl.height() / 2;
                    this.placeEl.parent(), o ? ((e = d(p.createElement(s.listNodeName)).addClass(s.listClass)).append(this.placeEl), this.pointEl.replaceWith(e)) : r ? this.pointEl.before(this.placeEl) : this.pointEl.after(this.placeEl), this.dragRootEl.find(s.itemNodeName).length || this.dragRootEl.append('<div class="' + s.emptyClass + '"/>'), l && (this.dragRootEl = n, this.hasNewRoot = this.el[0] !== this.dragRootEl[0])
                }
            }
        }, d.fn.nestable = function (e) {
            var s = this;
            return this.each(function () {
                var t = d(this).data("nestable");
                t ? "string" == typeof e && "function" == typeof t[e] && (s = t[e]()) : (d(this).data("nestable", new i(this, e)), d(this).data("nestable-id", (new Date).getTime()))
            }), s || this
        }
    }(window.jQuery || window.Zepto, window, document), $(document).ready(function () {
        var t = function (t) {
            var e = t.length ? t : $(t.target), s = e.data("output");
            window.JSON ? s.val(window.JSON.stringify(e.nestable("serialize"))) : s.val("JSON browser support required for this demo.")
        };
        $("#nestable").nestable({group: 1}).on("change", t), t($("#nestable").data("output", $("#nestable-output")))
    }), $(document).ready(function () {
        $("#load").hide(), $(".dd").on("change", function () {
            $("#load").show();
            var t = {data: $("#nestable-output").val()};
            $.ajax({
                type: "POST", url: window.location.href, data: t, cache: !1, success: function (t) {
                    $("#load").hide()
                }, error: function (t, e, s) {
                }
            })
        })
    });
}



function delete_server(element) {
    $.post( window.location.href, { del_server: element.closest('tr').id } );
    note({
        content: 'Сервер удалён',
        type: 'success',
        time: 3
    });
    element.closest('tr').remove();
}

function action_db_delete_table(id,element) {
    $.post( window.location.href, { function: "delete", table: element } );
    note({
        content: 'Таблица удалена',
        type: 'success',
        time: 3
    });
    id.closest('tr').remove();
    $("tr." + element).remove();
}
let doubleClickedCon = true;
function addConection(){
    if(doubleClickedCon){
        doubleClickedCon = false;
        $.ajax({
            url: window.location.href,
            type: 'post',
            data: $('#form-add-conection').serialize()+"&function=add_conection",
            success: function(response){
                var jsonData = JSON.parse(response);
                if (!(typeof jsonData.success === 'undefined')){
                    note({
                        content: jsonData.success,
                        type: 'success',
                        time: 2
                    });
                    setTimeout(function(){window.location = window.location.href.replace(window.location.hash, '#');location.reload(true);
                } , 2000);
                }
                else{
                    setTimeout(function(){doubleClickedCon = true;} ,1000);
                    note({
                        content: jsonData.error,
                        type: 'error',
                        time: 4
                    });
                }
            },
        });
    }
}
function changeConnection(mod) {

    document.getElementById('con_mod_name').innerHTML = 'Мод: '+mod;
    document.getElementById('con_mod_id').value = mod;
    document.getElementById('add_conection_button').setAttribute("href", "#add_connect");
    document.getElementById('custom_mod_wrapper').setAttribute("style", "display: none;");
    document.getElementById('con_table_name').value = "";
    document.getElementById('rank_pack_connection').setAttribute("style", "display: none;");
    var select = document.querySelector('select[name="game_mod"]');
    select[0].value = 730;
    select[0].textContent  = 'CS:GO';
    select[1].value = 240;
    select[1].textContent  = 'CS:S';
    
    if(mod == 'custom') {
        document.getElementById('custom_mod_wrapper').setAttribute("style", "display: block;");
    }
    let db_hide = document.querySelectorAll('.con_active');
    for (let i = 0; i < db_hide.length; i++) {
        db_hide[i].classList.remove("con_active");
    }
    let db_show = document.querySelectorAll('.con_'+mod);
    for (let i = 0; i < db_show.length; i++) {
        db_show[i].classList.add("con_active");
    }
    if (mod == 'LevelsRanks') {
        document.getElementById('con_table_name').value = "lvl_base";
        document.getElementById('rank_pack_connection').setAttribute("style", "display: block;");
    } else if (mod == 'Vips') {
        document.getElementById('con_table_name').value = "vip_";
    } else if (mod == 'SourceBans') {
        document.getElementById('con_table_name').value = "sb_";
    } else if (mod == 'lk'){
        document.getElementById('con_table_name').value = "lk";
        select[0].value = 1;
        select[0].textContent  = 'LK Impulse';
        select[1].value = 2;
        select[1].textContent  = 'LK D4ck';
    }

}
function changeConnect(value){
    if (value == 'db') {
        document.getElementById('db_select_con').setAttribute("style", "display: none;");
        document.getElementById('db_option_con').setAttribute("style", "display: flex;");
    } else {
        document.getElementById('db_select_con').setAttribute("style", "display: block;");
        document.getElementById('db_option_con').setAttribute("style", "display: none;");
    }
}
function changeNameModule(){
    let val = document.getElementById('mods').value;
    let db_show = document.querySelectorAll('.con_'+document.getElementById('custom_mod_name').value);
    for (let i = 0; i < db_show.length; i++) {
        db_show[i].classList.add("con_active");
    }
    if (val == 'custom') {
        document.getElementById('con_mod_name').innerHTML = 'Mod: '+document.getElementById('custom_mod_name').value;
        document.getElementById('con_mod_id').value = document.getElementById('custom_mod_name').value;
    }
}
function show_hide_password(target){
    var input = document.getElementById('con_password');
    if (input.getAttribute('type') == 'password') {
        target.classList.add('view');
        input.setAttribute('type', 'text');
    } else {
        target.classList.remove('view');
        input.setAttribute('type', 'password');
    }
    return false;
}
function change_shablon(id)
{
    if(id == 1)
    {
        //
    }
}let minplayers = 0,maxplayers = 0, info, players;
if (servers != 0) {
    $.ajax({
        type: 'POST',
        url: domain+"app/modules/module_block_main_servers_monitoring/includes/js_controller.php",
        data: ({data: servers, my: "yes"}),
        dataType: 'json',
        global: false,
        async:true,
        success: function( data ) {
            for (var i = 0; i < data.length; i++) {
                info = data[i]["info"];
                players = data[i]["players"];
                minplayers += info['Players'];
                maxplayers += info['Playersmax'];
                document.getElementById('server-name-' + i).innerHTML = info['Name'];
                document.getElementById('server-map-image-' + i).setAttribute("src", domain+info['Map_image']);
                document.getElementById('server-image-' + i).setAttribute("src", domain+"storage/cache/img/mods/"+info["Appid"]+".png");
                document.getElementById('server-players-' + i).innerHTML = info['Players'] + "/" + info['Playersmax'];
                document.getElementById('server-map-' + i).innerHTML = info['Map'];
                document.getElementById('online_gr-' + i).setAttribute("style", "width:" + 100*info['Players']/info['Playersmax'] + "%");
                document.getElementById('server-ip-' + i).innerHTML = info['Ip']+":"+info["Port"];

                var b = 1;
                if(players) {
                    if( players.length > 0 ) {
                        console.log(players);
                        for (var i2 = 0; i2 < players.length; i2++) {
                            var str = '<tr>' +
                                '<th class="text-center">' + b++ + '</th>' +
                                '<th class="text-center">' + players[i2]['Name'] + '</th>' +
                                '<th class="text-center">' + players[i2]['Score'] + '</th>' +
                                '<th class="text-center">' + players[i2]['Time'] + '</th>' +
                                '</tr>';
                            po = document.getElementById('players_online_' + i);
                            po.insertAdjacentHTML('beforeend', str);
                        }
                            var modal = document.getElementById('server-players-online-' + i );
                            document.getElementById('connect_server_' + i).setAttribute("href", "steam://connect/" + info['Ip']+":"+info["Port"] );
                    } else {
                        $('.btn_connect_' + i).prop("onclick", null).off("click");
                        $('.btn_connect_' + i).attr("href", "steam://connect/" + info['Ip']+":"+info["Port"] )
                        $('.str_connect_' + i).attr("onclick", "document.location = 'steam://connect/" + info['Ip']+":"+info["Port"] + "'" )
                    }
                }
            }
            document.getElementById('min_players').innerHTML = minplayers;
            document.getElementById('max_players').innerHTML = maxplayers;
        }
    });

    function get_players_data( i ) {
        var modal = document.getElementById('server-players-online-' + i );
        modal.style.display = "block";
    }

    function close_modal( i ) {
        var modal = document.getElementById('server-players-online-' + i );
        modal.style.display = "none";
    }
};