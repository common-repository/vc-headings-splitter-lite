<?php
$signup = get_option(WPS_VCS_HS_SU_OPT_NAME);
if (empty($signup)) {
  echo '<img src="' . WPS_VCS_HS_URI . 'admin/images/vclogo.png" alt="VC Splitter" class="wps-vcs-logo" />';
  ?>
  <form action="https://www.getdrip.com/forms/74705930/submissions" class="wps-vcs-hs-signup-form" method="post" data-drip-embedded-form="74705930" target="_blank">
    <h2 class="slim-bold">Enter your email to activate, stay updated and get priority support.</h2>
    <div data-drip-attribute="description"></div>
    <div>
      <input type="email" name="fields[email]" value="" placeholder="E-Mail Address"/>
      <input type="submit" name="submit" class="button button-primary" value="Sign Up" data-drip-attribute="sign-up-button"/>
    </div>
    <h5 style="text-align: center;">You can unsubscribe at any time, we respect your privacy.</h5>
  </form>
<?php } else {
  echo '<img src="' . WPS_VCS_HS_URI . 'admin/images/vclogo.png" alt="VC Splitter" class="wps-vcs-logo" />';
  echo '<h2 class="slim-bold">You have successfully activated ' . WPS_VCS_HS_NAME . '</h2>';
  echo '<a href="https://vcsplitter.com/product/pro/" class="button-vc">Upgrade to pro</a>';
} ?>