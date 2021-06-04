<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //creation champs formulaire

                //changement du texte de l'input nom et prenom et mise en place d'un placeholder :

            ->add('nom',TextType::class, [
                'label' => 'votre nom ',
                'attr'=> [
                    'placeholder' => 'champs requis'
                ]
            ])


            ->add('prenom', TextType::class,[
                'label'=>'votre prenom ',
                'attr'=> [
                    'placeholder' => 'champs requis'
                ]
            ])

            //adresse mail valide @
            ->add('email',EmailType::class,[
                'label'=>'Adresse email ',
                'attr'=> [
                    'placeholder' => 'champs requis'
                ]
            ])

            //gestion du mot de passe:
            ->add('password', RepeatedType::class,[
                'type'=>PasswordType::class,
                'invalid_message'=>'mot de passe invalide',
                'label'=>'Mot de passe ',
                'required'=>true,
                'first_options'=>['label'=>'Mot de passe'],
                'second_options'=>['label'=>'Confirmer Mot de passe'],

            ])

            //bouton submit
            ->add('submit', SubmitType::class, [

                'label' => 'valider'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
