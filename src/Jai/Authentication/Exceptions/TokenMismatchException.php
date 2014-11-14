<?php namespace Jai\Authentication\Exceptions;
/**
 * Class UseTokenMismatchExceptionrExistsException
 *
 * @author Jai beschi Jai@Jaibeschi.com
 */

use Exception;
use Jai\Library\Exceptions\JaiExceptionsInterface;

class TokenMismatchException extends Exception implements JaiExceptionsInterface {}