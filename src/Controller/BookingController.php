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
            // $booking->setCreatedAt(new \DateTime());
            // pour calculer la difference entre deux dates
            // $diff = $booking->getEndDate()->diff($booking->getStartDate());
            // $days=$diff->days;
            // $booking->setAmount($ad->getPrice()*$days);
            $notAvailableDays=[];
            $bookings = $ad->getBookings();
            // dd($bookings);
            foreach ($bookings as $book){
                $resultat=range(
                    $book->getStartDate()->getTimestamp(),
                    $book->getEndDate()->getTimestamp(),
                    24 * 60 * 60 );

                $days=array_map(function($dayTimestamp){
                    return new \DateTime(date("Y-m-d",$dayTimestamp));
                }, $resultat);
                $notAvailableDays= array_merge($notAvailableDays,$days);
            }
            // dd($notAvailableDays);
            $dateReservation = range($booking->getStartDate()->getTimestamp(),
                                    $booking->getEndDate()->getTimestamp(),
                                    24 * 60 * 60
                                );
            
            $myDays = array_map(function($day){
                return new \DateTime(date('Y-m-d', $day));
            }, $dateReservation);

            $transDays = array_map(function($day){
                return $day->format('Y-m-d');
            }, $myDays);
            dd($myDays, $transDays,$notAvailableDays);
            $booking->setAd($ad);
            $booking->setBooker($this->getUser());
            $manager->persist($booking);
            $manager->flush();
            return $this->redirectToRoute("booking_show",["id"=>$booking->getId(), 'success'=>true]);
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
