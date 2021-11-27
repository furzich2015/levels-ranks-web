<script>servers.push(<?php echo json_encode( action_array_keep_keys( $General->server_list, ['ip', 'fakeip'] ) )?>);</script>
<div class="row">
         <div class="col-md-12">
         <div class=card>
           <div class="all_in">
                <div class="text-center ; svetik">Общий онлайн: <div class="all_on" id="server-full-online">-</div>/<div class="all_on" id="server-full-max-online">-</div></div>
          </div>
         </div>
        </div>
    </div>