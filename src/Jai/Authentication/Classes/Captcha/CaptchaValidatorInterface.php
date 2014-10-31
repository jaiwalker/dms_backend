<?php
namespace Jai\Authentication\Classes\Captcha;

/**
 * Class CaptchaValidator
 *
 * @author Jai beschi Jai@Jaibeschi.com
 */
interface CaptchaValidatorInterface
{
    public function validateCaptcha($attribute, $value);

    public function getValue();

    /**
     * @return mixed
     */
    public function getErrorMessage();

    public function getImageSrcTag();
}