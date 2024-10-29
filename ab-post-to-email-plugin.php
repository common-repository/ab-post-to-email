<?php
   /*
   Plugin Name: AB Post To Email
   Plugin URI: http://aleksandar.bjelosevic.info/abptoem
   Description: This plugin send automatic email to all subscriber's when new post is out, 
   Version: 1.10
   Author: Aleksandar Bjelosevic
   Author URI: http://aleksandar.bjelosevic.info
   License: GPL2
   */

  add_action( 'publish_post', 'ab_send_email_to_users' );

function ab_send_email_to_users( $post_id ) {
  $args1 = array(
    'role' => 'subscriber',
    'orderby' => 'user_nicename',
    'order' => 'ASC'
   );
   
$file = file_get_contents('files/template.html', FILE_USE_INCLUDE_PATH);

   $subscribers = get_users($args1);  

    foreach( $subscribers as $user ) {
        $post= get_post($post_id);
        $post_url = get_permalink( $post_id );
        $url = get_bloginfo('url');
		$img_post = get_the_post_thumbnail_url($post_id, 'medium_large');
		$post_title = get_the_title( $post_id ); 
        $author = get_userdata($post->post_author);
		$first_name = $author->first_name;
		$tekstclanka= wp_trim_words(get_post_field('post_content', $post_id), 30);
		$linkdoclanka=$post_url;
        $last_name = $author->last_name;
        $subject  = 'New post on site-'.$url;
        $message = str_replace("[naslovclanka]",$post_title,$file);
		$file = str_replace("[nazivsajta]",$url,$message);
		$message = str_replace("[postimage]", $img_post,$file);
		$file=str_replace("[linkdoclanka]",$linkdoclanka,$message);
		$message = str_replace("[tekstclanka]",$tekstclanka,$file);
        $headers = array('Content-Type: text/html; charset=UTF-8');

        wp_mail( $user->user_email, $subject, $message,$headers);
      }


}

?>