<?php

declare(strict_types=1);

namespace Modassir\Promise;

\error_reporting(E_ALL);
\ini_set('display_errors', 0);

final class ErrorHandler
{
  /**
   * 
   */
  private static function errorHandler() : void
  {
    // 
  }

  /**
   * 
   */
  private static function shutdown() : void
  {
    // 
  }

  /**
   * 
   * @return
   */
  public static function PHP_ErrorHandlerActivate()
  {
    \set_error_handler(self::errorHandler(...));
    \register_shutdown_function(self::shutdown(...));
  }
}
?>