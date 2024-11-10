<?php

declare(strict_types=1);

namespace Modassir\Promise;

class Promise
{
  public $state = 'pending';
  private $executor;
  public $value;

  /**
   * Creates a new Promise instance.
   * @param callable $executor [required]
   *                           A callback used to initialize the promise.
   * @return void
   */
  public function __construct(callable $executor)
  {
    $this->executor = Executor::with($this)->execute($executor);
  }

  /**
   * @param callable $onFulfilled [optional]
   *                              The callback to execute when the Promise is resolved.
   * @return \Modassir\Promise\Promise
   */
  public function done(?callable $onFulfilled = null)
  {
    return $this->then($onFulfilled);
  }

  /**
   * @param callable $onRejected [optional]
   *                             The callback to execute when the Promise is rejected.
   * @return \Modassir\Promise\Promise
   */
  public function fail(?callable $onRejected = null)
  {
    return $this->catch($onRejected);
  }

  /**
   * @param callable $onFinally [optional]
   *                            The callback to execute when the Promise is settled (fulfilled or rejected).
   * @return \Modassir\Promise\Promise
   */
  public function finally(?callable $onFinally = null)
  {
    return $this->done($onFinally)->fail($onFinally);
  }

  /**
   * @param callable $onRejected [optional]
   *                             The callback to execute when the Promise is rejected.
   * @return \Modassir\Promise\Promise
   */
  public function catch(?callable $onRejected = null)
  {
    return $this->then(null, $onRejected);
  }

  /**
   * @param callable $onFinally [optional]
   *                            The callback to execute when the Promise is settled (fulfilled or rejected).
   * @return \Modassir\Promise\Promise
   */
  public function always(?callable $onFinally = null)
  {
    return $this->finally($onFinally);
  }

  /**
   * @param callable $onFulfilled [optional]
   *                              The callback to execute when the Promise is resolved.
   * @param callable $onRejected  [optional]
   *                              The callback to execute when the Promise is rejected.
   * 
   * @return \Modassir\Promise\Promise
   */
  public function then(?callable $onFulfilled = null, ?callable $onRejected = null)
  {
    $this->state === 'rejected' ? $onRejected && $onRejected($this->value) : $onFulfilled && $onFulfilled($this->value);
    return $this;
  }
}
?>