<?php declare(strict_types = 1);

namespace Drupal\bgm_monitoring\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Logger\RfcLogLevel;

/**
 * Configure BGM Monitoring settings for this site.
 */
final class Settings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'bgm_monitoring_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return ['bgm_monitoring.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $form['wrapper'] = [
      '#type' => 'fieldset',
      '#title' => 'Levels'
    ];

    $form['wrapper']['inline'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['container-inline']
      ],
    ];

    $form['wrapper']['inline']['levels'] = [
      '#type' => 'checkboxes',
      '#title' => 'Beobachte folgende Nachrichts- Typen:',
      '#options' => RfcLogLevel::getLevels(),
      '#default_value' => $this->config('bgm_monitoring.settings')->get('log_levels') ?? [],
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    // @todo Validate the form here.
    // Example:
    // @code
    //   if ($form_state->getValue('example') === 'wrong') {
    //     $form_state->setErrorByName(
    //       'message',
    //       $this->t('The value is not correct.'),
    //     );
    //   }
    // @endcode
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $this->config('bgm_monitoring.settings')
      ->set('log_levels', $form_state->getValue('levels'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
