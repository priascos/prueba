<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Form\ProductType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use mikehaertl\wkhtmlto\Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;

class ProductController extends AbstractController
{
    // #[Route('/product', name: 'app_product')]
    // public function index(): Response
    // {
    //     return $this->json([
    //         'message' => 'Welcome to your new controller!',
    //         'path' => 'src/Controller/ProductController.php',
    //     ]);
    // }

    #[Route('/product/index', name: 'index_product')]
    public function index(PaginatorInterface $paginator, Request $request,ManagerRegistry $doctrine)
    {   
        // $repository = $doctrine->getRepository(Product::class);
        // $products = $repository->findAll();

        // Retrieve the entity manager of Doctrine
        $em = $doctrine->getManager();
        
        // Get some repository of data, in our case we have an Appointments entity
        $productRepository = $em->getRepository(Product::class);
                
        // Find all the data on the Appointments table, filter your query as you need
        $products = $productRepository->createQueryBuilder('p')
            ->getQuery();

        $pagination = $paginator->paginate(
            $products, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        return $this->render(
            'product/index.html.twig',[
            'controller_name' => 'Welcome to your new controller!',
            'pagination' => $pagination,
            ]);
    }

    #[Route('/product/new', name: 'new_product')]
    public function new(Request $request, ManagerRegistry $doctrine)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if($form->isSubmitted()&& $form->isValid()){
            $dateImmutable = new \DateTime( 'now',  new \DateTimeZone( 'America/Bogota' ) );
            // var_dump($dateImmutable);
            // $error->getError();
            $em = $doctrine->getManager();
            $product->setCreatedAt($dateImmutable);
            $product->setUpdatedAt($dateImmutable);
            $em->persist($product);
            $em->flush();
            $this->addFlash('exito','se ha registrado exitosamente');
            return $this->redirectToRoute('index_product');
        }

        return $this->render(
            'product/new.html.twig',[
            'controller_name' => 'Welcome to your new controller!',
            'formulario' => $form->createView(),
            ]);
    }

    #[Route('/product/edit', name: 'edit_product')]
    public function edit(Request $request, ManagerRegistry $doctrine)
    {
        $id = $request->query->get('id');
        $em = $doctrine->getManager();
        $product =$em->getRepository(Product::class)->find($id);
        if(!$product){
            throw $this->createdNotFoundException('Product not found');
        }


        $form = $this->createForm(ProductType::class, $product);


        $form->handleRequest($request);

        if($form->isSubmitted()&& $form->isValid()){
            $dateImmutable = new \DateTime( 'now',  new \DateTimeZone( 'America/Bogota' ) );
            // var_dump($dateImmutable);
            // $error->getError();
            $em = $doctrine->getManager();
            $product->setCreatedAt($dateImmutable);
            $product->setUpdatedAt($dateImmutable);
            $em->persist($product);
            $em->flush();
            $this->addFlash('exito','se ha registrado exitosamente');
            return $this->redirectToRoute('index_product');
        }

        return $this->render(
            'product/edit.html.twig',[
            'controller_name' => 'Welcome to your new controller!',
            'form' => $form->createView(),
            ]);
    }

    #[Route('/product/delte', name: 'delete_product')]
    public function delete(Request $request, ManagerRegistry $doctrine)
    {
        $id = $request->query->get('id');
        $em = $doctrine->getManager();
        $product =$em->getRepository(Product::class)->find($id);
        if(!$product){
            throw $this->createdNotFoundException('Product not found');
        }else{
            $em->remove($product);
            $em->flush();
            $this->addFlash('exito','Deleted product');
            return $this->redirectToRoute('index_product');
        }

        return $this->render(
            'product/delete.html.twig',[
            'controller_name' => 'Welcome to your new controller!',
            'form' => $form->createView(),
            ]);
    }

    #[Route('/product/excel', name: 'excel_product')]
    public function excel(Request $request, ManagerRegistry $doctrine)
    {   

        $id = $request->query->get('id');
        $em = $doctrine->getManager();
        $product =$em->getRepository(Product::class)->find($id);

         // Configure Dompdf según sus necesidades
         $pdfOptions = new Options();
         $pdfOptions->set('defaultFont', 'Arial');
         
         // Crea una instancia de Dompdf con nuestras opciones
         $dompdf = new Dompdf($pdfOptions);
         
         // Recupere el HTML generado en nuestro archivo twig
         $html = $this->renderView('product/excel.html.twig', [
             'title' => "Welcome to our PDF Test",
             'product'=> $product
         ]);
         
         // Cargar HTML en Dompdf
         $dompdf->loadHtml($html);
         
         // (Opcional) Configure el tamaño del papel y la orientación 'vertical' o 'vertical'
         $dompdf->setPaper('A4', 'portrait');
 
         // Renderiza el HTML como PDF
         $dompdf->render();
 
         // Envíe el PDF generado al navegador (vista en línea)
         $dompdf->stream("mypdf.pdf", [
             "Attachment" => false
         ]);
    }

}
