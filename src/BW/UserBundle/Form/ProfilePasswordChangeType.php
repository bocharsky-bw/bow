<?php

namespace BW\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;

class ProfilePasswordChangeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                //->add('old_password', 'repeated', array(
                ->add('password', 'repeated', array(
                    'type' => 'password',
                    'invalid_message' => 'Пароли не совпадают',
                    'options' => array('attr' => array('class' => 'password-field')),
                    'required' => FALSE,
                    'first_options'  => array('label' => 'Новый пароль'),
                    'second_options' => array('label' => 'Подтверждение пароля'),
                ))
                ->add('changePassword', 'submit')
            ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BW\UserBundle\Entity\User',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'password_change';
    }
}
