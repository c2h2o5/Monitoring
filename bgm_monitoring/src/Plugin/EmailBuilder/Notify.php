<?php
namespace Drupal\bgm_monitoring\Plugin\EmailBuilder;

use Drupal\symfony_mailer\EmailFactoryInterface;
use Drupal\symfony_mailer\EmailInterface;
use Drupal\symfony_mailer\Processor\EmailBuilderBase;
use Drupal\symfony_mailer\Processor\TokenProcessorTrait;

/**
 * Defines the Email Builder plug-in for Company module.
 * @see https://www.drupal.org/project/symfony_mailer
 *
 * @EmailBuilder(
 *   id = "bgm_monitoring",
 *   sub_types = {
        "notify_on_error" = @Translation("Monitoring Notify"),
 *   },
 *   common_adjusters = {
        "email_subject",
 *      "email_body",
 *      "email_to",
 *   },
 * )
 */

class Notify extends EmailBuilderBase
{
    use TokenProcessorTrait;

    /**
     * @param EmailInterface $email
     * @param null|array $dogs
     * @return void
     */
    public function createParams(EmailInterface $email, ?array $dogs = null)
    {
        assert($dogs != null);
        $email->setParam('dogs', $dogs);
    }


    /**
     * @param EmailInterface $email
     * @return void
     */
    public function preRender(EmailInterface $email)
    {
//        $this->tokenOptions(['callback' => 'bgm_company_tokens', 'clear' => TRUE]);
//        $this->tokenOptions(['callback' => 'bgm_users_tokens', 'clear' => TRUE]);
    }

    /**
     * @param EmailInterface $email
     * @return void
     */
    public function build(EmailInterface $email)
    {
//      $dogs = $email->getParam('dogs');
//      switch ($email->getSubType())
//      {
//        case 'notify_on_error':
//          if ($dogs) {
//            $email->setVariable('dogs', $dogs);
//          }
//          break;
//      }
    }
}
