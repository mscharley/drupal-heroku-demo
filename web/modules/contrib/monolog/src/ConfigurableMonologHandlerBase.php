<?php

/**
 * @file
 * Contains \Drupal\monolog\ConfigurableMonologHandlerBase.
 */
namespace Drupal\monolog;

use Drupal\Core\Form\FormStateInterface;
use Drupal\monolog\Logger\MonologLogLevel;

/**
 * Provides a base class for configurable monolog handlers.
 *
 * @see \Drupal\monolog\Annotation\MonologHandler
 * @see \Drupal\monolog\MonologHandlerInterface
 * @see \Drupal\monolog\ConfigurableMonologHandlerInterface
 * @see \Drupal\monolog\MonologHandlerManager
 * @see plugin_api
 */
abstract class ConfigurableMonologHandlerBase extends MonologHandlerBase {

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['label'] = array(
      '#title' => $this->t('Name'),
      '#type' => 'textfield',
      '#default_value' => $this->label(),
      '#description' => $this->t('The human-readable name of the handler.'),
      '#required' => TRUE,
      '#maxlength' => 255,
      '#size' => 30,
    );

    $form['level'] = array(
      '#title' => $this->t('Logging level'),
      '#type' => 'select',
      '#default_value' => $this->configuration['level'],
      '#options' => MonologLogLevel::getLevels(),
      '#description' => $this->t('The minimum severity level of logged messages.'),
    );

    $form['bubble'] = array(
      '#title' => $this->t('Allow messages to bubble up to the next handler in the stack.'),
      '#type' => 'checkbox',
      '#default_value' => $this->configuration['bubble'],
      '#description' => $this->t('If unckecked, messages processed by this handler will be blocked from being processed by the subsequent handlers in the stack.'),
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
    $this->configuration['label'] = $form_state->getValue('label');
    $this->configuration['level'] = $form_state->getValue('level');
    $this->configuration['bubble'] = $form_state->getValue('bubble');
  }

}
