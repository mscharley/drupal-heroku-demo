<?php

/**
 * @file
 * Hooks provided by the Composer Manager module.
 */

use Monolog\Handler\StreamHandler;
use Monolog\Handler\HandlerInterface;

/**
 * @addtogroup hooks
 * @{
 */

/**
 * Defines monolog channels.
 *
 * A channel identifies which part of the application a record is related to.
 *
 * @return array
 *   An associative array keyed by unique name of the channel. Each channel is
 *   an associative array containing:
 *   - label: The human readable name of the channel as displayed in
 *     administrative pages.
 *   - description: The description of the channel displayed in administrative
 *     pages.
 *   - default profile: The machine readable name of the channel's default
 *     logging profile.
 */
function hook_monolog_channel_info() {
  $channels = array();

  $channels['watchdog'] = array(
    'label' => t('Watchdog'),
    'description' => t('The default channel that watchdog messages are routed through.'),
    'default profile' => 'production',
  );

  return $channels;
}


/**
 * @} End of "addtogroup hooks".
 */
