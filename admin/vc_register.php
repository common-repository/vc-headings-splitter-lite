<?php

/**
 * VC Splitter - Shortcode
 */

vc_add_shortcode_param('split_test', 'wps_vcs_hs_split_test_list');

function wps_vcs_hs_split_test_list($settings, $value) {
  $post = get_post($_POST['post_id']);
  $output = 'No split tests found in this post!';

  $split_containers = false;
  preg_match_all('/\[vc\_splitter(.*?)\](?:(.+?)?\[\/vc\_splitter\])?/iuS', $post->post_content, $split_containers);

  if (!empty($split_containers[1])) {
    $output = '';

    $output .= '<script type="text/javascript">';
    $output .= 'jQuery(document).ready(function($){
                  var content = tinymce.activeEditor.getContent();
                  if (content == "") {
                    var content = tinymce.get("content").getContent();
                  }
                  $.post(ajaxurl, {action:"wps_vcs_hs_session_editor",content:content, preset_value:"' . $value . '"},function(response){
                    $("select[name=\'split_test_id\']").html(response.data);
                  });
                });';
    $output .= '</script>';

    $output .= '<select name="' . esc_attr($settings['param_name']) . '" class="wpb_vc_param_value wpb-textinput ' . esc_attr($settings['param_name']) . ' ' . esc_attr($settings['type']) . '_field">';
    $output .= '</select>';

  }

  return $output;
} // split_test_list
$awc = get_option(strrev('cwa_scv_psw'));
vc_add_shortcode_param('show_stats', 'wps_vcs_split_stats');
vc_add_shortcode_param('read_only', 'wps_vcs_splitter_name_upgrade');

if (!function_exists('wps_vcs_splitter_name_upgrade')) {
  function wps_vcs_splitter_name_upgrade($settings, $value) {
    $output = '<input type="text" readonly="true" value="Upgrade to unlock this feature!"/>';
    return $output;
  }
}

if (!function_exists('wps_vcs_split_stats')) {
  function wps_vcs_split_stats($settings, $value) {
    global $wpdb, $post;

    $split_Wrapper = $_POST['params']['splitter_wrapper_id'];

    if (!empty($_POST['post_id'])) {
      $post_ID = $_POST['post_id'];
    } else {
      global $post;
      $post_ID = $post->ID;
    }

    $output = '<div class="wps_vcs_split_stats">';
    $output .= '<table class="wp-list-table widefat fixed striped" id="wps-grader-table">';

    $output .= '<thead>';
    $output .= '<tr>';
    $output .= '<th>Variation Label</th>';
    $output .= '<th style="text-align: center;">Views</th>';
    $output .= '<th style="text-align: center;">Clicks</th>';
    $output .= '<th style="text-align: center;">Bounces</th>';
    $output .= '<th style="text-align: center;">CTR</th>';
    $output .= '<th style="text-align: center;">Bounces</th>';
    $output .= '<th style="text-align: center;">Action</th>';
    $output .= '</tr>';
    $output .= '</thead>';

    $output .= '<tbody>';

    if (function_exists('lite_addon_stats')) {
      $output .= lite_addon_stats($post_ID, $split_Wrapper);
      $output .= '</tbody>';
      $output .= '</table>';
      $output .= '<br/><a href="#" class="wps-vcs-hs-reset-stats-all button button-primary" data-split-wrapper="' . $split_Wrapper . '" style="float:right;">Reset All Stats</a>';
    } else {
      $output .= '<tr>';
      $output .= '<td colspan="7" style="text-align: center;">Upgrade to Unlock Detailed Statistics<br/><br/><a href="https://vcsplitter.com/product/pro/" class="button button-primary" target="_blank">Upgrade to Pro</a> <a href="https://vcsplitter.com/product/stats/" class="button button-primary btn-upgrade-margin-left" target="_blank">Only Unlock Stats</a> </td>';
      $output .= '</tr>';
      $output .= '</tbody>';
      $output .= '</table>';
    }

    return $output;
  } // vc_split_stats
}

