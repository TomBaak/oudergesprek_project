<?php
	
	namespace App\Controller\Student;
	
	use App\Entity\Afspraak;
	use App\Entity\Student;
	use App\Entity\Uitnodiging;
	use App\Repository\StudentRepository;
	use DateInterval;
	use DatePeriod;
	use DateTime;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Bridge\Doctrine\Form\Type\EntityType;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\Asset\Package;
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
	use Symfony\Component\Validator\Constraints\Choice;
	
	class StudentController extends AbstractController
	{
		
		private $session;
		private $request;
		
		public function __construct(SessionInterface $session)
		{
			
			$this->session = $session;
			setlocale(LC_TIME, 'NL_nl');
			
		}
		
		/**
		 * @Route("/student/afspraak", name="afspraak")
		 */
		public function afspraak(Request $request, EntityManagerInterface $em)
		{
			
			$uitnodiging = $this->getDoctrine()->getRepository(Uitnodiging::class)->findOneBy([
				
				'uitnodigingsCode' => $request->get('id')
			
			]);
			
			if ($uitnodiging === NULL) {
				
				$this->addFlash('error', 'Er ging iets mis probeer het opnieuw. Als dit probleem zich herhaalt neem dan contact op met je SLBer');
				
				return $this->redirectToRoute('home');
				
			}
			
			$pickedTimes = [];
			
			$afspraken = $uitnodiging->getAfspraken();
			
			foreach ($afspraken as $afspraak => $value){
				
				$pickedTimes[] = $value->getTijd();
				
			}
			
			if (count($uitnodiging->getKlas()->getStudents()) == count($pickedTimes)) {
				$this->addFlash('error', 'Er zijn geen plaatsen beschikbaar meer op deze datum. Neem contact op met je SLBer');
				
				return $this->redirectToRoute('home');
			}
			
			$startTime = $uitnodiging->getStartTime();
			$stopTime = $uitnodiging->getStopTime();
			
			$times = [];
			
			//creates an array of times with interval of 15 minutes between the start time and end time
			
			
			$period = new DatePeriod(
				$startTime,
				new DateInterval('PT15M'),
				$stopTime->modify('+15 minutes')
			);
			
			
			
			foreach ($period as $key => $value) {
				if(array_search($value, $pickedTimes) === false){
					$times[] = $value;
				}
			}
			
			$afspraakEmpty = new Afspraak();
			
			$student = $this->getDoctrine()->getRepository(Student::class)->findOneBy([
				
				'emailAdres' => $request->get('student')
			
			]);
			
			
			foreach($afspraken as $afspraak => $value){
				if($value->getStudent() === $student){
					$this->addFlash('error', 'U heeft al een afspraak gemaakt check uw email inbox van email adres: ' . $student->getEmailAdres());
					
					return $this->redirectToRoute('home');
				}
				
			}
			
			$form = $this->createFormBuilder($afspraakEmpty)
				->add('tijd', ChoiceType::class, [
					'choices' => $times,
					'choice_label' => function ($choice, $key, $value) {
						
						return $choice->format('H:i');
					},
					'label' => 'Tijd',
					'required' => true,
					'help' => 'Beschikbare tijden'
				])
				->add('telefoonNummer', TextType::class, [
					'label' => 'Telefoonnummer:',
					'required' => true
				
				])
				->add('metOuders', CheckboxType::class, [
					'label' => 'Ik kom met mijn ouders',
					'required' => false,
					'help' => 'Studenten onder de 18 zijn verplicht met hun ouders aanwezig te zijn'
				])
				->getForm();
			
			$form->handleRequest($request);
			
			if ($form->isSubmitted() && $form->isValid()) {
				
				$afspraak = $form->getData();
				
				$afspraak->setStudent($student);
				
				$gemaakteAfspraak = $this->getDoctrine()->getRepository(Afspraak::class)->findOneBy([
					
					'student' => $afspraak->getStudent()->getId(),
					'uitnodiging' => $uitnodiging
				
				]);
				
				if ($gemaakteAfspraak !== NULL) {
					
					$this->addFlash('error', 'Deze student heeft al een afspraak gemaakt');
					
					return $this->redirectToRoute('afspraak', array('id' => $uitnodiging->getUitnodigingsCode()));
					
				}
				
				$afspraak->setUitnodiging($uitnodiging);
				
				try {
					
					$email = (new Email())
						->from('tomdevelop@gmail.com')
						->priority(Email::PRIORITY_HIGH)
						->subject('Afspraak ouder gesprek')
						->html('<div style="font-size:10pt;font-family:Segoe UI,sans-serif;">'
							. '<h1 style="font-size:24pt;font-family:Times New Roman,serif;font-weight:bold;margin-right:0;margin-left:0;">Afspraakbevestiging ouderavond</h1>'
							. '<p>Geachte heer, mevrouw en beste ' . $student->getNaam() . '</p>'
							. '<p>Hierbij ontvangt u de afspraak bevestiging.</p>'
							. '<table>'
							. '<tr>'
							. '<td style="font-weight: bold">Datum</td>'
							. '<td style="padding-left: 10px">' . strftime('%A %e %B',$uitnodiging->getDate()->format('U')) . '</td>'
							. '</tr>'
							. '<tr>'
							. '<td style="font-weight: bold">Tijd</td>'
							. '<td style="padding-left: 10px">' . $afspraak->getTijd()->format('H:i') . '</td>'
							. '</tr>'
							. '<tr>'
							. '<td style="font-weight: bold">Mentor</td>'
							. '<td style="padding-left: 10px">' . $uitnodiging->getKlas()->getSlb()->getFirstLetter() . ' ' . $uitnodiging->getKlas()->getSlb()->getLastname() . '</td>'
							. '</tr>'
							. '<tr>'
							. '<td style="font-weight: bold">Locatie</td>'
							. '<td style="padding-left: 10px">' . $uitnodiging->getKlas()->getLocation()->getAdres() . '</td>'
							. '</tr>'
							. '</table>'
							. '<p>U mag zich aanmelden bij de administratie van de ' . $uitnodiging->getKlas()->getLocation()->getNaam() . '.</p>'
							. '<p>Met vriendelijke groet,<br> Administratie '
							. $uitnodiging->getKlas()->getLocation()->getNaam()
							. '</p>'
							. '</div>');
					
					$email->addTo($afspraak->getStudent()->getEmailAdres());
					
					$transport = new GmailSmtpTransport('tomdeveloping@gmail.com', 'TDevelop20032002');
					$mailer = new Mailer($transport);
					$mailer->send($email);
					
					$em->persist($afspraak);
					$em->flush();
					
					$this->addFlash('success', 'Afspraak is gemaakt, u kunt de pagina nu verlaten');
					
					return $this->redirectToRoute('home');
				} catch (\Exception $e) {
					error_log($e->getMessage(), 0);
					
					$this->addFlash('error', 'Er ging iets mis tijdens het aanmaken van uw afspraak probeer het alstublieft nog eens');
					
					return $this->redirectToRoute('afspraak', array('id' => $uitnodiging->getUitnodigingsCode()));
				}
				
				
			}
			
			return $this->render('student/student_afspraak.html.twig', [
				
				'uitnodiging' => $uitnodiging,
				'form' => $form->createView(),
				'student' => $student->getNaam() . ' - ' . $student->getEmailAdres()
			
			]);
			
		}
		
	}
