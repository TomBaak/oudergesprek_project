<?php
	
	
	namespace App\Forms;
	
	
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\Extension\Core\Type\FileType;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\Validator\Constraints\File;
	
	class StudentenType extends AbstractType
	{
		
		public function buildForm(FormBuilderInterface $builder, array $options)
		{
			parent::buildForm($builder, $options);
			$builder
				->add('studentFile', FileType::class, [
					
					'label' => 'Lijst met studenten',
					'required' => true,
					'help' => 'De lijst met studenten van deze klas geëxporteerd uit magister'
				
				]);
		}
	
	}