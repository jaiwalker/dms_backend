<?php  namespace Jai\Authentication\Classes\Images;

use Image;
use Illuminate\Support\Facades\Input;
use Jai\Library\Exceptions\NotFoundException;

/**
 * Trait ImageHelperTrait
 *
 * @author Jai beschi Jai@Jaibeschi.com
 */
trait ImageHelperTrait {

    public static function getPathFromInput($input_name)
    {
        if (Input::hasFile($input_name))
        {
            return $path = Input::file($input_name)->getRealPath();
        }
        else
        {
            throw new NotFoundException('File non found.');
        }
    }

    /**
     * Fetch an image given a path
     */
    public static function getBinaryData($size = 170, $input_name)
    {
        return Image::make(static::getPathFromInput($input_name))->fit($size)->encode();
    }

} 