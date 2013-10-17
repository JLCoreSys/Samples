<?php

namespace CoreSys\NotesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Event\DataEvent;

class NoteType extends AbstractType
{

    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $builder
        ->add( 'note', 'textarea',
               array( 'required' => TRUE, 'attr' => array( 'rows'          => 6, 'class' => 'input full-width',
                                                           'data-postdesc' => 'The note.' ) ) );
    }

    public function getName()
    {
        return 'note_type';
    }
}
