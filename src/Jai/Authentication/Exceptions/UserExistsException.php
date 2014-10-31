<?php namespace Jai\Authentication\Exceptions;
/**
 * Class UserExistsException
 *
 * @author Jai beschi Jai@Jaibeschi.com
 */

use Exception;
use Jai\Library\Exceptions\JaiExceptionsInterface;

class UserExistsException extends Exception implements JaiExceptionsInterface {}