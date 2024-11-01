<?php


/**
 * VC Splitter - Ajax
 */
class wps_vcs_hs_frontend_ajax {

  public $slug;
  public $version;


  public function __construct($parent) {

    $this->slug = $parent->slug;
    $this->version = $parent->version;

    add_action('init', array($this, 'add'));
    add_action('wp_print_footer_scripts', array($this, 'print_ajaxurl'));
  } // __construct


  public static function wps_vcs_hs_track_bounce() {
    global $wpdb;

    $split_tests = $_POST['splits'];

    if (is_array($split_tests)) {
      foreach ($split_tests as $split_test) {
        $sql = $wpdb->prepare("UPDATE vcs_split_stats SET bounce=bounce+1 WHERE split_ID=%s AND split_Wrapper=%s AND post_ID=%s",
                              $split_test['split_ID'],
                              $split_test['wrapper_ID'],
                              $_POST['post']);

        $query = $wpdb->query($sql);
        wp_send_json_success();
      }
    }

    wp_send_json_error();
  } // wps_vcs_hs_track_bounce


  public static function wps_vcs_hs_track_split() {
    global $wpdb;

    $split_tests = $_POST['splits'];
    $split_test_ID = sanitize_text_field($_POST['split_test_ID']);

    if (is_array($split_tests)) {
      foreach ($split_tests as $split_test) {
        $sql = $wpdb->prepare("UPDATE vcs_split_stats SET clicks=clicks+1 WHERE split_ID=%s AND split_Wrapper=%s AND post_ID=%s",
                              $split_test['split_ID'],
                              $split_test_ID,
                              $_POST['post']);

        $query = $wpdb->query($sql);
        wp_send_json_success();
      }
    }

    wp_send_json_error();
  } // wps_vcs_hs_track_split


  public static function wps_vcs_hs_track_link() {
    global $wpdb;

    $post = $_POST['post'];
    $link = $_POST['link'];
    $button = $_POST['button'];

    if (!empty($post) && ($link || $button)) {

      $update_query = 'link=link+1';

      if ($link == "true") {
        $link = '1';
        $update_query = 'link=link+1';
      } elseif ($button == "true") {
        $button = '1';
        $update_query = 'button=button+1';
      }

      $sql = $wpdb->prepare("INSERT INTO vcs_split_links (link, button, post_ID) VALUES (%d, %d, %d) ON DUPLICATE KEY UPDATE " . $update_query, $link, $button, $post);
      $query = $wpdb->query($sql);
      wp_send_json_success();
    }

    wp_send_json_error();
  } // wps_track_link


  public static function wps_vcs_hs_click() {

    if (!empty($_POST)) {
      global $wpdb;

      $split_ID = sanitize_text_field($_POST['split_ID']);
      $wrapper_ID = sanitize_text_field($_POST['wrapper_ID']);
      $post_ID = sanitize_text_field($_POST['post_ID']);

      $sql = $wpdb->prepare("UPDATE vcs_split_stats SET clicks=clicks+1 WHERE split_ID=%s AND split_Wrapper=%s AND post_ID=%s",
                            $split_ID,
                            $wrapper_ID,
                            $post_ID);

      $query = $wpdb->query($sql);
      if ($query) {
        wp_send_json_success();
      } else {
        wp_send_json_error();
      }

    }
  } // wps_vcs_click


  public function print_ajaxurl() {
    echo '<script type="text/javascript">';
    echo 'var ajaxurl="' . admin_url('admin-ajax.php') . '";';
    echo '</script>';
  } // print_ajaxurl


  public function add() {
    $this->register_ajax('wps_vcs_hs_click');
    $this->register_ajax('wps_vcs_hs_track_split');
    $this->register_ajax('wps_vcs_hs_track_link');
    $this->register_ajax('wps_vcs_hs_track_bounce');
  } // wps_track_link


  public function register_ajax($hook) {
    add_action('wp_ajax_' . $hook, array($this, $hook));
    add_action('wp_ajax_nopriv_' . $hook, array($this, $hook));
  } // register_ajax


} // wps_vcs_hs_frontend_ajax