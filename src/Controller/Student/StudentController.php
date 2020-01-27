<?php
	
	
	namespace App\Controller\Student;
	
	use App\Entity\Afspraak;
	use App\Entity\Uitnodiging;
	use App\Forms\AfspraakType;
	use DateTime;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
	use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Session\SessionInterface;
	use Symfony\Component\Mailer\Bridge\Google\Transport\GmailSmtpTransport;
	use Symfony\Component\Mailer\Mailer;
	use Symfony\Component\Mime\Email;
	use Symfony\Component\Routing\Annotation\Route;
	use App\Classes\Utilities as Utils;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	
	class StudentController extends AbstractController
	{
		
		private $session;
		
		public function __construct(SessionInterface $session)
		{
			
			$this->session = $session;
			
		}
		
		/**
		 * @Route("/student/afspraak", name="afspraak")
		 */
		public function afspraak(Request $request, EntityManagerInterface $em)
		{
			
			$uitnodiging = $this->getDoctrine()->getRepository(Uitnodiging::class)->findOneBy([
				
				'invitationCode' => $request->get('id')
			
			]);
			
			
			if ($uitnodiging === NULL) {
				
				$this->addFlash('error', 'Er ging iets mis probeer het opnieuw. Als dit probleem zich herhaalt neem dan contact op met je SLBer');
				
				return $this->redirectToRoute('home');
				
			}
			
			$pickedTimes = [];
			
			$afpraken = $uitnodiging->getAfspraken();
			
			for ($i = 0; $i < count($afpraken); $i++) {
				
				if (array_search($afpraken[$i], $pickedTimes) === false) {
					array_push($pickedTimes, $afpraken[$i]->getTime());
				}
				
			}
			
			$leerlingen = $uitnodiging->getKlas()->getLeerlingen();
			
			$times = [];
			
			//creates an array of times with interval of 15 minutes between the start time and end time
//            in probably the most inefficient way possible
			
			$i = 0;
			
			do {
				
				if (count($times) > 0) {
					
					$index = count($times) - 1;
					
					$lastTime = new DateTime($uitnodiging->getStopTime()->format('Ymd') . $times[$index]->format('His'));
					
					$time = $lastTime->modify('+15 minutes');
					
					if (array_search($time, $pickedTimes) === false) {
						$times[] = $time;
					}
					
					$i++;
				} else {
					
					$timeObj = new DateTime($uitnodiging->getStartTime()->format('H:i'));
					
					while (array_search($timeObj, $pickedTimes) !== false) {
						$timeObj->modify('+15 minutes');
					}
					
					array_push($times, $timeObj);
					
				}
				
			} while ($times[count($times) - 1] < $uitnodiging->getStopTime());
			
			$afspraakEmpty = new Afspraak();
			
			$form = $this->createFormBuilder($afspraakEmpty)
				->add('time', ChoiceType::class, [
					'choices' => $times,
					'choice_label' => function ($choice, $key, $value) {
						
						return $choice->format('H:i');
					},
					'label' => 'Tijd'
				])
				->add('student_number', ChoiceType::class, [
					'choices' => $leerlingen,
					'choice_value' =>function ($choice) {
						dd($choice->studentId);
						return $choice->studentId;
					},
					'choice_label' => function ($choice) {
						return $choice->naam . ' - ' . $choice->studentId;
					}
				])
				->add('phoneNumber', TextType::class, [
					'label' => 'Telefoonnummer:',
					'required' => true
					
				])
				->add('with_parents', CheckboxType::class, [
					'label' => 'Ik kom met mijn ouders',
					'required' => false,
					'help' => 'Studenten onder de 18 zijn verplicht met hun ouders aanwezig te zijn!'
				])
				->getForm();
			
			if (count($uitnodiging->getKlas()->getLeerlingen()) == count($pickedTimes)) {
				$this->addFlash('error', 'Er zijn meer plaatsen beschikbaar op deze datum. Neem contact op met je SLBer');
				
				return $this->redirectToRoute('home');
			}
			
			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
				
				$afspraak = $form->getData();
				
				dd($afspraak);
				
				$this->addFlash('success', 'Afspraak is gemaakt, u kunt de pagina nu verlaten');
				
				$em->persist($afspraak);
				$em->flush();
				
				$email = (new Email())
					->from('tomdevelop@gmail.com')
					->priority(Email::PRIORITY_HIGH)
					->subject('Uitnodiging ouder gesprek')
					->html('<h1 style="font-weight: bold">Afspraak ouder gesprek</h1>' . '<p>U heeft een afspraak gemaakt op '
						. $uitnodiging->getDate->format('d M Y') . ' om ' . $afspraak->getTime()->format('H:i')
						. 'met meneer/mevrouw' . $uitnodiging->getKlas()->getSLB()->getLastname());
				
				$email->addTo($afspraak->getStudentNumber() . '@student.rocmondriaan.nl');
				
				$transport = new GmailSmtpTransport('tomdeveloping@gmail.com', 'TDevelop20032002');
				$mailer = new Mailer($transport);
				$mailer->send($email);
				
				return $this->redirectToRoute('home');
				
			}
			
			
			return $this->render('student/student_afspraak.html.twig', [
				
				'uitnodiging' => $uitnodiging,
				'form' => $form->createView()
			
			]);
			
		}
		
	}