<?php

/**
 * @file
 * Contains \Drupal\menu_attributes\Form\MenuAttributesSettingsForm.
 */

namespace Drupal\menu_attributes\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Menu Attributes admin settings.
 */
class MenuAttributesSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'menu_attributes_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['menu_attributes.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('menu_attributes.settings');
    $options = [];
    $default_value = [];
    foreach ($config->get('attribute_enable') as $key => $val) {
      $options[$key] = ucwords($key);
      if ($val) {
        $default_value[] = $key;
      }
    }
    $form['menu_attributes_enabled'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Enable Options'),
      '#tree' => TRUE,
    ];
    $form['menu_attributes_enabled']['menu_attributes'] = [
      '#type' => 'checkboxes',
      '#options' => $options,
      '#default_value' => $default_value,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValue('menu_attributes_enabled')['menu_attributes'];

    $config = $this->config('menu_attributes.settings');
    foreach ($values as $key => $val) {
      if ($key === $val) {
        $config->set('attribute_enable.' . $key, TRUE);
      } else {
        $config->set('attribute_enable.' . $key, FALSE);
      }
    }

    $config->save();

    parent::submitForm($form, $form_state);
  }
}
