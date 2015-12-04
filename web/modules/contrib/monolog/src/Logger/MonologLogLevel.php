<?php

/**
 * @file
 * Contains \Drupal\monolog\Logger\MonologLogLevel.
 */

namespace Drupal\monolog\Logger;

use Drupal\Core\StringTranslation\TranslationWrapper;
use Monolog\Logger;

/**
 * @defgroup logging_severity_levels Logging severity levels
 * @{
 * Logging severity levels as defined in Monolog\Logger.
 *
 * @} End of "defgroup logging_severity_levels".
 */
class MonologLogLevel {

  /**
   * Log message severity -- Emergency: system is unusable.
   */
  const EMERGENCY = Logger::EMERGENCY;

  /**
   * Log message severity -- Alert: action must be taken immediately.
   */
  const ALERT = Logger::ALERT;

  /**
   * Log message severity -- Critical conditions.
   */
  const CRITICAL = Logger::CRITICAL;

  /**
   * Log message severity -- Error conditions.
   */
  const ERROR = Logger::ERROR;

  /**
   * Log message severity -- Warning conditions.
   */
  const WARNING = Logger::WARNING;

  /**
   * Log message severity -- Normal but significant conditions.
   */
  const NOTICE = Logger::NOTICE;

  /**
   * Log message severity -- Informational messages.
   */
  const INFO = Logger::INFO;

  /**
   * Log message severity -- Debug-level messages.
   */
  const DEBUG = Logger::DEBUG;

  /**
   * An array with the severity levels as keys and labels as values.
   *
   * @var array
   */
  protected static $levels;

  /**
   * Returns a list of severity levels, as defined Monolog\Logger.
   *
   * @return array
   *   Array of the possible severity levels for log messages.
   *
   * @ingroup logging_severity_levels
   */
  public static function getLevels() {
    if (!static::$levels) {
      static::$levels = [
        static::EMERGENCY => new TranslationWrapper('Emergency'),
        static::ALERT => new TranslationWrapper('Alert'),
        static::CRITICAL => new TranslationWrapper('Critical'),
        static::ERROR => new TranslationWrapper('Error'),
        static::WARNING => new TranslationWrapper('Warning'),
        static::NOTICE => new TranslationWrapper('Notice'),
        static::INFO => new TranslationWrapper('Info'),
        static::DEBUG => new TranslationWrapper('Debug'),
      ];
    }

    return static::$levels;
  }

}