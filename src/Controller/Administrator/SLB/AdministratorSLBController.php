<?php
	
	
	namespace App\Controller\Administrator\SLB;
	
	use App\Classes\Randomizer;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Session\SessionInterface;
	use Symfony\Component\Mailer\Bridge\Google\Transport\GmailSmtpTransport;
	use Symfony\Component\Mailer\Mailer;
	use Symfony\Component\Mime\Email;
	use Symfony\Component\Routing\Annotation\Route;
	use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
	use UserType;
	use App\Entity\User;
	
	class AdministratorSLBController extends AbstractController
	{
		
		private $passwordEncoder;
		private $session;
		
		public function __construct(UserPasswordEncoderInterface $passwordEncoder, SessionInterface $session)
		{
			
			$this->session = $session;
			$this->passwordEncoder = $passwordEncoder;
			
		}
		
		/**
		 * @Route("/adimistrator/slb/nieuw", name="administrator_nieuwe_sbler")
		 */
		public function administratorNieuweSbler(Request $request, EntityManagerInterface $em, Randomizer $randomPassword, SessionInterface $session)
		{
			$form = $this->createForm(UserType::class);
			
			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
				
				$user = $form->getData();
				
				if($this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]) !== NULL){
					$this->addFlash('error', 'Er bestaat al een account met dit email adres');
					
					return $this->redirectToRoute('administrator_nieuwe_sbler');
				}
				
				$user->setRoles(["ROLE_SLB"]);
				
				$randomSetPw = $randomPassword->getRandomPassword();
				
				$user->setPassword($this->passwordEncoder->encodePassword($user  ,$randomSetPw));
				
				try{
					$em->persist($user);
					
					$em->flush();
					
					$email = (new Email())
						->from('')
						->priority(Email::PRIORITY_HIGH)
						->subject('Nieuw account Simply Plan')
						->html('<div style="font-size:10pt;font-family:Segoe UI,sans-serif;">'
							. '<h1 style="font-size:24pt;font-family:Times New Roman,serif;font-weight:bold;margin-right:0;margin-left:0;">Nieuw account Simply Plan</h1>'
							. '<p>Beste ' . $user->getFirstLetter() . ' ' . $user->getLastname() . '</p>'
							. '<p>Hierbij ontvangt u de inloggegevens van uw account voor Simply Plan.</p>'
							. '<table>'
							. '<tr>'
							. '<td style="font-weight: bold">Emailadres:</td>'
							. '<td style="padding-left: 10px">' . $user->getEmail() . '</td>'
							. '</tr>'
							. '<tr>'
							. '<td style="font-weight: bold">Tijdelijk wachtwoord:</td>'
							. '<td style="padding-left: 10px">' . $randomSetPw . '</td>'
							. '</tr>'
							. '</table>'
							. '<p>U kunt nu inloggen op <a href="http://127.0.0.1:8000/">simplyplan.nl</a> om uw wachtwoord te wijzigen.</p>'
							. '<p>Met vriendelijke groet,<br> Administratie '
							. $this->getUser()->getLocation()->getNaam()
							. '</p>'
							. '</div>');
					
					$email->to($user->getEmail());
					
					$transport = new GmailSmtpTransport(NULL, NULL);
					$mailer = new Mailer($transport);
					$mailer->send($email);
					
				}catch (\Exception $e){
					error_log($e->getMessage(),0);
					
					$this->addFlash('error', 'Er ging iets mis tijdens het aanmaken van het account probeer het alstublieft nog eens');
					
					return $this->redirectToRoute('home');
				}
				
				$this->addFlash('success', 'Nieuw SLB account aan gemaakt');
				
				return $this->redirectToRoute('home');
			}
			
			return $this->render('administrator/SLB/administrator_nieuwe_slber.html.twig', [
				
				'form' => $form->createView()
				
			]);
			
		}
		
	}