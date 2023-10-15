<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\CarType;
use App\Repository\CarRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



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
    public function addcar(Request $request): Response
    {
        $newcar = new Car();
        $form = $this->createForm(CarType::class, $newcar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $imageFile = $form['imageFile']->getData();
            if ($imageFile) {
                // Thực hiện lưu trữ tệp ảnh và cập nhật đối tượng Car
                $newFileName = uniqid().'.'.$imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('image_directory'), // Thư mục lưu trữ hình ảnh
                    $newFileName
                );
                $newcar->setImage($newFileName);
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
    
}
