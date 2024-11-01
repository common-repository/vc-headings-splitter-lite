<?php


/**
 * VC Splitter - Menu
 */

class wps_vcs_hs_admin_menu {

  public $slug;
  public $version;
  public $stats;

  public function __construct($parent) {
    $this->slug = $parent->slug;
    $this->version = $parent->version;
    $this->stats = $parent->stats;

    add_action('admin_menu', array($this, 'register_menu'));
    #add_action('enqueu_basic', array($this, 'admin_enqueue_basic'));

  } // __construct


  public function register_menu() {
    global $awc;
    $awc = get_option(strrev('cwa_scv_psw'));
    add_menu_page(WPS_VCS_HS_MENU_NAME, WPS_VCS_HS_MENU_NAME, 'manage_options', $this->slug, array($this, 'list_split_tests'));
  } // register_menu


  public function list_split_tests() {
    global $wpdb;

    $slug = $this->slug;
    $status = get_option(WPS_VCS_HS_OPT_NAME);
    $pc = get_option(WPS_VCS_HS_PC_NAME);

    if (!empty($_POST['wps-vcs-bs'])) {

      if (!empty($_POST['wps-vcs-bs']['purchase-code'])) {
        $pc = sanitize_text_field($_POST['wps-vcs-bs']['purchase-code']);
        update_option(WPS_VCS_HS_PC_NAME, $pc);
        do_action('enqueu_basic');
      }

      $status = get_option(WPS_VCS_HS_OPT_NAME);
      $pc = get_option(WPS_VCS_HS_PC_NAME);
    }

    echo '<div class="wrap">';
    echo '<h2>VC Headings Splitter</h2>';
    echo '<hr/>';

    if (!empty($_POST) && $status == 'false') {
      echo '<div class="wps-vc-error">We were not able to verify your purchase!</div>';
    }

    #echo '<div class="wps-vcs-settings wps-vcs-menu-page-left-side">';
    #include 'drip-form.php';
    #echo '</div>';

    echo '<div class="wps-vcs-admin-container">';

    echo '<div class="wps-vcs-menu-page-left-side">';

    echo '<div class="wps-vcs-container-inner">';
    include 'drip-form.php';
    echo '</div>';

    echo '<h2>Other Free Plugins</h2>';
    echo '<ul class="other-free-plugins">';
    echo '<li><a href="#"><img src="' . WPS_VCS_HS_URI . 'admin/images/vc-button-splitter.png"/></a></li>';
    echo '<li><a href="#"><img src="' . WPS_VCS_HS_URI . 'admin/images/vc-heading-splitter.png"/></a></li>';
    echo '<li><a href="#"><img src="' . WPS_VCS_HS_URI . 'admin/images/vc-image-splitter.png"/></a></li>';
    echo '</ul>';

    echo '</div>';
    // Left Side

    echo '<div class="wps-vcs-menu-page-right-side">';

    echo '<div class="wps-vcs-ad-container">';
    echo '<img src="' . WPS_VCS_HS_URI . 'admin/images/splitter-pro-ad.png"/>';
    echo '</div>';

    echo '<div class="wps-vcs-ad-text">';
    echo '<h2>The Most Advanced Split Testing Tool for Visual Composer</h2>';
    echo '<a href="https://vcsplitter.com/product/pro/" class="button-vc">UPGRADE NOW</a>';
    echo '</div>';

    echo '</div>';
    // Right Side

    echo '</div>';
    // Admin Container


    echo '</div>';
  } // list_sites


} // wps_vcs_bs_admin_menu