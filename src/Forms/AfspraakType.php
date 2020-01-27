<?php
	
	
	namespace App\Forms;
	
	
	use App\Entity\Klas;
	use App\Entity\Uitnodiging;
	use DateTime;
	use Doctrine\ORM\EntityRepository;
	use Symfony\Bridge\Doctrine\Form\Type\EntityType;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
	use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
	use Symfony\Component\Form\Extension\Core\Type\DateType;
	use Symfony\Component\Form\Extension\Core\Type\RadioType;
	use Symfony\Component\Form\Extension\Core\Type\TimeType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\HttpFoundation\Session\SessionInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Symfony\Component\Validator\Constraints\Date;
	
	class AfspraakType extends AbstractType
	{
		
		private $session;
		
		public function __construct(SessionInterface $session)
		{
			
			$this->session = $session;
			
		}
		
		public function buildForm(FormBuilderInterface $builder, array $options)
		{
			$uitnodiging = json_decode($this->session->get('invitation'));
			$pickedTimesJson = json_decode($this->session->get('pickedTimes'));
			$leerlingen = json_decode($this->session->get('leerlingen'));
			
			$pickedTimes = [];
			
			for ($i = 0; $i < count($pickedTimesJson); $i++) {
				array_push($pickedTimes, new DateTime($pickedTimesJson[$i]->date));
			}
			
			$stopTimeDateTime = new DateTime($uitnodiging->stopTime->date);
			
			$times = [];
			
			//creates an array of times with interval of 15 minutes between the start time and end time
//            in probably the most inefficient way possible
			
			$i = 0;
			
			do {
				
				if (count($times) > 0) {
					
					$index = count($times) - 1;
					
					$lastTime = new DateTime($stopTimeDateTime->format('Ymd') . $times[$index]->format('His'));
					
					$time = $lastTime->modify('+15 minutes');
					
					if (array_search($time, $pickedTimes) === false) {
						$times[] = $time;
					}
					
					$i++;
					
				} else {
					
					$timeObj = new DateTime($uitnodiging->startTime->date);
					
					while (array_search($timeObj, $pickedTimes) !== false) {
						$timeObj->modify('+15 minutes');
					}
					
					array_push($times, $timeObj);
					
				}
				
			} while ($times[count($times) - 1] < $stopTimeDateTime);
			
			$builder
				->add('time', ChoiceType::class, [
					'choices' => $times,
					'choice_label' => function ($choice, $key, $value) {
						
						return $choice->format('H:i');
					},
					'label' => 'Tijd'
				])
				->add('student_number', ChoiceType::class, [
					'choices' => $leerlingen,
					'choice_label' => function ($choice, $key, $value) {
						
						return $choice->naam . ' - ' . $choice->studentId ;
					}
				])->add('phoneNumber', TextType::class, [
					'label' => 'Telefoonnummer:',
					'required' => true
				])->add('with_parents', CheckboxType::class, [
					'label' => 'Ik kom met mijn ouders',
					'required' => false,
					'help' => 'Studenten onder de 18 zijn verplicht met hun ouders aanwezig te zijn!'
				]);
		}
		
		public function configureOptions(OptionsResolver $resolver)
		{
			$resolver->setDefaults([
				'data_class' => AfspraakType::class,
			]);
		}
		
	}