<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Form\BookingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class BookingController extends AbstractController
{
    /**
     * @Route("/ads/{slug}/book", name="booking_create")
     * @IsGranted("ROLE_USER")
     */
    public function book(Ad $ad, Request $request, EntityManagerInterface $manager)
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            
            $booking->setAd($ad);
            $booking->setBooker($this->getUser());
            
            // si les dates ne sont pas disponibles, message d'erreur
            if (!$booking->isBookableDates()){
                $this->addFlash('warning', "Les dates que vous avez saisies ne sont pas disponible !");
            }
            else{
                
                // sino, enregistrer et redirection
                $manager->persist($booking);
                $manager->flush();
                return $this->redirectToRoute("booking_show",["id"=>$booking->getId(), 'success'=>true]);
            }
            
        }
        return $this->render('booking/book.html.twig', [
            'form'  => $form->createView(),
            'ad'    => $ad
        ]);
    }

    /**
     * Undocumented function
     * @Route("/booking/{id}",name="booking_show")
     */
    public function show(Booking $booking)
    {
        return $this->render("booking/show.html.twig", [
            'booking' => $booking
        ]);
    }
}
