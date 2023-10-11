<?php
 
namespace App\Controller;

use App\Entity\Car;
use App\Repository\CarRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product_show")
     */
    public function show(): Response
    {
        // Trong ví dụ này, chúng ta sử dụng tham số $id để lấy thông tin về sản phẩm từ cơ sở dữ liệu.
        // Sau đó, hiển thị thông tin sản phẩm bằng cách render một view.

        // Lấy thông tin sản phẩm từ cơ sở dữ liệu (điều này cần cài đặt)
        $product = $this->getDoctrine()->getRepository(Car::class)->findAll();

        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
    // src/Controller/HomeController.php
    public function addToOrder($id, CarRepository $carRepository): Response
    {
        $car = $carRepository->find($id);

        if (!$car) {
            throw $this->createNotFoundException('car not found');
        }

        return $this->render('product/order.html.twig', [
            'car' => $car,
        ]);
    }
}
