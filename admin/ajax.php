<?php

/**
 * VC Splitter - Admin Ajax
 */
class wps_vcs_hs_admin_ajax {

  public $slug;
  public $version;


  public function __construct($parent) {

    $this->slug = $parent->slug;
    $this->version = $parent->version;

    add_action('init', array($this, 'add'));
  } // __construct


  public function add() {
    $this->register_ajax('wps_vcs_hs_signup');
    $this->register_ajax('wps_vcs_hs_stat_reset');
    $this->register_ajax('wps_vcs_hs_stat_reset_all');
    $this->register_ajax('wps_vcs_hs_session_editor');
  } // add


  public static function wps_vcs_hs_signup() {
    update_option(WPS_VCS_HS_SU_OPT_NAME, 'true');
  }


  public static function wps_vcs_hs_session_editor() {
    $list = '';

    $preset_value = sanitize_text_field($_POST['preset_value']);
    $content = sanitize_text_field($_POST['content']);
    $content = strip_tags($content);

    preg_match_all('/\[vc\_splitter(.*?)\](?:(.+?)?\[\/vc\_splitter\])?/iuS', $content, $split_containers);

    if (!empty($split_containers[1])) {
      foreach ($split_containers[1] as $key => $test) {
        $test = trim($test);
        $test = stripslashes($test);
        //splitter_wrapper_id="1488825347623-04e900c9-fea3"
        preg_match('/splitter\_wrapper\_id\=\"(.*?)\"/iuS', $test, $inner_atts);
        preg_match('/splitter\_container\_name\=\"(.*?)\"/iuS', $test, $splitter_name);

        if ($inner_atts[1] == $preset_value) {
          $list .= '<option value="' . $inner_atts[1] . '" selected="selected">' . $splitter_name[1] . '</option>';
        } else {
          $list .= '<option value="' . $inner_atts[1] . '">' . $splitter_name[1] . '</option>';
        }
      }
    }

    wp_send_json_success($list);

  } // wps_vcs_session_editor


  public static function wps_vcs_hs_stat_reset() {
    global $wpdb;

    $stat_ID = sanitize_text_field($_POST['stat_ID']);

    if (!empty($_POST)) {
      $sql = $wpdb->prepare("DELETE FROM vcs_split_stats WHERE ID=%s", $stat_ID);
      $query = $wpdb->query($sql);
      wp_send_json_success();
    }

    wp_send_json_error();

  } // wps_vcs_stat_reset

  public static function wps_vcs_hs_stat_reset_all() {
    global $wpdb;

    $split_wrapper = sanitize_text_field($_POST['split_wrapper']);

    if (!empty($_POST)) {
      $sql = $wpdb->prepare("DELETE FROM vcs_split_stats WHERE split_Wrapper=%s", $split_wrapper);
      $query = $wpdb->query($sql);
      wp_send_json_success();
    }

    wp_send_json_error();

  } // wps_vcs_hs_stat_reset_all


  public function register_ajax($hook) {
    add_action('wp_ajax_' . $hook, array($this, $hook));
    add_action('wp_ajax_nopriv_' . $hook, array($this, $hook));
  } // register_ajax

}