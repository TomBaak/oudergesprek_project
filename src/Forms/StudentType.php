<?php
	
	namespace App\Forms;
	
	use App\Entity\Student;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	
	class StudentType extends AbstractType{
		
		public function buildForm(FormBuilderInterface $builder, array $options)
		{
			parent::buildForm($builder, $options);
			$builder
				->add('naam', TextType::class, [
					
					'label' => 'Naam',
				
				])
				->add('studentId', TextType::class, [
					
					'label' => 'Student Nummer',
				
				]);
		}
		
		public function configureOptions(OptionsResolver $resolver)
		{
			$resolver->setDefaults([
				'data_class' => Student::class,
			]);
		}
	
	}