<?php

namespace Acme\TestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BlogType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('blogTitle')
            ->add('description')
            ->add('attachment','file',
                    array('required' => true))
            ->add('addedOn');
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Acme\TestBundle\Entity\Blog'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'acme_testbundle_blog';
    }
}
