<?php

namespace Podcast\MainBundle\Form\PodcastType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class PodcastForm extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('link','text');
    }
    public function getName()
    {
        return 'podcast';
    }
}