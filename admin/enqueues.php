<?php


/**
 * VC Splitter - Enqueues
 */

class wps_vcs_hs_admin_enqueues {

  public $slug;
  public $version;

  public function __construct($parent) {

    $this->slug = $parent->slug;
    $this->version = $parent->version;

    add_action('admin_enqueue_scripts', array($this, 'add'));
  } // __construct


  public function add() {
    $this->enqueue_style('admin-style', 'admin.css');
    $this->enqueue_script('admin-js', 'admin.js');
  } // add


  public function enqueue_script($file_desc, $file) {
    wp_enqueue_script($this->slug . '_' . $file_desc, WPS_VCS_HS_URI . 'admin/'. $file, array(), $this->version, true);
  } // enqueue_script


  public function enqueue_style($file_desc, $file) {
    wp_enqueue_style($this->slug . '_' . $file_desc, WPS_VCS_HS_URI . 'admin/'. $file, array(), $this->version);
  } // enqueue_style


} // wps_vcs_hs_admin_enqueues