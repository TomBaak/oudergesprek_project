<?php
	
	
	use App\Entity\Klas;
	use App\Entity\User;
	use Symfony\Bridge\Doctrine\Form\Type\EntityType;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	
	class UserType extends AbstractType
	{
		
		public function buildForm(FormBuilderInterface $builder, array $options)
		{
			parent::buildForm($builder, $options);
			$builder
				->add('email', TextType::class, ['label' => 'Email','required' => true])
				->add('firstname', TextType::class, ['label' => 'Voornaam','required' => true] )
				->add('lastname', TextType::class, ['label' => 'Achternaam','required' => true]);
		}
		
		public function configureOptions(OptionsResolver $resolver)
		{
			$resolver->setDefaults([
				'data_class' => User::class,
			]);
		}
		
	}