<?php
	
	
	use App\Entity\Klas;
	use App\Entity\LeerlingType;
	use App\Entity\Location;
	use App\Entity\User;
	use Doctrine\ORM\EntityRepository;
	use Symfony\Bridge\Doctrine\Form\Type\EntityType;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
	use Symfony\Component\Form\Extension\Core\Type\CollectionType;
	use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	
	class KlasType extends AbstractType
	{
		
		public function buildForm(FormBuilderInterface $builder, array $options)
		{
			parent::buildForm($builder, $options);
			$builder
				->add('naam', TextType::class, ['label' => 'Klas naam', 'required' => true])
				->add('location', EntityType::class, [
					
					'label' => 'Klas locatie',
					'class' => Location::class,
					'required' => true,
					'choice_label' => function ($category) {
						return $category->getNaam() . ' - ' . $category->getAdres();
					}
				
				]);
		}
		
		public function configureOptions(OptionsResolver $resolver)
		{
			$resolver->setDefaults([
				'data_class' => Klas::class,
			]);
		}
		
	}