<?php
	
	
	namespace App\Controller\Administrator\Uitnodigingen;
	
	use App\Classes\Randomizer;
	use App\Entity\Klas;
	use App\Forms\UitnodigingType;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\Asset\Package;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Mailer\Bridge\Google\Transport\GmailSmtpTransport;
	use Symfony\Component\Mime\Email;
	use Symfony\Component\Routing\Annotation\Route;
	use Symfony\Component\Mailer\Mailer;
	use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
	
	class AdministratorUitnodigingController extends AbstractController
	{
		
		/**
		 * @Route("/administrator/uitnodiging/nieuw", name="uitnodiging")
		 */
		public function uitnodiging(Request $request, Randomizer $randomizer, EntityManagerInterface $em)
		{
			$form = $this->createForm(UitnodigingType::class);
			
			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
				
				$uitnodiging = $form->getData();
				
				$uitnodiging->setInvitationCode($randomizer->getInvitationCode(
					
					$uitnodiging->getStartTime()->format('Hi'),
					$uitnodiging->getStopTime()->format('Hi'),
					$uitnodiging->getDate()->format('2mY'),
					$this->getUser()->getId()
				
				));
				
				$klas = $this->getDoctrine()->getRepository(Klas::class)->findOneBy([
					
					'id' => $uitnodiging->getKlas()
				
				]);
				
				$leerlingen = $klas->getStudents();
				
				try {
					$transport = new GmailSmtpTransport('tomdeveloping@gmail.com', 'TDevelop20032002');
					$mailer = new Mailer($transport);
					
					$em->persist($uitnodiging);
					$em->flush();
					
					$email = (new Email())
						->from('tomdevelop@gmail.com')
						->priority(Email::PRIORITY_HIGH)
						->subject('Uitnodiging ouder gesprek');
					
					if (count($klas->getStudents()) === 0) {
						$this->addFlash('error', 'De klas die u heeft gebruikt bevat geen studenten. Probeer het nog eens of neem contact op met de servicedesk');
						
						return $this->redirectToRoute('slb');
					} else {
						for ($i = 0; $i < count($klas->getStudents()); $i++) {
							
							$invitationLink = 'http://127.0.0.1:8000/student/afspraak?id=' . $uitnodiging->getInvitationCode() . '&student=' . $leerlingen[$i]->getStudentId();
							
							$email->to($leerlingen[$i]->getStudentId() . '@student.rocmondriaan.nl');
							
							$email->html('<div style="font-size:10pt;font-family:Segoe UI,sans-serif;">'
								. '<h1 style="font-size:24pt;font-family:Times New Roman,serif;font-weight:bold;margin-right:0;margin-left:0;">Uitnodiging ouderavond</h1>'
								. '<p>Geachte heer, mevrouw en beste ' . $leerlingen[$i]->getNaam()  . '</p>'
								. '<p>Op <span>' . $uitnodiging->getDate()->format('l j F') . '</span>  a.s. willen wij u graag in de gelegenheid stellen het studieverloop'
								. '<br>van ' . $leerlingen[$i]->getNaam() . ' met de studieloopbaanbegeleider, ' . $uitnodiging->getKlas()->getSlb()->getFirstLetter() . ' ' . $uitnodiging->getKlas()->getSlb()->getLastname() . ', te bespreken.</p>'
								. $uitnodiging->getKlas()->getSlb()->getFirstLetter() . ' ' . $uitnodiging->getKlas()->getSlb()->getLastname() . ' is vanaf ' . $uitnodiging->getStartTime()->format('H:i') . ' uur beschikbaar voor individuele gesprekken met een tijdsduur van ca. 15 minuten.'
								. '<p>Wij verzoeken u via <a href="' . $invitationLink . '">deze link</a> een afspraak te maken op het voor u gewenste tijdstip.<br>
									De administratie is bereikbaar voor vragen van maandag t/m vrijdag tussen 08.00 uur tot 17.00 uur op 088 - 666 3360.</p>'
								. '<p>Wij hopen u op ' . $uitnodiging->getDate()->format('l j F') . ' a.s. te mogen begroeten.</p>'
								. '<p>Met vriendelijke groet,</p>'
								. '<p>'
								. $uitnodiging->getKlas()->getLocation()->getDirecteur()
								. '<br> Schooldirecteur School voor Commerciële economie</p>'
								. '</div>');
							
							$mailer->send($email);
						}
						
					}
					
				} catch (\Exception $e) {
					error_log($e->getMessage(), 0);
					
					$this->addFlash('error', 'Er ging iets mis tijdens het aanmaken van uw uitnodiging probeer het alstublieft nog eens');
					
					return $this->redirectToRoute('administrator');
				}
				
				
				$this->addFlash('success', 'Uitnoding is verstuurd naar de studenten');
				
				return $this->redirectToRoute('administrator');
			}
			
			return $this->render('administrator/uitnodigingen/administrator_nieuwe_uitnodiging.html.twig', [
				
				'form' => $form->createView()
			
			]);
		}
		
	}