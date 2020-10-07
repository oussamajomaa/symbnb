<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{

    /**
     * Permet d'ajouter une annonce et rediriger vers l'annonce ajouté
     * 
     * @Route("/ads/new", name="ads_create")
     *
     */
    public function create(Request $request, EntityManagerInterface $manager)
    {
        $ad = new Ad(); 
        $form=$this->createForm(AdType::class, $ad);
        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid()){
            foreach($ad->getImages() as $image){
                $image->setAd($ad);
                $manager->persist($image);
            }
            $ad->setAuthor($this->getUser());
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                "success",
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrée"
            );
            return $this->redirectToRoute('ads_show', [
                'slug'=>$ad->getSlug()
            ]);
        }

        return $this->render('ad/new.html.twig',[
            'form'  => $form->createView()
            
        ]);
    }

    /**
     * permet d'éditer une annonce
     *
     * @Route("/ads/{slug}/edit",name="ad_edit")
     */
    public function edit(Ad $ad, Request $request, EntityManagerInterface $manager)
    {
        // $ad=$repo->findOneBySlug($slug);
        $form=$this->createForm(AdType::class,$ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                "warning",
                "Les modification de l'annonce <strong>{$ad->getTitle()}</strong> ont bien été enregistrées"
            );
            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }
        return $this->render("ad/edit.html.twig", [
            "form"=>$form->createView(),
            'ad'    => $ad
        ]);
    }
    
    /**
     * permet d'afficher toutes les annonces
     * 
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo)
    {
        return $this->render('ad/index.html.twig', [
            'ads' => $repo->findAll()
        ]);
    }

    /**
     * permet de trouver et d'afficher une seule annonce par son slug
     * 
     * @Route("/ads/{slug}", name="ads_show")
     */
    
    public function show(Ad $ad)
    {
        // $ad=$repo->findOneBySlug($slug);
        return $this->render('ad/show.html.twig', [
            'ad' => $ad
        ]);
    }


    

}
