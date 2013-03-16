<?php

namespace Podcast\MainBundle\Form\PodcastType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class PodcastType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('link', 'text')
                ->add('name', 'text');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Podcast\MainBundle\Entity\Podcast'
        ));
    }

    public function getName()
    {
        return 'podcast';
    }

}
