<?php


    namespace App\Controller\Student;


    use App\Entity\Uitnodiging;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;

    class StudentController extends AbstractController
    {
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

            return $this->render('student/student_afspraak.html.twig',[

                'uitnodiging' => $uitnodiging

            ]);

        }

    }