<?php

/**
 * @file
 * Contains \Drupal\monolog\Form\MonologProfileFormBase.
 */

namespace Drupal\monolog\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base form for monolog profile add and edit forms.
 */
abstract class MonologProfileFormBase extends EntityForm {

  /**
   * The profile entity storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $monologProfileStorage;

  /**
   * Constructs a new MonologProfileFormBase.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $monolog_profile_storage
   *   The monolog profile entity storage.
   */
  public function __construct(EntityStorageInterface $monolog_profile_storage) {
    $this->monologProfileStorage = $monolog_profile_storage;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.manager')->getStorage('monolog_profile')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {

    $form['label'] = [
      '#title' => t('Label'),
      '#type' => 'textfield',
      '#default_value' => $this->entity->label(),
      '#description' => t('The human-readable name of the logging profile.'),
      '#required' => TRUE,
      '#maxlength' => 255,
      '#size' => 30,
    ];

    $form['name'] = [
      '#type' => 'machine_name',
      '#default_value' => $this->entity->id(),
      '#maxlength' => 32,
      '#machine_name' => [
        'exists' => [$this->monologProfileStorage, 'load'],
      ],
      '#disabled' => $this->entity->id(),
      '#description' => t('The machine readable name of the logging profile. This value can only contain letters, numbers, and underscores.'),
    ];

    return parent::form($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    parent::save($form, $form_state);
    $form_state->setRedirectUrl($this->entity->urlInfo('edit-form'));
  }

}
