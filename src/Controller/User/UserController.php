<?php
	
	
	namespace App\Controller\User;
	
	
	use App\Entity\Location;
	use App\Entity\User;
	use App\Forms\UserPasswordType;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Bridge\Doctrine\Form\Type\EntityType;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
	use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
	use Symfony\Component\Form\Extension\Core\Type\EmailType;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Routing\Annotation\Route;
	use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
	use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
	
	class UserController extends AbstractController
	{
		
		/**
		 * @Route("/user/profiel", name="profiel")
		 */
		public function profiel(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $em)
		{
			$user = $this->getDoctrine()->getRepository(User::class)->findOneBy([
				
				'id' => $this->getUser()->getId()
				
			]);

//			---------- Profile Form -----------------------------------------------------------------------
			
			$formProfile = $this->createFormBuilder($user)
				->add('firstname', TextType::class, [
					'label' => 'Voornaam',
					'required' => true,
				])->add('lastname', TextType::class, [
					'label' => 'Achternaam',
					'required' => true,
				])->add('email', EmailType::class, [
					'label' => 'Email adres',
					'required' => true,
				]);;
			
			if(array_search('ROLE_ADMIN',$user->getRoles()) !== false){
				$formProfile->add('location', EntityType::class, [
					'class' => Location::class,
					'choice_label' => 'naam',
					'label' => 'Locatie',
					'required' => true,
				]);
			}
			
			$formProfile = $formProfile->getForm();
			
			$formProfile->handleRequest($request);
			
			if($formProfile->isSubmitted() && $formProfile->isValid()){
				
				$user = $formProfile->getData();
				
				if($this->getDoctrine()->getRepository(User::class)->findOneBy([
					'email' => $user->getEmail()
				]) !== NULL && $this->getUser()->getEmail() != $user->getEmail()){
					$this->addFlash('error', 'Er bestaat al een profiel met dit email adres');
					
					return $this->redirectToRoute('profiel');
				}else{
					$em->persist($user);
					$em->flush();
					
					$this->addFlash('success', 'Profiel aangepast');
					
					return $this->redirectToRoute('profiel');
				}
				
			}
			
//			---------- Password Form -----------------------------------------------------------------------
			
			$form = $this->createForm(UserPasswordType::class);
			
			$form->handleRequest($request);
			
			if($form->isSubmitted() && $form->isValid()){
				
				$result = $form->getData();
				
				if($encoder->isPasswordValid($this->getUser(), $result['oldPassword'])){
					if($result['newPassword'] === $result['newRePassword']){
						
						$user->setPassword($encoder->encodePassword($user, $result['newPassword']));
						
						try{
							$em->persist($user);
							
							$em->flush();
						}catch (\Exception $e){
							error_log($e->getMessage(),0);
							
							$this->addFlash('pwError', 'Er ging iets mis probeer het alstublief nog eens');
							
							return $this->redirectToRoute('profiel');
						}
						
						
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
				
				'form' => $form->createView(),
				'formProfile' => $formProfile->createView()
				
			]);
			
		}
		
		
	}