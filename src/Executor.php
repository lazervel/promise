<?php

declare(strict_types=1);

/**
 * The PHP Promise handling PHP promises with additional utilities and features.

 * The (promise) Github Repository
 * @see       https://github.com/lazervel/promise
 * 
 * @author    Shahzada Modassir
 * @copyright (c) Shahzada Modassir 2024
 * 
 * @license   MIT License
 * @see       https://github.com/lazervel/promise/blob/main/LICENSE
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Modassir\Promise;

use Modassir\Promise\Exception\UnhandledPromiseRejection;

 class Executor
{
  /**
   * Default promise state,
   * initially set to 'pending' until resolved or rejected.
   * 
   * @var string
   */
  protected $state = 'pending';

  /**
   * Flag to know if executor handler was already fired
   * 
   * @var bool
   */
  private $fired = false;

  /**
   * Flag to know if catch block exists or not exists status
   * 
   * @var bool
   */
  private $catched = false;

  /**
   * Flag to prevent firing
   * 
   * @var bool
   */
  private $locked = false;

  /**
   * Flag to know if executor handler is currently firing
   */
  private $firing = false;

  /**
   * Actual callback arguments
   * 
   * @var array
   */
  private $arguments;

  /**
   * A callback used to initialize the executor constructor.
   * 
   * @var callable
   */
  private $executor;

  /**
   * A callback used to call the finally method of promise.
   * 
   * @var callable
   */
  protected $finallyClouser;

  /**
   * Flag to know rejected or not rejected status
   * 
   * @var bool
   */
  private $rejected = false;

  /**
   * 
   * @var array
   */
  protected $queue = [];

  /**
   * Creates a new Executor instance.
   * Initializes a new instance of Executor with the given $executor.
   * 
   * @param callable $executor [required]
   *                           A callback used to initialize the executor.
   * @return void
   */
  public function __construct(callable $executor)
  {
    $this->executor = $executor;
  }

  /**
   * Execute the final executor with resolve custom Error or Exception.
   * 
   * @return void
   */
  protected function finalExecutorExecute() : void
  {
    try {
      ErrorHandler::PHP_ErrorHandlerActivate();
      $this->execute();
    } catch(\Exception $e) {
      $this->execute($e, 'reject', $e->getMessage());
    } catch(\Error $e) {
      $this->execute($e, 'reject', $e->getMessage());
    }
  }

  /**
   * Creates a new resolved promise.
   * 
   * @param mixed $value [optional]
   * @return void
   */
  private function resolve($value = null) : void
  {
    $this->fireWith($value, 'resolved');
  }

  /**
   * Creates a new rejected promise.
   * 
   * @param mixed $value [optional]
   * @return void
   */
  private function reject($value = null) : void
  {
    $this->rejected = true;
    $this->fireWith($value, 'rejected');
  }

  /**
   * 
   * 
   * @param string $handler [required]
   * @return static Returns resolve or reject Handler.
   */
  private function getHandler(string $handler)
  {
    $promise = $this;
    return static function($value = null) use ($promise, $handler) {
      $promise->$handler($value);
    };
  }

  /**
   * Call all callbacks with the given context and arguments
   * fire - Method will used to call promise block handlers of specific state,
   * This method could not be fire of 'pending' state.
   * @return void
   */
  private function fire() : void
  {
    $this->locked = $this->firing = true;
    foreach($this->queue as $queue) {

      if (($clouser = $queue['finally'] ?? false)) {
        \call_user_func($clouser);
      } elseif (($clouser = $queue[$this->state] ?? false)) {
        \call_user_func_array($clouser, $this->arguments);
      }
    }
  }

  /**
   * Call all callbacks with the given context and arguments
   * 
   * @param mixed  $value [optional]
   * @param string $state [required]
   * 
   * @return void
   */
  private function fireWith($value = null, string $state) : void
  {
    if (!$this->locked) {
      $this->arguments = [$value, $this->state = $state];
      if (!$this->firing) {
        $this->fire();
      }
    }
  }

  /**
   * Set all handlers queue for 'resolved', 'rejected', 'finally' in queue
   * 
   * @param callable $fnHandler [required]
   * @param string   $state     [required]
   * 
   * @return void
   */
  private function setHandler(callable $fnHandler, string $state) : void
  {
    $this->queue[] = [$state => $fnHandler];
  }

  /**
   * 
   * 
   * @param callable $onFulfilled [optional]
   * @param callable $onRejected  [optional]
   * 
   * @return void
   */
  protected function addHandlers(callable $onFulfilled = null, callable $onRejected = null) : void
  {
    if ($onRejected) $this->catched = true;
    $onFulfilled && $this->setHandler($onFulfilled, 'resolved');
    $onRejected && $this->setHandler($onRejected, 'rejected');
  }

  /**
   * 
   * @throws Modassir\Promise\Exception\UnhandledPromiseRejection Rejected without catch blocking.
   * 
   * @return void
   */
  private function execute($value = null, string $handler = null, string $EMessage = null) : void
  {
    if ($value && $handler) {
      $this->$handler($value);
    } else {
      \call_user_func($this->executor, $this->getHandler('resolve'), $this->getHandler('reject'));
    }

    if (!$this->catched && $this->rejected) {
      throw new UnhandledPromiseRejection(
        sprintf(
          'This error originated either by throwing inside of an method without a catch block, or by rejecting a promise which was not handled with ->catch(). The promise rejected with the reason [%s].',
          $EMessage ?? $this->arguments[0] ?? NULL
        )
      );
    }
  }
}
?>