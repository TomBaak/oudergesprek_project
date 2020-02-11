<?php
	
	
	namespace App\Forms;
	
	
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
//					'help' => 'De lijst met studenten van deze klas geëxporteerd uit magister',
					'constraints' => [
						new File([
							'mimeTypes' => [
								'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
							],
							'mimeTypesMessage' => 'Upload alstublief een geldig .xlsx bestand',
						])
					]
				])->add('update', CheckboxType::class, [
					'label' => 'Al bestaande studenten wijzigen',
					'help' => 'Als een student op de lijst al in dit systeem staat zullen de gegevens worden vervangen met de gegevens op de ingevoerde lijst mochten deze afwijken',
					'required' => NULL
				]);
		}
		
	}