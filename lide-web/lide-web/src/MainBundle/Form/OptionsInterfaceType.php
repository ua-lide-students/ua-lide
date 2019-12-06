<?php 
 
namespace MainBundle\Form; 
 
use Symfony\Component\Form\AbstractType; 
use Symfony\Component\Form\FormBuilderInterface; 
use Symfony\Component\OptionsResolver\OptionsResolver; 
 
use Symfony\Component\Form\Extension\Core\Type\ChoiceType; 
use Symfony\Component\Form\Extension\Core\Type\NumberType; 

use MainBundle\Entity\User;
 
class OptionsInterfaceType extends AbstractType 
{ 
 
    public function buildForm(FormBuilderInterface $builder, array $options) 
    { 
        $builder 
            ->add('aceTheme', ChoiceType::class, array( 
                'label' => "Thème de l'éditeur", 
                'choices' => array( 
                    'tomorrow_night' => 'tomorrow_night', 
                    'clouds' => 'clouds',
                    'clouds_midnight' => 'clouds_midnight',
                    'cobalt' => 'cobalt',
                    'dracula' => 'dracula', 
                    'xcode' => 'xcode', 
                    'tomorrow_night_bright' => 'tomorrow_night_bright', 
                    'eclipse' => 'eclipse', 
                    'pastel_on_dark' => 'pastel_on_dark', 
                    'dawn' => 'dawn', 
                    'crimson_editor' => 'crimson_editor', 
                    'ambiance' => 'ambiance' 
                ), 
            )) 
            ->add('consoleTheme', ChoiceType::class, array( 
                'label' => "Thème de la console", 
                'choices' => array( 
                    'dark' => 'sombre', 
                    'light' => 'claire', 
                    'blue' => 'bleu' 
                ), 
            )) 
            ->add('sizeEditeur', NumberType::class, array( 
                'label' => 'Taille de la police (editeur)' 
            )) 
        ;
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
}; 
?> 

