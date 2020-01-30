<?php
	
	
	use App\Entity\Klas;
	use App\Entity\LeerlingType;
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
				->add('naam', TextType::class, ['label' => 'Klas naam','required' => true])
				->add('slb', EntityType::class, [
					
					'class' => User::class,
					'query_builder' => function (EntityRepository $er) {
						return $er->createQueryBuilder('u')
							->where('u.isAdmin = 0')
							->orderBy('u.firstname', 'ASC');
					},
					'choice_label' => 'displayname',
					'label' => 'SLBer',
					'required' => true
				
				]);
		}
		
		public function configureOptions(OptionsResolver $resolver)
		{
			$resolver->setDefaults([
				'data_class' => Klas::class,
			]);
		}
		
	}