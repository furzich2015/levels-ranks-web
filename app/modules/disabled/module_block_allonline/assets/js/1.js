if (servers != 0) {
    $.ajax({
        type: 'POST',
        url: "./app/modules/module_block_allonline/includes/ServerJS.php",
        data: ({data: servers}),
        dataType: 'json',
        global: false,
        async:true,
        success: function( data ) {

            var full_online = 0;
            var full_max_online = 0;
            
            for (var i = 0; i < data.length; i++) {
                full_online = full_online + data[i]['Players'];
                document.getElementById('server-full-online').innerHTML = full_online;
                full_max_online = full_max_online + data[i]['MaxPlayers'];
                document.getElementById('server-full-max-online').innerHTML = full_max_online;

                var b = 1;
                if(data[i]['players']) {
                    if( data[i]['players'].length > 0 ) {
                        console.log(data[i]['players']);
                            var modal = document.getElementById('server-players-online-' + i );
                            document.getElementById('connect_server_' + i).setAttribute("href", "steam://connect/" + data[i]['ip'] );
                    } else {
                        $('.btn_connect_' + i).prop("onclick", null).off("click");
                        $('.btn_connect_' + i).attr("href", "steam://connect/" + data[i]['ip'] )
                        $('.str_connect_' + i).attr("onclick", "document.location = 'steam://connect/" + data[i]['ip'] + "'" )
                    }
                }
            }
        }
    });
};