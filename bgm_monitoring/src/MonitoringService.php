<?php declare(strict_types = 1);

namespace Drupal\bgm_monitoring;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Database\Connection;
use \Drupal\Core\Datetime\DrupalDateTime;
use \Drupal\Core\Logger\RfcLogLevel;

/**
 * @todo Add class description.
 */
final class MonitoringService {

  /**
   * @var int
   */
  private int $messageLength = 120;

  /**
   * @var int
   */
  private int $messageOffset = 0;

  /**
   * @var array
   */
  private array $dogs = [];

  /**
   * Constructs a MonitoringService object.
   */
  public function __construct(
    private readonly Connection $connection,
    private readonly ConfigFactoryInterface $configFactory,
  ) {
    $this->prepareDogs();
    $this->normalizeDogs($this->dogs);
  }

  /**
   * Return all watchdog logs based on log levels saved in settings
   *
   * @return array
   */
  protected function prepareDogs() : void {
    $cfg = $this->configFactory->get('bgm_monitoring.settings')->get('log_levels');
    $query = $this->connection->select('watchdog', 'wd');
    $query->fields('wd', ['type', 'message', 'variables', 'timestamp', 'severity']);
    $orGroup = $query->orConditionGroup();
    foreach ($cfg as $level) {
      if (!$level) {
        continue;
      }
      $orGroup->condition('severity', $level, '=');
    }
    $query->condition($orGroup);
    $result = $query->execute()->fetchAll();

    if ($result && is_array($result)) {
      $this->dogs = $result;
    }
  }

  /**
   * Normalize the logs and make it ready for print
   *
   * @param array $dogs
   * @param int $msgLength
   *
   * @return void
   * @throws \Exception
   */
  protected function normalizeDogs(array &$dogs) : void {
    if (!count($dogs)) {
      throw new \Exception('Invalid dogs!');
    }
    foreach ($dogs as $index => $dog) {
      $vars = unserialize($dog->variables);
      $dogs[$index] = [
        'type' => $dog->type,
        'msg' => substr(str_replace(array_keys($vars), array_values($vars), $dog->message), $this->messageOffset, $this->messageLength),
        'time' => DrupalDateTime::createFromTimestamp($dog->timestamp)->format('d.m.Y H:s:i'),
        'severity' => RfcLogLevel::getLevels()[$dog->severity]->render()
      ];
    }
  }

  /**
   * @return array
   */
  public function getDogs() : array {
    return $this->dogs;
  }

  /**
   * @param array $dogs
   *
   * @return $this
   */
  public function setDogs(array $dogs) : self {
    $this->dogs = $dogs;
    return $this;
  }

  /**
   * @param int $offset
   *
   * @return void
   */
  public function setMsgOffset(int $offset) : self {
    $this->messageOffset = $offset;
    return $this;
  }

  /**
   * @param int $length
   *
   * @return $this
   */
  public function setMsgLength(int $length) : self {
    $this->messageLength = $length;
    return $this;
  }

}
