<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController{

    /**
     * Undocumented function
     *
     * @Route("/", name="homepage")
     */
    public function home(){
       
        return $this->render("home.html.twig");
    }

    /**
     * Undocumented function
     *
     * @Route("hello/{nom}", name="hello")
     */
    public function hello($nom){
        return new Response('Hello every one '.$nom);
    }
}
