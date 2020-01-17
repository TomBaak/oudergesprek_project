<?php
	
	
	namespace App\Forms;
	
	
	use App\Entity\Klas;
	use App\Entity\Uitnodiging;
	use Doctrine\ORM\EntityRepository;
	use Symfony\Bridge\Doctrine\Form\Type\EntityType;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\Extension\Core\Type\DateType;
	use Symfony\Component\Form\Extension\Core\Type\TimeType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\HttpFoundation\Session\SessionInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	
	class AfspraakType extends AbstractType
	{
		
		private $session;
		
		public function __construct(SessionInterface $session)
		{
			
			$this->session = $session;
			
		}
		
		public function buildForm(FormBuilderInterface $builder, array $options)
		{
			dd(json_decode($this->session->get('latestInvit')));
			
			$builder
				->add('start_time', TimeType::class, ['label' => 'Begin tijd'])
				->add('stop_time', TimeType::class, ['label' => 'Eind tijd']);
		}
		
		public function configureOptions(OptionsResolver $resolver)
		{
			$resolver->setDefaults([
				'data_class' => AfspraakType::class,
			]);
		}
	
	}