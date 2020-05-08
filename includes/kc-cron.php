<?php

// wp_remote_get
$response=  wp_remote_get('http://dsc');
if(is_wp_error($response)){
 echo 'Error Found ( '.$response->get_error_message().' )';
}


GET https://www.googleapis.com/youtube/v3/search?part=snippet&maxResults=25&q=kinefinity&key=[YOUR_API_KEY] HTTP/1.1

Authorization: Bearer [YOUR_ACCESS_TOKEN]
Accept: application/json


// cribbed from an example on youtube's docs, prolly doesn't wok for php
# https://developers.google.com/youtube/v3/docs/search/list
function searchByKeyword() {
  var results = YouTube.Search.list('id,snippet', {q: 'kinefinity', maxResults: 25});

  for(var i in results.items) {
    var item = results.items[i];
    Logger.log('[%s] Title: %s', item.id.videoId, item.snippet.title);
  }
}