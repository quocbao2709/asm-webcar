<?php

namespace App\Controller;

use App\Entity\Car;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $cars = $entityManager->getRepository(Car::class)->findAll();

        return $this->render('home/index.html.twig', [
            'cars' => $cars,
        ]);
    }

}