<?php

/**
 * @file
 * Handler include for StreamHandler include.
 */

namespace Drupal\monolog\Plugin\MonologHandler;

use Drupal\Core\Form\FormStateInterface;
use Drupal\monolog\ConfigurableMonologHandlerInterface;
use Drupal\monolog\ConfigurableMonologHandlerBase;
use Monolog\Handler\StreamHandler;

/**
 * 'Logs records into any PHP stream, use this for log files.
 *
 * @MonologHandler(
 *   id = "stream",
 *   label = @Translation("Stream Handler"),
 *   description = @Translation("'Logs records into any PHP stream, use this for log files."),
 *   group = @Translation("Files and syslog"),
 * )
 */
class StreamMonologHandler extends ConfigurableMonologHandlerBase implements ConfigurableMonologHandlerInterface {

  /**
   * {@inheritdoc}
   */
  public function getHandlerInstance() {
    return new StreamHandler($this->configuration['filepath'], $this->configuration['level'], $this->configuration['bubble']);
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);
    $form['filepath'] = array(
      '#title' => $this->t('Log file path'),
      '#type' => 'textfield',
      '#default_value' => $this->configuration['filepath'],
      '#description' => $this->t('The path or URI that the log file will be written to.'),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);
    $this->configuration['filepath'] = $form_state->getValue('filepath');
    $directory = dirname($this->configuration['filepath']);
    monolog_prepare_log_dir($directory);
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return parent::defaultConfiguration() + [
      'filepath' => 'public://monolog/drupal.log',
    ];
  }

}
