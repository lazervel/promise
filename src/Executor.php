<?php

declare(strict_types=1);

namespace Modassir\Promise;

final class Executor extends Stack
{
  public $rejected = false;
  private $fired = false;

  /**
   * 
   * @param \Modassir\Promise\Promise $promise [required]
   * @return void
   */
  public function __construct(Promise $promise)
  {
    $this->promise = $promise;
  }

  /**
   * 
   * @param mixed $value  [required]
   * @param string $state [required]
   * 
   * @return void
   */
  private function fire($value, string $state)
  {
    $this->promise->state = $state;
    $this->promise->value = $value;
    $this->fired = true;
  }

  /**
   * 
   * @param \Modassir\Promise\Executor $executor [required]
   */
  private function resolver($executor)
  {
    return static function($value = null) use ($executor) {
      return $executor->resolve($value);
    };
  }

  /**
   * 
   * @param \Modassir\Promise\Executor $executor [required]
   */
  private function rejector($executor)
  {
    return static function($value = null) use ($executor) {
      return $executor->reject($value);
    };
  }

  /**
   * @param mixed $value [optional]
   * @return void
   */
  public function resolve($value = null) : void
  {
    if (!$this->fired) {
      $this->fire($value, 'resolved');
    }
  }

  /**
   * 
   * @param object   $stack    [required]
   * @return void
   */
  private function rejected(object $stack) : void
  {
    $this->rejected = true;
    $this->fire($stack, 'rejected');
  }

  /**
   * @param mixed $value [optional]
   * @return void
   */
  public function reject($value = null) : void
  {
    if (!$this->fired) {
      $this->rejected = true;
      $this->fire($value, 'reject');
    }
  }

  /**
   * 
   * @param \Modassir\Promise\Promise $promise [required]
   * @return \Modassir\Promise\Executor
   */
  public static function with(Promise $promise)
  {
    return new self($promise);
  }

  /**
   * @param callable $executor [required]
   *                           A callback used to initialize the promise.
   */
  public function execute(callable $executor)
  {
    Stack::PHP_CustomErrorHandlerActivate();
    try {
      $executor($this->resolver($this), $this->rejector($this));
    } catch(\Exception $e) {
      $this->rejected($e);
    } catch(\Error $e) {
      $this->rejected($e);
    }
  }
}
?>