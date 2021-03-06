<?php
	
	
	namespace App\Forms;
	
	use App\Entity\Klas;
	use App\Entity\Uitnodiging;
	use Doctrine\ORM\EntityRepository;
	use Symfony\Bridge\Doctrine\Form\Type\EntityType;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\Extension\Core\Type\DateType;
	use Symfony\Component\Form\Extension\Core\Type\TimeType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\HttpFoundation\Session\SessionInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	
	class UitnodigingType extends AbstractType
	{
		
		public function buildForm(FormBuilderInterface $builder, array $options)
		{
			$builder
				->add('klas', EntityType::class, [
					
					'class' => Klas::class,
					'required' => true,
					'choice_label' => function ($category) {
						return $category->getNaam() . ' - ' . $category->getSlb()->getLastname() . " - Studenten: " . count($category->getStudents());
					},
					'query_builder' => function (EntityRepository $er) {
						return $er->createQueryBuilder('u')
//							TODO: Make where query
							->orderBy('u.naam', 'ASC');
					},
				
				])
				->add('date', DateType::class, [
					
					'years' => range(date('Y'), date('Y') + 5),
					'label' => 'Datum',
					'required' => true
				
				
				])
				->add('start_time', TimeType::class, [
					'label' => 'Begin tijd',
					'required' => true,
					'minutes' => [0, 15, 30, 45]])
				->add('stop_time', TimeType::class, [
					'label' => 'Eind tijd',
					'required' => true,
					'minutes' => [0, 15, 30, 45]]);
		}
		
		public function configureOptions(OptionsResolver $resolver)
		{
			$resolver->setDefaults([
				'data_class' => Uitnodiging::class,
			]);
		}
		
	}