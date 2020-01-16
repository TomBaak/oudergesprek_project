<?php
	
	
	namespace App\Entity;
	
	use App\Classes\Student;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	
	class LeerlingType extends AbstractType
	{
		
		public function buildForm(FormBuilderInterface $builder, array $options)
		{
			$builder
				->add('naam')
				->add('studentId');
		}
		
		public function configureOptions(OptionsResolver $resolver)
		{
			$resolver->setDefaults([
				'data_class' => Student::class,
			]);
		}
		
	}