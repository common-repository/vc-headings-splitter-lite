<?php


/**
 * VC Splitter - Shortcode
 */
class wps_vcs_hs_frontend_shortcode {


  public function __construct() {
    if (!is_admin()) {
      add_shortcode('vc_splitter', array($this, 'vc_splitter'));
      add_action('wp_print_footer_scripts', array($this, 'vc_tracker'));
    }
  } // __construct


  public function vc_tracker() {
    global $wps_vcs_hs_active, $post;

    if (!empty($post) && is_array($wps_vcs_hs_active)) {
      $wps_vcs_hs_active = json_encode($wps_vcs_hs_active);

      echo '<script type="text/javascript">
          var tracked = false;
          var post_ID = "' . $post->ID . '";
          var split_test_ID = 0;
          
          jQuery(document).ready(function($){

              $("a, button, input[type=\'button\']").on("click", function(e){
              
              // Ultimate shortcodes fix
              if ($(this).parent().is("a") == false) {
                  var target = $(this);
                  var parents = $(target).closest(".wps-vcs-wrapper");
                  var split_test_ID = $(parents).data("split-id");
                  var wrapper_ID = $(parents).data("wrapper-id");
                  
                  if ($(this).closest(\'.wps-vcs-wrapper\').length) {
                    // Inside a wrapper
                    tracked = true;
                    
                    if (is_link(target) || is_button(target)) {
                      if (is_split_trigger(target)) {
                      split_test_ID = $(target).data("split-test-id");
                        $.post(ajaxurl, {action:"wps_vcs_hs_track_split", splits:' . $wps_vcs_hs_active . ', post_ID:"' . $post->ID . '", wrapper_ID:wrapper_ID, split_ID:split_test_ID}, function(response){});
                      } else {
                        $.post(ajaxurl, {action:"wps_vcs_hs_click", post_ID:"' . $post->ID . '", wrapper_ID:wrapper_ID, split_ID:split_test_ID}, function(response){});
                      }
                    }
                  } else {
                    // Outside wrapper
                    tracked = true;
                    
                    if (is_link(target) || is_button(target)) {
                      if (is_split_trigger(target)) {
                      split_test_ID = $(target).data("split-test-id");
                        $.post(ajaxurl, {action:"wps_vcs_hs_track_split", splits:' . $wps_vcs_hs_active . ', post:"' . $post->ID . '", split_test_ID:split_test_ID}, function(response){});
                      } else {
                        $.post(ajaxurl, {action:"wps_vcs_hs_track_link", post:"' . $post->ID . '", link:is_link(target), button:is_button(target)}, function(response){});
                      }
                    }
                  }
              }
              });

          });
          jQuery(window).on("beforeunload",function (e) {

            if (!tracked) {
              jQuery.ajaxSetup({async:false});
              jQuery.post(ajaxurl, {action: "wps_vcs_hs_track_bounce", splits:' . $wps_vcs_hs_active . ', post:post_ID}, function(response){});
            }
            
          });
          </script>';
    }
  } // vc_tracker


  public function vc_splitter($atts, $content = null) {
    global $post, $wps_vcs_hs_active;

    #$content = do_shortcode($content);
    #$content = apply_filters('the_content', $content);

    $splitter_containers = false;
    preg_match_all('/\[vc\_splitter\_container(.*?)\](?:(.+?)?\[\/vc\_splitter\_container\])?/ius', $content, $splitter_containers);

    if (!empty($splitter_containers[2])) {

      // Random
      $rand_min = 0;
      $rand_max = count($splitter_containers[2]) - 1;
      $get_random = mt_rand($rand_min, $rand_max);

      // Fetch Attributes
      preg_match('/splitter\_element\_id\=\"(.*?)\"/iuS', $splitter_containers[1][$get_random], $inner_atts);
      preg_match('/splitter\_name\=\"(.*?)\"/iuS', $splitter_containers[1][$get_random], $splitter_name);

      if (empty($splitter_name[1])) {
        $splitter_name[1] = $inner_atts[1];
      }

      if (!empty($inner_atts[1])) {
        // Count the stats
        $this->count_split_stats($inner_atts[1], $splitter_name[1], $atts['splitter_wrapper_id']);
      }

      $wps_vcs_hs_active[] = array('wrapper_ID' => $atts['splitter_wrapper_id'], 'split_ID' => $inner_atts[1], 'split_Name' => $splitter_name[1]);

      return '<div class="wps-vcs-wrapper" data-wrapper-id="' . $atts['splitter_wrapper_id'] . '" data-post-id="' . $post->ID . '" data-split-id="' . $inner_atts[1] . '">' . do_shortcode($splitter_containers[2][$get_random]) . '</div>';

    }

  } // vc_splitter


  public function count_split_stats($split_ID, $splitter_name, $split_Wrapper) {
    global $wpdb, $post;

    $sql = $wpdb->prepare("INSERT INTO vcs_split_stats (post_ID, split_ID, split_Wrapper, split_name, first_occurance, last_occurance, views) VALUES (%d, %s, %s, %s, %s, %s, %d) ON DUPLICATE KEY UPDATE last_occurance=%s, split_name=%s, views=views+1",
                          $post->ID,
                          $split_ID,
                          $split_Wrapper,
                          $splitter_name,
                          current_time('mysql'),
                          current_time('mysql'),
                          '1',
                          current_time('mysql'),
                          $splitter_name);

    $query = $wpdb->query($sql);

  } // count_split_stats


} // wps_vcs_hs_frontend_shortcode