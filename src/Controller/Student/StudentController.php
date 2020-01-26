<?php


namespace App\Controller\Student;


use App\Entity\Afspraak;
use App\Entity\Uitnodiging;
use App\Forms\AfspraakType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Classes\Utilities as Utils;

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
    public function afspraak(Request $request)
    {

        $uitnodiging = $this->getDoctrine()->getRepository(Uitnodiging::class)->findOneBy([

            'invitationCode' => $request->get('id')

        ]);


        if ($uitnodiging === NULL) {

            $this->addFlash('error', 'Er ging iets mis probeer het opnieuw. Als dit probleem zich herhaalt neem dan contact op met je SLBer');

            return $this->redirectToRoute('home');

        }

        $afspraken = $this->getDoctrine()->getRepository(Afspraak::class)->findBy([

            'uitnodiging' => $uitnodiging

        ]);

        $usedDates = [];

        if ($afspraken !== NULL) {
            if (count($afspraken) !== 0) {
                for ($i = 0; $i < count($afspraken); $i++) {

                     $usedDates[] = $afspraken[$i]->getTime();

                }
            }
        }

        $this->session->set('invitation', json_encode($uitnodiging));
        $this->session->set('invitation', json_encode($usedDates));

        $form = $this->createForm(AfspraakType::class);

        return $this->render('student/student_afspraak.html.twig', [

            'uitnodiging' => $uitnodiging,
            'form' => $form->createView()

        ]);

    }

}