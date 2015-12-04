<?php

/**
 * Monolog extension for use with Drupal.
 */

namespace Drupal\monolog\Logger;

use Drupal\Component\Utility\SafeMarkup;
use Drupal\Core\Logger\RfcLogLevel;
use Monolog\Logger as BaseLogger;

/**
 * Logger class for the Drupal Monolog module.
 *
 * Allows the channel to be modified after the class is instantiated. This is
 * normally not a good idea, but it is necessary to reconcile the differences in
 * the Monolog library and how the watchdog type relates to the logging
 * facility.
 */
class Logger extends BaseLogger {

  /**
   * An array of enabled context keys.
   *
   * @var array
   */
  protected $enabledContexts = [];

  /**
   * Map of RFC 5424 log constants to Monolog log constants.
   *
   * @var array
   */
  protected $levelTranslation = array(
    RfcLogLevel::EMERGENCY => MonologLogLevel::EMERGENCY,
    RfcLogLevel::ALERT => MonologLogLevel::ALERT,
    RfcLogLevel::CRITICAL => MonologLogLevel::CRITICAL,
    RfcLogLevel::ERROR => MonologLogLevel::ERROR,
    RfcLogLevel::WARNING => MonologLogLevel::WARNING,
    RfcLogLevel::NOTICE => MonologLogLevel::NOTICE,
    RfcLogLevel::INFO => MonologLogLevel::INFO,
    RfcLogLevel::DEBUG => MonologLogLevel::DEBUG,
  );

  public function addRecord($level, $message, array $context = array()) {
    if (array_key_exists($level, $this->levelTranslation)) {
      $level = $this->levelTranslation[$level];
    }

    // Replace Drupal style placeholders.
    $message = strip_tags(SafeMarkup::format($message, $context));

    $enabled_contexts = $this->getEnabledContexts();
    $context = array_intersect_key($context, $enabled_contexts);
    if (isset($enabled_contexts['request_id'])) {
      $context['request_id'] = monolog_request_id();
    }
    if (empty($context)) {
      $context = [];
    }

    parent::addRecord($level, $message, $context);
  }

  protected function getEnabledContexts() {
    if (!$this->enabledContexts && \Drupal::hasService('config.factory')) {
      $this->enabledContexts = array_filter(\Drupal::config('monolog.settings')->get('logging_contexts'));
    }

    return $this->enabledContexts;
  }

}
