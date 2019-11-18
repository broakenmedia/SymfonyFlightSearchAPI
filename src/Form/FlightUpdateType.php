<?php
namespace App\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use App\Entity\Flight;
class FlightUpdateType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
        ->add('id', null, ['required'=>false])
        ->add('departureAirport', null, ['required'=>false])
        ->add('arrivalAirport', null, ['required'=>false])
        ->add('airCraft', null, ['required'=>false])
        ->add('departureTime', DateTimeType::class, [
            'format' => 'yyyy-MM-dd HH:mm:ss',
            'widget' => 'single_text',
            'required'=>false
        ])
        ->add('seatCost', null, ['required'=>false])
        ->add('save', SubmitType::class)
    ;
  }
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => Flight::class,
      'csrf_protection' => false
    ));
  }
}