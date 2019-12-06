<?php
/**
 * Created by PhpStorm.
 * User: etudiant
 * Date: 08/10/18
 * Time: 14:23
 */

namespace MainBundle\Form;


use MainBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('editorTheme', TextType::class)
            ->add('editorTextSize', NumberType::class)
            ->add('layoutStacked', CheckboxType::class, [
                'label' => 'Use stacked Layout'
            ])
            ->add('lineHighlight', CheckboxType::class)
            ->add('useDarkTheme', CheckboxType::class)
            ->add('useWrapMode', CheckboxType::class)
            ->add('useSoftTabs', CheckboxType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
            'csrf_protection' => false
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'mainbundle_options_interface';
    }

}