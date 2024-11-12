<?php

declare(strict_types=1);

namespace Modassir\Promise;

\error_reporting(E_ALL);
\ini_set('display_errors', 0);

class Stack
{
  private $message;
  private $code;
  private $file;
  private $line;

  public function __construct(int $code, string $message, string $file, int $line)
  {
    $this->code = $code;
    $this->message = $message;
    $this->file = $file;
    $this->line = $line;
  }

  public function getMessage()
  {
    return $this->message;
  }

  public function getCode()
  {
    return $this->code;
  }

  protected function getStack()
  {
    return $this;
  }

  public function getFile()
  {
    return $this->file;
  }

  public function getLine()
  {
    return $this->line;
  }

  public function getTraceAsString()
  {
    $exception = new \Exception($this->message);
    return $exception->getTraceAsString();
  }

  /**
   * 
   */
  private static function shutdown()
  {
    $error = \error_get_last();
    if ($error === null) {
      return;
    }
    new self($error->type, $error->message, $error->file, $error->line);
  }

  public static function PHP_CustomErrorHandlerActivate()
  {
    \set_error_handler(self::errorHandler(...));
    \register_shutdown_function(self::shutdown(...));
  }

  /**
   * 
   */
  private static function errorHandler(int $code, string $message, string $file, int $line)
  {
    new self($code, $message, $file, $line);
  }

  public function __toString()
  {
    return '';
  }
}
?>
