<?php
	
	
	namespace App\Controller\slb\Invitations;
	
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
	
	class SLBInvitationsController extends AbstractController
	{
		
		/**
		 * @Route("/slb/uitnodiging/nieuw", name="uitnodiging")
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
							
							$email->html('<h1 style="font-weight: bold">Uitnodiging ouder gesprek</h1>' . '<p>U bent uitgenodigd voor een ouder gesprek met meneer/mevrouw '
								. $this->getUser()->getLastName() . ' op ' . $uitnodiging->getDate()->format('j-n-Y') . '</p>'
								. '<p>U kunt met <span><a href="' . $invitationLink . '">deze link</a></span> een afspraak op de door uw gewenste tijd kiezen.</p>'
								. '<br><p>Met vriendelijke groet,</p>' . '<p>SimplyPlan</p>');
							
							$mailer->send($email);
						}
						
					}
					
				} catch (\Exception $e) {
					error_log($e->getMessage(), 0);
					
					$this->addFlash('error', 'Er ging iets mis tijdens het aanmaken van uw uitnodiging probeer het alstublieft nog eens');
					
					return $this->redirectToRoute('slb');
				}
				
				
				$this->addFlash('success', 'Uitnoding is verstuurd naar de studenten');
				
				return $this->redirectToRoute('slb');
			}
			
			return $this->render('slb/invitations/slb_nieuwe_uitnodiging.html.twig', [
				
				'form' => $form->createView()
			
			]);
		}
		
	}