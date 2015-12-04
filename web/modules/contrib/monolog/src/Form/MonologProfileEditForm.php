<?php

/**
 * @file
 * Contains \Drupal\monolog\Form\MonologProfileEditForm.
 */

namespace Drupal\monolog\Form;

use Drupal\Component\Utility\SafeMarkup;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\monolog\ConfigurableMonologHandlerInterface;
use Drupal\monolog\Logger\MonologLogLevel;
use Drupal\monolog\MonologHandlerManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Add form for monolog profile edit forms.
 */
class MonologProfileEditForm extends MonologProfileFormBase {

  /**
   * The monolog handler manager service.
   *
   * @var \Drupal\monolog\MonologHandlerManager
   */
  protected $handlerManager;

  /**
   * Constructs an MonologProfileEditForm object.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $monolog_profile_storage
   *   The storage.
   * @param \Drupal\monolog\MonologHandlerManager $monolog_handler_manager
   *   The monolog handler manager service.
   */
  public function __construct(EntityStorageInterface $monolog_profile_storage, MonologHandlerManager $monolog_handler_manager) {
    parent::__construct($monolog_profile_storage);
    $this->handlerManager = $monolog_handler_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.manager')->getStorage('monolog_profile'),
      $container->get('plugin.manager.monolog.handler')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    $form['#title'] = $this->t('Edit profile %label', ['%label' => $this->entity->label()]);
    $user_input = $form_state->getUserInput();

    // Build the list of existing handlers.
    $form['handlers'] = [
      '#type' => 'table',
      '#header' => [
        $this->t('Label'),
        $this->t('Handler'),
        $this->t('Log level'),
        $this->t('Bubble messages'),
        $this->t('Weight'),
        $this->t('Operations'),
      ],
      '#tabledrag' => [
        [
          'action' => 'order',
          'relationship' => 'sibling',
          'group' => 'monolog-handler-order-weight',
        ],
      ],
      '#attributes' => [
        'id' => 'monolog-profile-handlers',
      ],
      '#empty' => $this->t('This profile has no handlers. Add one by selecting an option below.'),
      // Render handlers below parent elements.
      '#weight' => 5,
    ];

    foreach ($this->entity->getHandlers() as $handler) {
      $key = $handler->getUuid();
      $form['handlers'][$key]['#attributes']['class'][] = 'draggable';
      $form['handlers'][$key]['#weight'] = isset($user_input['handlers']) ? $user_input['handlers'][$key]['weight'] : NULL;
      $form['handlers'][$key]['label'] = [
         '#markup' => SafeMarkup::checkPlain($handler->label()),
      ];
      $form['handlers'][$key]['handler'] = [
         '#markup' => SafeMarkup::checkPlain($handler->getPluginId()),
      ];
      $form['handlers'][$key]['level'] = [
        '#type' => 'select',
        '#title' => $this->t('Logging level for @handler', ['@handler' => $handler->label()]),
        '#title_display' => 'invisible',
        '#default_value' => $handler->getLevel(),
        '#options' => MonologLogLevel::getLevels(),
      ];

      $form['handlers'][$key]['bubble'] = [
        '#type' => 'select',
        '#title' => $this->t('Bubble setting for @handler', ['@handler' => $handler->label()]),
        '#title_display' => 'invisible',
        '#default_value' => $handler->allowsBubblingUp(),
        '#options' => [
          1 => t('Yes'),
          0 => t('No'),
        ],
      ];

      $form['handlers'][$key]['weight'] = [
        '#type' => 'weight',
        '#title' => $this->t('Weight for @handler', ['@handler' => $handler->label()]),
        '#title_display' => 'invisible',
        '#default_value' => $handler->getWeight(),
        '#attributes' => ['class' => ['monolog-handler-order-weight']],
      ];

      $links = [];
      $is_configurable = $handler instanceof ConfigurableMonologHandlerInterface;
      if ($is_configurable) {
        $links['edit'] = [
          'title' => $this->t('Edit'),
          'url' => Url::fromRoute('monolog.profile_handler_edit_form', [
            'monolog_profile' => $this->entity->id(),
            'monolog_handler' => $key,
          ]),
        ];
      }
      $links['delete'] = [
        'title' => $this->t('Delete'),
        'url' => Url::fromRoute('monolog.profile_handler_delete_form', [
          'monolog_profile' => $this->entity->id(),
          'monolog_handler' => $key,
        ]),
      ];
      $form['handlers'][$key]['operations'] = [
        '#type' => 'operations',
        '#links' => $links,
      ];
    }

    // Build the new handler addition form and add it to the handlers list.
    $new_handler_options = [];
    $handlers = $this->handlerManager->getDefinitions();
    uasort($handlers, function ($a, $b) {
      return strcasecmp($a['id'], $b['id']);
    });
    foreach ($handlers as $handler => $definition) {
      $new_handler_options[$handler] = $definition['label'];
    }
    $form['handlers']['new'] = [
      '#tree' => FALSE,
      '#weight' => isset($user_input['weight']) ? $user_input['weight'] : NULL,
      '#attributes' => ['class' => ['draggable']],
    ];
    $form['handlers']['new']['handler'] = [
      'data' => [
        'new' => [
          '#type' => 'select',
          '#title' => $this->t('Effect'),
          '#title_display' => 'invisible',
          '#options' => $new_handler_options,
          '#empty_option' => $this->t('Select a new handler'),
        ],
        [
          'add' => [
            '#type' => 'submit',
            '#value' => $this->t('Add'),
            '#validate' => ['::handlerValidate'],
            '#submit' => ['::submitForm', '::handlerSave'],
          ],
        ],
      ],
      '#prefix' => '<div class="monolog-handler-new">',
      '#suffix' => '</div>',
    ];

    $form['handlers']['new']['label'] = $form['handlers']['new']['level'] = $form['handlers']['new']['bubble'] = [
      'data' => [],
    ];
    $form['handlers']['new']['weight'] = [
      '#type' => 'weight',
      '#title' => $this->t('Weight for new effect'),
      '#title_display' => 'invisible',
      '#default_value' => count($this->entity->getHandlers()) + 1,
      '#attributes' => ['class' => ['monolog-handler-order-weight']],
    ];
    $form['handlers']['new']['operations'] = [
      'data' => [],
    ];

    return parent::form($form, $form_state);
  }

