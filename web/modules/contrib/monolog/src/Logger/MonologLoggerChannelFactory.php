<?php

/**
 * @file
 * Contains \Drupal\Core\Logger\MonologLoggingLoggerChannelFactory.
 */

namespace Drupal\monolog\Logger;

use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\monolog\Logger\Logger;
use Drupal\monolog\Entity\MonologProfile;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Defines a factory for logging channels.
 */
class MonologLoggerChannelFactory implements LoggerChannelFactoryInterface, ContainerAwareInterface {
  use ContainerAwareTrait;

  /**
   * Array of all instantiated logger channels keyed by channel name.
   *
   * @var \Drupal\Core\Logger\LoggerChannelInterface[]
   */
  protected $channels = array();

  /**
   * {@inheritdoc}
   */
  public function get($channel) {
    if (!isset($this->channels[$channel])) {
      try {
        $this->channels[$channel] = $this->getChannelInstance($channel);
      }
      catch (\InvalidArgumentException $e) {
        $this->channels[$channel] = new NullLogger();
        if ($this->container->get('current_user')->hasPermission('administer site configuration')) {
          drupal_set_message($e->getMessage(), 'error');
        }
      }
    }

    return $this->channels[$channel];
  }

  /**
   * {@inheritdoc}
   */
  public function addLogger(LoggerInterface $logger, $priority = 0) {
    // No-op, we have handlers which are plugins and configured in the UI.
  }

  /**
   * Factory function for Monolog loggers.
   *
   * @param string $channel_name
   *   The name the logging channel.
   *
   * @return \Psr\Log\LoggerInterface
   *
   * @throws \RuntimeException
   * @throws \InvalidArgumentException
   */
  protected function getChannelInstance($channel_name) {
    if (!class_exists('Monolog\Logger')) {
      throw new \RuntimeException('The Monolog\Logger class was not found. Make sure the Monolog package is installed via Composer.');
    }

    if (!$this->container) {
      // We need the container to read profiles etc.
      return new NullLogger();
    }

    $channel_profiles = $this->container->get('config.factory')->get('monolog.settings')->get('channel_profiles');
    if (!isset($channel_profiles[$channel_name])) {
      $this->container->get('module_handler')->loadInclude('monolog', 'inc', 'monolog.crud');
      $channel_info = monolog_channel_info_load($channel_name);
      $channel_profiles[$channel_name] = $channel_info['default profile'] ? $channel_info['default profile'] : 'development';
    }

    if (!$this->container->get('entity.manager')->getDefinition('monolog_profile', FALSE)) {
      // When installing the entity type is not available yet.
      return new NullLogger();
    }

    $profile = MonologProfile::load($channel_profiles[$channel_name]);
    if (!$profile) {
      throw new \InvalidArgumentException(sprintf('Logging profile not valid: %s', $profile));
    }

    $logger = new Logger($channel_name);
    foreach ($profile->getHandlers()->sort() as $handler) {
      $instance = $handler->getHandlerInstance();
      $logger->pushHandler($instance);
    }

    return $logger;
  }


}
