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

class Promise extends Executor
{
  /**
   * Creates a new Promise instance.
   * Initializes a new instance of Promise with the given $executor.
   * 
   * @param callable $executor [required]
   *                           A callback used to initialize the promise.
   * @return void
   */
  public function __construct(callable $executor)
  {
    parent::__construct($executor);
  }

  /**
   * Attaches a callback that is invoked when the Promise is settled (fulfilled or rejected).
   * The resolved value cannot be modified from the callback.
   * 
   * @param callable $onFinally [optional]
   *                            The callback to execute when the Promise is settled (fulfilled or rejected).
   * @return \Modassir\Promise\Promise
   */
  public function finally(callable $onFinally = null)
  {
    if ($onFinally) {
      $this->queue[] = ['finally' => $onFinally];
    }
    return $this;
  }

  /**
   * Attaches a callback for only the rejection of the Promise.
   * 
   * @param callable $onRejected [optional]
   *                             The callback to execute when the Promise is rejected.
   * 
   * @return \Modassir\Promise\Promise A Promise for the completion of the callback.
   */
  public function catch(callable $onRejected = null)
  {
    return $this->then(null, $onRejected);
  }

  /**
   * Attaches callbacks for the resolution and/or rejection of the Promise.
   * 
   * @param callable $onFulfilled [optional]
   *                              The callback to execute when the Promise is resolved.
   * @param callable $onRejected  [optional]
   *                              The callback to execute when the Promise is rejected.
   * 
   * @return \Modassir\Promise\Promise A Promise for the completion of which ever callback is executed.
   */
  public function then(callable $onFulfilled = null, callable $onRejected = null)
  {
    $this->addHandlers($onFulfilled, $onRejected);
    return $this;
  }

  /**
   * Destructor method.
   * This method is automatically called when the object is destroyed.
   * It is used to release any resources or perform cleanup tasks,
   * such as closing database connections, file handles, or other resources.
   * 
   * @throws Modassir\Promise\Exception\UnhandledPromiseRejection Rejected without catch blocking.
   * @return void
   */
  public function __destruct()
  {
    $this->finalExecutorExecute();
  }
}
?>