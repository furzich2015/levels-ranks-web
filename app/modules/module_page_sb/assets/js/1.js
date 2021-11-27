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
}