  /**
   * Validate handler for monolog handler.
   */
  public function handlerValidate($form, FormStateInterface $form_state) {
    if (!$form_state->getValue('new')) {
      $form_state->setErrorByName('new', $this->t('Select a handler to add.'));
    }
  }

  /**
   * Submit handler for monolog handler.
   */
  public function handlerSave($form, FormStateInterface $form_state) {
    $this->save($form, $form_state);

    // Check if this field has any configuration options.
    $handler = $this->handlerManager->getDefinition($form_state->getValue('new'));

    // Load the configuration form for this option.
    if (is_subclass_of($handler['class'], '\Drupal\monolog\ConfigurableMonologHandlerInterface')) {
      $form_state->setRedirect(
        'monolog.profile_handler_add_form',
        [
          'monolog_profile' => $this->entity->id(),
          'monolog_handler' => $form_state->getValue('new'),
        ],
        ['query' => ['weight' => $form_state->getValue('weight')]]
      );
    }
    // If there's no form, immediately add the handler.
    else {
      $handler = [
        'id' => $handler['id'],
        'data' => [
          'weight' => $form_state->getValue('weight'),
        ],
      ];
      $handler_id = $this->entity->addHandler($handler);
      $this->entity->save();
      if (!empty($handler_id)) {
        drupal_set_message($this->t('The handler was successfully applied.'));
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    foreach ($form_state->getValue('handlers') as $uuid => $data) {
      // Update handlers weights.
      if ($this->entity->getHandlers()->has($uuid)) {
        $handler = $this->entity->getHandler($uuid);
        $handler->setWeight($data['weight'])
          ->setLevel($data['level'])
          ->setAllowsBubblingUp($data['bubble']);
      }
    }

    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function copyFormValuesToEntity(EntityInterface $entity, array $form, FormStateInterface $form_state) {
    foreach ($form_state->getValues() as $key => $value) {
      // Do not copy handlers here, see self::submitForm().
      if ($key != 'handlers') {
        $entity->set($key, $value);
      }
    }
  }

}
