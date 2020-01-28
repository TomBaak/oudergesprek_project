<?php
	
	
	namespace App\Controller\User;
	
	
	use App\Entity\User;
	use App\Forms\UserPasswordType;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Routing\Annotation\Route;
	use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
	use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
	
	class UserController extends AbstractController
	{
		
		/**
		 * @Route("/profiel", name="profiel")
		 */
		public function profiel(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $em)
		{
			$user = $this->getDoctrine()->getRepository(User::class)->findOneBy([
				
				'id' => $this->getUser()->getId()
				
			]);
			
			$form = $this->createForm(UserPasswordType::class);
			
			$form->handleRequest($request);
			
			if($form->isSubmitted() && $form->isValid()){
				
				$result = $form->getData();
				
				if($encoder->isPasswordValid($this->getUser(), $result['oldPassword'])){
					if($result['newPassword'] === $result['newRePassword']){
						
						$user->setPassword($encoder->encodePassword($user, $result['newPassword']));
						
						$em->persist($user);
						
						$em->flush();
						
						$this->addFlash('pwSuccess', 'Wachtwoord gewijzigd');
						
						return $this->redirectToRoute('profiel');
					
					}else{
						$this->addFlash('pwError', 'Het nieuwe wachtwoord is niet gelijk');
						
						return $this->redirectToRoute('profiel');
					}
				}else{
					$this->addFlash('pwError', 'Het huidige wachtwoord is incorrect');
					
					return $this->redirectToRoute('profiel');
				}
				
				
			}
			
			return $this->render('user/profile.html.twig',[
				
				'form' => $form->createView()
				
			]);
			
		}
		
		
	}