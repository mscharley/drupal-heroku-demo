<?php

/**
 * @file
 * Contains \Drupal\monolog\Form\MonologHandlerFormBase.
 */

namespace Drupal\monolog\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormState;
use Drupal\Core\Form\FormStateInterface;
use Drupal\monolog\ConfigurableMonologHandlerInterface;
use Drupal\monolog\MonologProfileInterface;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Component\Utility\SafeMarkup;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Provides a base form for monolog handlers.
 */
abstract class MonologHandlerFormBase extends FormBase {

  /**
   * The monolog handler.
   *
   * @var \Drupal\monolog\MonologHandlerInterface
   */
  protected $handler;

  /**
   * The monolog profile.
   *
   * @var \Drupal\monolog\MonologProfileInterface
   */
  protected $profile;

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'monolog_handler_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, MonologProfileInterface $monolog_profile = NULL, $monolog_handler = NULL) {
    $this->profile = $monolog_profile;
    try {
      $this->handler = $this->prepareHandler($monolog_handler);
    }
    catch (PluginNotFoundException $e) {
      throw new NotFoundHttpException(SafeMarkup::format("Invalid handler id: '@id'.", array('@id' => $monolog_handler)));
    }
    $request = $this->getRequest();

    if (!($this->handler instanceof ConfigurableMonologHandlerInterface)) {
      throw new NotFoundHttpException();
    }

    $form['uuid'] = array(
      '#type' => 'value',
      '#value' => $this->handler->getUuid(),
    );
    $form['id'] = array(
      '#type' => 'value',
      '#value' => $this->handler->getPluginId(),
    );

    $form['data'] = $this->handler->buildConfigurationForm(array(), $form_state);
    $form['data']['#tree'] = TRUE;

    // Check the URL for a weight, then the handler, otherwise use default.
    $form['weight'] = array(
      '#type' => 'hidden',
      '#value' => $request->query->has('weight') ? (int) $request->query->get('weight') : $this->handler->getWeight(),
    );

    $form['actions'] = array('#type' => 'actions');
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#button_type' => 'primary',
    );
    $form['actions']['cancel'] = array(
      '#type' => 'link',
      '#title' => $this->t('Cancel'),
      '#url' => $this->profile->urlInfo('edit-form'),
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // The handler's configuration is stored in the 'data' key in the form,
    // pass that through for validation.
    $handler_data = (new FormState())->setValues($form_state->getValue('data'));
    $this->handler->validateConfigurationForm($form, $handler_data);
    // Update the original form values.
    $form_state->setValue('data', $handler_data->getValues());
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $form_state->cleanValues();

    // The handler's configuration is stored in the 'data' key in the form,
    // pass that through for submission.
    $handler_data = (new FormState())->setValues($form_state->getValue('data'));
    $this->handler->submitConfigurationForm($form, $handler_data);
    // Update the original form values.
    $form_state->setValue('data', $handler_data->getValues());

    $this->handler->setWeight($form_state->getValue('weight'));
    if (!$this->handler->getUuid()) {
      $this->profile->addHandler($this->handler->getConfiguration());
    }
    $this->profile->save();

    drupal_set_message($this->t('The handler was successfully added.'));
    $form_state->setRedirectUrl($this->profile->urlInfo('edit-form'));
  }

  /**
   * Converts a monolog handler ID into an object.
   *
   * @param string $monolog_handler_id
   *   The monolog handler ID.
   *
   * @return \Drupal\monolog\MonologHandlerInterface
   *   The monolog handler object.
   */
  abstract protected function prepareHandler($monolog_handler_id);

}
