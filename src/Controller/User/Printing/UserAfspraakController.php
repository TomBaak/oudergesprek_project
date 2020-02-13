<?php
	
	
	namespace App\Controller\User\Printing;
	
	
	use App\Entity\Afspraak;
	use App\Entity\Uitnodiging;
	use App\Misc\FPDF;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Routing\Annotation\Route;
	use Symfony\Component\Config\Definition\Exception\Exception;
	
	class UserAfspraakController extends AbstractController
	{
		
		/**
		 * @Route("/print/afspraken", name="print_afspraken")
		 */
		public function test(Request $request)
		{
			
			if ($request->get('id') === NULL) {
				$this->addFlash('error', 'Er is een fout opgetreden probeer het opnieuw');
				
				if (array_search('ROLE_ADMIN', $this->getUser()->getRoles()) !== FALSE) {
					return $this->redirectToRoute('adimistrator');
				} elseif (array_search('ROLE_SLB', $this->getUser()->getRoles()) !== FALSE) {
					return $this->redirectToRoute('slb_uitnodigingen');
				}
			}
			
			$uitnodiging = $this->getDoctrine()->getRepository(Uitnodiging::class)->findOneBy(['id' => $request->get('id')]);
			
			$afspraken = $this->getDoctrine()->getRepository(Afspraak::class)->findBy(['uitnodiging' => $uitnodiging]);
			
			usort($afspraken, function($a, $b){
				
				if($a->getTime() > $b->getTime()){
					return 1;
				}elseif($a->getTime() < $b->getTime()){
					return -1;
				}else{
					return 0;
				}
				
			});
			
			$titel = 'Afspraken oudergesprekken';
			
			$pdf = new FPDF();
			$pdf->SetAuthor('SimplyPlan', true);
			$pdf->SetCreator('SimplyPlan', true);
			$pdf->SetTitle($titel. ' ' . $uitnodiging->getKlas()->getNaam() . ' ' . $uitnodiging->getDate()->format('d-m-Y'), true);
			$pdf->SetSubject($titel. ' ' . $uitnodiging->getKlas()->getNaam() . ' ' . $uitnodiging->getDate()->format('d-m-Y'), true);
			$pdf->AddPage();
			$pdf->Image('img/ROC-logo.png',10,6,30);
			$pdf->SetFont('Arial','B',16);
			$pdf->Cell(80);
			$pdf->Cell(30,10,$titel,0,0,'C');
			$pdf->Ln(15);
			$pdf->SetFont('Arial','B',16);
			$pdf->Cell(30,10,'Informatie',0,0,'L');
			$pdf->Ln();
			$pdf->SetFont('Arial','B',12);
			$pdf->Cell(30,8,'Klas:',1,0,'C');
			$pdf->Cell(60,8,$uitnodiging->getKlas()->getNaam(),1,0,'L');
			$pdf->Ln();
			$pdf->Cell(30,8,'Datum:',1,0,'C');
			$pdf->Cell(60,8,$uitnodiging->getDate()->format('d-m-Y'),1,0,'L');
			$pdf->Ln();
			$pdf->Cell(30,8,'SLBer:',1,0,'C');
			$pdf->Cell(60,8,$uitnodiging->getKlas()->getSLB()->getFirstletter() . ' ' . $uitnodiging->getKlas()->getSLB()->getLastname()  ,1,0,'L');
			$pdf->Ln();
			$pdf->Cell(30,8,'Tijden:',1,0,'C');
			$pdf->Cell(60,8,'Begin: ' . $uitnodiging->getStartTime()->format('H:i') . ' Eind: ' . $uitnodiging->getStopTime()->format('H:i')  ,1,0,'L');
			$pdf->Ln(10);
			$pdf->SetFont('Arial','B',16);
			$pdf->Cell(30,10,'Afspraken',0,0,'L');
			$pdf->SetFont('Arial','B',11);
			$pdf->Ln();
			$pdf->Cell(12,8,'',1,0,'C');
			$pdf->Cell(45,8,'Tijd:',1,0,'C');
			$pdf->Cell(45,8,'Leerling:',1,0,'C');
			$pdf->Cell(45,8,'Alleen of met ouders:',1,0,'C');
			$pdf->Cell(45,8,'Telefoon nummer:',1,0,'C');
			
			for ($i = 0; $i < count($afspraken); $i++){
				$pdf->Ln();
				$pdf->SetFont('Arial','B',11);
				$pdf->Cell(12,7,$i + 1,1,0,'C');
				$pdf->SetFont('Arial','',10);
				$pdf->Cell(45,7,$afspraken[$i]->getTime()->format('H:i'),1,0,'C');
				$pdf->Cell(45,7,$afspraken[$i]->getStudent()->getNaam(),1,0,'C');
				$metOuders = '';
				if($afspraken[$i]->getWithParents()){
					$metOuders = 'Met Ouders';
				}else{
					$metOuders = 'Alleen';
				}
				$pdf->Cell(45,7,$metOuders,1,0,'C');
				$pdf->Cell(45,7,$afspraken[$i]->getPhoneNumber(),1,0,'C');
			}
			
			$pdf->Output();
			
			if (array_search('ROLE_ADMIN', $this->getUser()->getRoles()) !== FALSE) {
				return $this->redirectToRoute('adimistrator');
			} elseif (array_search('ROLE_SLB', $this->getUser()->getRoles()) !== FALSE) {
				return $this->redirectToRoute('slb_uitnodigingen');
			}
			
		}
		
		
	}