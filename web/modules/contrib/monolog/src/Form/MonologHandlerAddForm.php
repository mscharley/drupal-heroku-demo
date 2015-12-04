<?php

/**
 * @file
 * Contains \Drupal\monolog\Form\MonologHandlerAddForm.
 */

namespace Drupal\monolog\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\monolog\MonologHandlerManager;
use Drupal\monolog\MonologProfileInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a base form for monolog handlers.
 */
class MonologHandlerAddForm extends MonologHandlerFormBase {

  /**
   * The monolog handler manager.
   *
   * @var \Drupal\monolog\MonologHandlerManager
   */
  protected $handlerManager;

  /**
   * Constructs a new MonologHandlerAddForm.
   *
   * @param \Drupal\monolog\MonologHandlerManager $handler_manager
   *   The monolog handler manager.
   */
  public function __construct(MonologHandlerManager $handler_manager) {
    $this->handlerManager = $handler_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.monolog.handler')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, MonologProfileInterface $monolog_profile = NULL, $monolog_handler = NULL) {
    $form = parent::buildForm($form, $form_state, $monolog_profile, $monolog_handler);

    $form['#title'] = $this->t('Add %label handler', array('%label' => $this->handler->label()));
    $form['actions']['submit']['#value'] = $this->t('Add handler');

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  protected function prepareHandler($monolog_handler_id) {
    $handler = $this->handlerManager->createInstance($monolog_handler_id);
    // Set the initial weight so this effect comes last.
    $handler->setWeight(count($this->profile->getHandlers()));
    return $handler;
  }

}
