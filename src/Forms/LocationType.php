<?php
	
	
	namespace App\Forms;
	
	
	use App\Entity\Location;
	use App\Entity\Student;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	
	class LocationType extends AbstractType
	{
		
		public function buildForm(FormBuilderInterface $builder, array $options)
		{
			parent::buildForm($builder, $options);
			$builder
				->add('naam', TextType::class, [
					
					'label' => 'Naam',
					'required' => true,
					'help' => 'Bijvoorbeeld: School voor ICT'
				
				])->add('directeur', TextType::class, [
					
					'label' => 'Directeur',
					'required' => true,
					'help' => 'Bijvoorbeeld: T.R. Baak'
				
				])->add('adres', TextType::class, [
					
					'label' => 'Adres',
					'required' => true
				
				]);
		}
		
		public function configureOptions(OptionsResolver $resolver)
		{
			$resolver->setDefaults([
				'data_class' => Location::class,
			]);
		}
		
	}