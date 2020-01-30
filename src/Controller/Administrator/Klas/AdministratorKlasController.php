<?php


namespace App\Controller\Administrator\Klas;

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

class AdministratorKlasController extends AbstractController
{

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @Route("/adimistrator/klas/nieuw", name="administrator_nieuwe_klas")
     */
    public function administratorNieuweKlas(Request $request, EntityManagerInterface $em, SessionInterface $session)
    {

        $form = $this->createForm(KlasType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $klas = $form->getData();
	
			try{
				$em->persist($klas);
				
				$em->flush();
			}catch (\Exception $e){
				error_log($e->getMessage(),0);
		
				$this->addFlash('error', 'Er ging iets mis tijdens het aanmaken van de klas probeer het alstublieft nog eens');
		
				return $this->redirectToRoute('slb');
			}

            $this->addFlash('success', 'Klas ' . $klas->getNaam() . ' toegevoegd');

            return $this->redirectToRoute('administrator');
        }

        return $this->render('administrator/Klas/administrator_nieuwe_klas.html.twig', [

            'form' => $form->createView()

        ]);

    }

    /**
     * @Route("/adimistrator/klas/nieuweStudent", name="administrator_nieuwe_student")
     */
    public function administratorNieuweLeerling(Request $request, EntityManagerInterface $em)
    {
        $klas = $this->getDoctrine()->getRepository(Klas::class)->findOneBy([

            'id' => $request->get('id')

        ]);

        $form = $this->createForm(StudentType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $student = $form->getData();

            $student->setKlas($klas);
	
	
	
			try{
				$em->persist($student);
				$em->flush();
			}catch (\Exception $e){
				error_log($e->getMessage(),0);
		
				$this->addFlash('error', 'Er ging iets mis tijdens het aanmaken van de student probeer probeer het alstublieft nog eens');
		
				return $this->redirectToRoute('slb');
			}

            $this->addFlash('success', 'Student ' . $student->getNaam() . ' toegevoegd');

            return $this->redirectToRoute('administrator_nieuwe_student', array('id' => $request->get('id')));
        }

        return $this->render('administrator/Klas/administrator_nieuwe_student.html.twig', [

            'klas' => $klas,
            'form' => $form->createView()

        ]);

    }

    /**
     * @Route("/adimistrator/klas/studentVerwijderen", name="administrator_student_verwijderen")
     */
    public function administratorLeerlingVerwijderen(Request $request, EntityManagerInterface $em)
    {

        $student = $this->getDoctrine()->getRepository(Student::class)->findOneBy([

            'id' => $request->get('id')

        ]);

        $em->remove($student);

        $em->flush();

        $this->addFlash('success', 'Student ' . $student->getNaam() . ' verwijderd');

        return $this->redirectToRoute('administrator_nieuwe_student', array('id' => $request->get('klasId')));

    }

}