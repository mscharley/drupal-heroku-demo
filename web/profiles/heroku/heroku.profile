<?php
/**
 * Profile code for the Heroku profile.
 *
 * This provides integrations with the Heroku environment.
 */

$settings['reverse_proxy'] = TRUE;
$settings['reverse_proxy_address'] = [];

// For the free tier at least, Heroku doesn't appear to have static load balancers for each instance.
if (strpos('10.', $_SERVER['REMOTE_ADDR']) === 0) {
  $settings['revers_proxy_address'][] = $_SERVER['REMOTE_ADDR'];
}
