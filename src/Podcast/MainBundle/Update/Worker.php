<?php

namespace Podcast\MainBundle\Update;

use Hautelook\GearmanBundle\Model\GearmanJobInterface;

class Worker implements GearmanJobInterface
{
    private $string;

    public function setString($string)
    {
        $this->string = $string;
    }

    /**
     * {@inheritDoc}
     */
    public function getWorkload()
    {
        return array('str' => $this->string);
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctionName()
    {
        return 'string_reverse';
    }

    /**
     * {@inheritDoc}
     */
    public function setWorkload(array $workload)
    {
        if (isset($workload['str'])) {
            $this->string = $str;
        }
    }
}
