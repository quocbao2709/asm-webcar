<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\CarType;
use App\Repository\CarRepository;
use Exception;
use Monolog\Logger;
use PhpParser\Node\Stmt\TryCatch;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Filesystem\Filesystem;

class CarsController extends AbstractController
{
    /**
     * @Route("/cars", name="car_list")
     */
    public function index(CarRepository $carRepository): Response
    {
        $cars = $carRepository->findAll();

        return $this->render('cars/index.html.twig', [
            'cars' => $cars,
        ]);
    }
    
    /**
     * @Route("/cars/add", name="add_car")
     */
    public function addcar(Request $request, CarRepository $carRepository, LoggerInterface $logger): Response
{
    $newcar = new Car();
    $form = $this->createForm(CarType::class, $newcar);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();

        $imageFile = $form['imageFile']->getData();
        if ($imageFile) {
            // Generate a unique filename for the uploaded image
            $newFileName = uniqid().'.'.$imageFile->guessExtension();
            
            // Specify the target directory
            $targetDirectory = $this->getParameter('image_directory');
            
            // Create an instance of the Filesystem component
            $filesystem = new Filesystem();

            
            // Move the uploaded image to the target directory
            $filesystem->rename($imageFile->getPathname(), $targetDirectory.'/'.$newFileName);

            // Update the Car entity with the new filename
            $newcar->setImage($newFileName);
            $newcar->setImageFile();
        }

        $entityManager->persist($newcar);
        $entityManager->flush();

        return $this->redirectToRoute('home');  
    }

    return $this->render('cars/add.html.twig', [
        'form' => $form->createView(),
    ]);
}
     /**
     * @Route("/cars/{id}", name="view_car")
     */
    public function viewCar($id, CarRepository $carRepository): Response
    {
        $car = $carRepository->find($id);

        if (!$car) {
            throw $this->createNotFoundException('car not found');
        }

        return $this->render('cars/view.html.twig', [
            'car' => $car,
        ]);
    }
    /**
     * @Route("/cars/{id}/delete", name="delete_car")
     */
    public function deleteCar($id, CarRepository $CarRepository): Response
    {
        $car = $CarRepository->find($id);

        if (!$car) {
            throw $this->createNotFoundException('car not found');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($car);
        $entityManager->flush();

        return $this->redirectToRoute('car_list');
    }
        /**
     * @Route("/cars/{id}/edit", name="edit_car")
     */

    /*
    public function editCar($id, CarRepository $CarRepository, Request $request): Response
    {
        $car = $CarRepository->find($id);

        if (!$car) {
            throw $this->createNotFoundException('car not found');
        }

        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('car_list');
        }

        return $this->render('cars/edit.html.twig', [
            'car' => $car,
            'form' => $form->createView(),
        ]);
    }
    */
    public function editCar($id, CarRepository $carRepository, Request $request, LoggerInterface $logger): Response
{
    $car = $carRepository->find($id);

    if (!$car) {
        throw $this->createNotFoundException('Không tìm thấy xe');
    }

    $form = $this->createForm(CarType::class, $car);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();

        $imageFile = $form['imageFile']->getData();
        if ($imageFile) {
            // Generate a unique filename for the uploaded image
            $newFileName = uniqid().'.'.$imageFile->guessExtension();

            // Specify the target directory
            $targetDirectory = $this->getParameter('image_directory');

            // Create an instance of the Filesystem component
            $filesystem = new Filesystem();

            // Move the uploaded image to the target directory
            $filesystem->rename($imageFile->getPathname(), $targetDirectory.'/'.$newFileName);

            // Update the Car entity with the new filename
            $car->setImage($newFileName);
           // Update car properties
            $car->setNamecar($form['namecar']->getData());
            $car->setPrice($form['price']->getData());
            $car->setImageFile();
            
        }

        $entityManager->persist($car);
        $entityManager->flush();

        return $this->redirectToRoute('car_list');
    }

    return $this->render('cars/edit.html.twig', [
        'car' => $car,
        'form' => $form->createView(),
    ]);
}

    
}
