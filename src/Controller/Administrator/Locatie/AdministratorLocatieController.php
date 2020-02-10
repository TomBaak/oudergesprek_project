<?php
	
	
	namespace App\Controller\Administrator\Locatie;
	
	
	use App\Entity\Location;
	use App\Entity\Uitnodiging;
	use App\Forms\LocationType;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Routing\Annotation\Route;
	
	class AdministratorLocatieController extends AbstractController
	{
		
		/**
		 * @Route("/adimistrator/locatie/nieuw", name="administrator_locatie_nieuw")
		 */
		public function administratorLocatieNieuw(Request $request, EntityManagerInterface $em)
		{
			$form = $this->createForm(LocationType::class);
			
			$form->handleRequest($request);
			
			if($form->isSubmitted() && $form->isValid()){
				
				$locatie = $form->getData();
				
				$em->persist($locatie);
				$em->flush();
				
				$this->addFlash('success', 'Nieuwe locatie aangemaakt');
				
				return $this->redirectToRoute('administrator');
				
			}
			
			return $this->render('administrator/locatie/locatie.html.twig',[
				
				'form' => $form->createView(),
				'edit' => false
			
			]);
			
		}

        /**
         * @Route("/adimistrator/locatie/wijzigen", name="administrator_locatie_wijzigen")
         */
        public function administratorLocatieWijzigen(Request $request, EntityManagerInterface $em)
        {
            $locatie = $this->getDoctrine()->getRepository(Location::class)->findOneBy(['id' => $request->get('id')]);

            $form = $this->createForm(LocationType::class, $locatie);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){

                $locatie = $form->getData();

                $em->persist($locatie);
                $em->flush();

                $this->addFlash('success', 'Locatie ' . $locatie->getNaam() . ' gewijzigd');

                return $this->redirectToRoute('administrator');

            }

            return $this->render('administrator/locatie/locatie.html.twig',[

                'form' => $form->createView(),
                'edit' => true,
                'locatie' => $locatie

            ]);

        }

		/**
		 * @Route("/adimistrator/locatie/lijst", name="administrator_locatie_lijst")
		 */
		public function administradministratorLocatieLijst()
		{
			
			$locaties = $this->getDoctrine()->getRepository(Location::class)->findAll();
			
			return $this->render('administrator/locatie/locatie_lijst.html.twig',[
				
				'locaties' => $locaties
			
			]);
			
		}
		
	}