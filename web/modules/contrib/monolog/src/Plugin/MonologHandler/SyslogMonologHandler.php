<?php

/**
 * @file
 * Handler include for SyslogHandler.
 */

namespace Drupal\monolog\Plugin\MonologHandler;

use Drupal\Core\Form\FormStateInterface;
use Drupal\monolog\ConfigurableMonologHandlerInterface;
use Drupal\monolog\ConfigurableMonologHandlerBase;
use Monolog\Handler\SyslogHandler;

/**
 * Logs records to the syslog.
 *
 * @MonologHandler(
 *   id = "syslog",
 *   label = @Translation("Syslog Handler"),
 *   description = @Translation("Logs records to the syslog."),
 *   group = @Translation("Files and syslog"),
 * )
 */
class SyslogMonologHandler extends ConfigurableMonologHandlerBase implements ConfigurableMonologHandlerInterface {

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);
    $form['ident'] = [
      '#title' => $this->t('Identity string'),
      '#type' => 'textfield',
      '#default_value' => $this->configuration['ident'],
      '#description' => $this->t('The string ident is added to each message.'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);
    $this->configuration['ident'] = $form_state->getValue('ident');
  }

  /**
   * {@inheritdoc}
   */
  public function getHandlerInstance() {
    return new SyslogHandler($this->configuration['ident'], LOG_USER, $this->configuration['level'], $this->configuration['bubble']);
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return parent::defaultConfiguration() + [
      'ident' => 'drupal',
    ];
  }

}