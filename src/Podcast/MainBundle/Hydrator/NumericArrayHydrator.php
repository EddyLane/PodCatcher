<?php
/**
 * Created by JetBrains PhpStorm.
 * User: edwardlane
 * Date: 07/07/2013
 * Time: 16:11
 * To change this template use File | Settings | File Templates.
 */

namespace Podcast\MainBundle\Hydrator;
use Doctrine\ORM\Internal\Hydration\ArrayHydrator;

class NumericArrayHydrator extends ArrayHydrator
{
    /**
     * {@inheritdoc}
     */
    protected function hydrateAllData()
    {
        $result = parent::hydrateAllData();
        return array_map(function ($a) {
            return $a['id'];
        }, $result);
    }

}