<?php

/**
 * @file
 * Contains \Drupal\monolog\Form\SettingsForm.
 */

namespace Drupal\monolog\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfigFormBase;

/**
 * Configures monolog logging settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'monolog_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('monolog.settings');

    $form['logging_contexts'] = array(
      '#type' => 'checkboxes',
      '#title' => $this->t('Include contexts in record'),
      '#description' => $this->t('Include the selected contexts in all log messages that are routed through Monolog from <code>watchdog()</code>.'),
      '#options' => array(
        'type' => $this->t('The type of message for this entry.'),
        'uid' => $this->t('The user ID for the user who was logged in when the event happened.'),
        'request_uri' => $this->t('The request URI for the page the event happened in.'),
        'referer' => $this->t('The page that referred the user to the page where the event occurred.'),
        'ip' => $this->t('The IP address where the request for the page came from.'),
        'link' => $this->t('An optional link provided by the module that called the watchdog() function.'),
        'request_id' => $this->t('A unique identifier for the page request or PHP process to logically group log messages.'),
      ),
      '#default_value' => $config->get('logging_contexts'),
    );

    $form['drupal_compatibility'] = array(
      '#type' => 'item',
      '#title' => $this->t('Drupal compatibility'),
    );

    $form['type_as_channel'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Use the watchdog type as the channel name'),
      '#description' => $this->t('Enable this option to use the watchdog type as each record\'s channel name instead of "watchdog". This allows handlers such as the GELF handler to behave as the current Drupal watchdog implementations do.'),
      '#default_value' => $config->get('type_as_channel'),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('monolog.settings')
      ->set('logging_contexts', $form_state->getValue('logging_contexts'))
      ->set('type_as_channel', $form_state->getValue('type_as_channel'))
      ->save();
    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['monolog.settings'];
  }

}
