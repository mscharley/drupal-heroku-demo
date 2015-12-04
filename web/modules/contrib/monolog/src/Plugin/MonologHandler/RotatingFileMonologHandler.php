<?php

/**
 * @file
 * Handler include for StreamHandler.
 */

namespace Drupal\monolog\Plugin\MonologHandler;

use Drupal\Core\Form\FormStateInterface;
use Drupal\monolog\ConfigurableMonologHandlerInterface;
use Drupal\monolog\ConfigurableMonologHandlerBase;
use Monolog\Handler\RotatingFileHandler;

/**
 * Logs records to a file and creates one logfile per day. It will also delete files older than the "Max Files" settings.
 *
 * @MonologHandler(
 *   id = "rotating_file",
 *   label = @Translation("Rotating File Handler"),
 *   description = @Translation("Logs records to a file and creates one logfile per day. It will also delete files older than the 'Max Files' settings."),
 *   group = @Translation("Files and syslog"),
 * )
 */
class RotatingFileMonologHandler extends ConfigurableMonologHandlerBase implements ConfigurableMonologHandlerInterface {

  /**
   * {@inheritdoc}
   */
  public function getHandlerInstance() {
    return new RotatingFileHandler($this->configuration['filepath'], $this->configuration['max_files'], $this->configuration['level'], $this->configuration['bubble']);
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);
    $form['filepath'] = [
      '#title' => $this->t('Log file path'),
      '#type' => 'textfield',
      '#default_value' => $this->configuration['filepath'],
      '#description' => $this->t('The path or URI that the log file will be written to.'),
    ];

    $form['max_files'] = [
      '#title' => $this->t('Maximum number of files'),
      '#type' => 'number',
      '#default_value' => $this->configuration['max_files'],
      '#description' => $this->t('The maximal amount of files to keep (0 means unlimited).'),
      '#size' => 4,
    ];

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
    $this->configuration['max_files'] = $form_state->getValue('max_files');
    $directory = dirname($this->configuration['filepath']);
    monolog_prepare_log_dir($directory);
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return parent::defaultConfiguration() + [
      'filepath' => 'public://monolog/drupal.log',
      'max_files' => 0,
    ];
  }

}
