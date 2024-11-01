function is_link(item) {
    if (jQuery(item).is('a') &&
        (!jQuery(item).hasClass('vc_btn') &&
            !jQuery(item).hasClass('vc_btn1') &&
            !jQuery(item).hasClass('vc_btn2') &&
            !jQuery(item).hasClass('vc_btn3') &&
            !jQuery(item).hasClass('vc_btn4') &&
            !jQuery(item).hasClass('vc_btn5') &&
            !jQuery(item).hasClass('vc_btn6') &&
            !jQuery(item).hasClass('vc_btn7') &&
            !jQuery(item).hasClass('vc_btn8') &&
            !jQuery(item).hasClass('vc_btn9') &&
            !jQuery(item).hasClass('vc_btn10'))) {
        return true;
    } else {
        return false;
    }
} // is_link


function is_button(item) {
    if ((jQuery(item).is('button') || jQuery(item).is('input[type="button"]')) ||
        (jQuery(item).hasClass('vc_btn') ||
        jQuery(item).hasClass('vc_btn1') ||
        jQuery(item).hasClass('vc_btn2') ||
        jQuery(item).hasClass('vc_btn3') ||
        jQuery(item).hasClass('vc_btn4') ||
        jQuery(item).hasClass('vc_btn5') ||
        jQuery(item).hasClass('vc_btn6') ||
        jQuery(item).hasClass('vc_btn7') ||
        jQuery(item).hasClass('vc_btn8') ||
        jQuery(item).hasClass('vc_btn9') ||
        jQuery(item).hasClass('vc_btn10'))
    ) {
        return true;
    } else {
        return false;
    }
} // is_button


function is_split_trigger(item) {
    if (jQuery(item).hasClass('wps-vcs-split-trigger')) {
        return true;
    } else {
        return false;
    }
} // is_split_trigger