<?php


namespace App\Controller\Slb\Klas;

use App\Entity\Klas;
use App\Entity\Student;
use App\Forms\StudentType;
use Doctrine\ORM\EntityManagerInterface;
use http\Env\Response;
use KlasType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\Bridge\Google\Transport\GmailSmtpTransport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Routing\Annotation\Route;

class SlbKlasController extends AbstractController
{

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @Route("/slb/klas/nieuw", name="slb_nieuwe_klas")
     */
    public function administratorNieuweKlas(Request $request, EntityManagerInterface $em, SessionInterface $session)
    {

        $form = $this->createForm(KlasType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $klas = $form->getData();
	
            $klas->setSlb($this->getUser());
            
			try{
				$em->persist($klas);
				
				$em->flush();
			}catch (\Exception $e){
				error_log($e->getMessage(),0);
		
				$this->addFlash('error', 'Er ging iets mis tijdens het aanmaken van de klas probeer het alstublieft nog eens');
		
				return $this->redirectToRoute('slb');
			}

            $this->addFlash('success', 'Klas ' . $klas->getNaam() . ' toegevoegd');

            return $this->redirectToRoute('slb');
        }

        return $this->render('slb/Klas/slb_nieuwe_klas.html.twig', [

            'form' => $form->createView()

        ]);

    }

    /**
     * @Route("/slb/klas/studenten", name="slb_studenten")
     */
    public function administratorNieuweLeerling(Request $request, EntityManagerInterface $em)
    {
        $klas = $this->getDoctrine()->getRepository(Klas::class)->findOneBy([

            'id' => $request->get('id')

        ]);

        return $this->render('slb/Klas/slb_studenten.html.twig', [

            'klas' => $klas

        ]);

    }

    /**
     * @Route("/slb/klas/studentVerwijderen", name="slb_student_verwijderen")
     */
    public function administratorLeerlingVerwijderen(Request $request, EntityManagerInterface $em)
    {

        $student = $this->getDoctrine()->getRepository(Student::class)->findOneBy([

            'id' => $request->get('id')

        ]);

        $em->remove($student);

        $em->flush();

        $this->addFlash('success', 'Student ' . $student->getNaam() . ' verwijderd');

        return $this->redirectToRoute('slb_nieuwe_student', array('id' => $request->get('klasId')));

    }

}