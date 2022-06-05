<?php

$post_to_delete = [ "post_type" => "msu-copy", 'numberposts' => -1 ];

$copyrights = get_posts( $post_to_delete );

foreach($copyrights as $copyright) {
   wp_delete_post($copyright->ID, true);
}