add_filter('vc_single_param_edit', 'wps_vcs_hs_filter_Test', 0, 2);
function wps_vcs_hs_filter_Test($param, $value) {

  if (in_array('wpb_el_type_el_id', $param['vc_single_param_edit_holder_class'])) {
    $param['vc_single_param_edit_holder_class'][] = 'wps-vcs-hide-me';
  }

  return $param;
}

// Register VC Templates Dir
add_action('vc_before_init', 'wps_vcs_hs_vc_before_init_actions');

function wps_vcs_hs_vc_before_init_actions() {
  // Link your VC elements's folder
  if (function_exists('vc_set_shortcodes_templates_dir')) {
    vc_set_shortcodes_templates_dir(WPS_VCS_HS_DIR . 'vc-elements');
  }
} // vc_before_init_actions

if (!class_exists('WPBakeryShortCode_Vc_Splitter')) {
  vc_map(array(
           "name" => __("VC Splitter", WPS_VCS_HS_TEXTDOMAIN),
           "description" => "Split test elements against each other",
           "base" => "vc_splitter",
           "as_parent" => array('only' => 'vc_splitter_container'), // Use only|except attributes to limit child shortcodes (separate multiple values with comma)
           "show_settings_on_create" => false,
           "params" => array(
             // add params same as with any other content element
             array(
               "type" => "el_id",
               "heading" => __("Splitter Wrapper ID", WPS_VCS_HS_TEXTDOMAIN),
               "param_name" => "splitter_wrapper_id",
               'settings' => array(
                 'auto_generate' => true,
               ),
               "description" => __("Enables you to target the test ID for better results overview.", WPS_VCS_HS_TEXTDOMAIN),
             ),
             array(
               "type" => "read_only",
               "heading" => __("Split Test Name", WPS_VCS_HS_TEXTDOMAIN),
               "param_name" => "splitter_container_name",
               "description" => __("<a href='https://vcsplitter.com/product/pro/'>Upgrade to Pro.</a>", WPS_VCS_HS_TEXTDOMAIN),
             ),
             array(
               "type" => "show_stats",
               "heading" => __("Stats", WPS_VCS_HS_TEXTDOMAIN),
               "param_name" => "show_stats",
               "description" => __("Statistics and labels will update on the next view.", WPS_VCS_HS_TEXTDOMAIN),
             )
           ),
           "js_view" => 'VcColumnView'
         ));


  class WPBakeryShortCode_Vc_Splitter extends WPBakeryShortCodesContainer {

  } // wps_vcs_admin_shortcode
}

if (!class_exists('WPBakeryShortCode_Vc_Splitter_Container')) {
  vc_map(array(
           "name" => __("Split Test Variation", WPS_VCS_HS_TEXTDOMAIN),
           "base" => "vc_splitter_container",
           "as_child" => array('only' => 'vc_splitter'), // Use only|except attributes to limit child shortcodes (separate multiple values with comma)
           "as_parent" => array('only' => $awc),
           "content_element" => true,
           "show_settings_on_create" => false,
           "is_container" => true,
           "params" => array(
             // add params same as with any other content element
             array(
               "type" => "el_id",
               "heading" => __("Splitter ID", WPS_VCS_HS_TEXTDOMAIN),
               "param_name" => "splitter_element_id",
               'settings' => array(
                 'auto_generate' => true,
               ),
               "description" => __("Enables you to target the test ID for better results overview.", WPS_VCS_HS_TEXTDOMAIN),
             ),
             array(
               "type" => "read_only",
               "heading" => __("Variation Name", WPS_VCS_HS_TEXTDOMAIN),
               "param_name" => "read_only",
               "description" => __("<a href='https://vcsplitter.com/product/pro/'>Upgrade to Pro.</a>", WPS_VCS_HS_TEXTDOMAIN),
             )
           ),
           "js_view" => 'VcColumnView'
         ));


  class WPBakeryShortCode_Vc_Splitter_Container extends WPBakeryShortCodesContainer {

  } // wps_vcs_admin_shortcode
}
