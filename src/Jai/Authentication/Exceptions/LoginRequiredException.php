<?php namespace Jai\Authentication\Exceptions;
/**
 * Class UserNotFoundException
 *
 * @author Jai beschi Jai@Jaibeschi.com
 */

use Exception;
use Jai\Library\Exceptions\JaiExceptionsInterface;

class LoginRequiredException extends Exception implements JaiExceptionsInterface {}