<?php

namespace Podcast\MainBundle\Entity;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MyStringHelper
 *
 * @author Eddy
 */
class MyStringHelper
{
    public static function sluggize($text)
    {
        // replace all non letters or digits by -
        $text = preg_replace('/\W+/', '-', $text);

        // trim and lowercase
        $text = strtolower(trim($text, '-'));

        return $text;
    }

}
