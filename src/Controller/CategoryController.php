<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Form\SubmitType;
use App\Controller\ManagerRegistry;


class CategoryController extends AbstractController
{
    #[Route('/category', name: 'cateory')]
    public function index()
    {

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);


        return $this->render(
            'category/index.html.twig',[
            'controller_name' => 'Welcome to your new controller!',
            'formulario' => $form->createView(),
            ]);
    }
    
    // public function createCategory(ManagerRegistry $doctrine): Response
    // {
    //     $entityManager = $doctrine->getManager();

    //     $dateImmutable = \DateTime::createFromFormat('Y-m-d H:i:s', strtotime('now'));

    //     $category = new Category();
    //     $category->setName('Keyboard');
    //     $category->setActive(1);
    //     $category->setCreateAt($dateImmutable);
    //     $category->setUpdateAt($dateImmutable);

    //     // tell Doctrine you want to (eventually) save the Category (no queries yet)
    //     $entityManager->persist($category);

    //     // actually executes the queries (i.e. the INSERT query)
    //     $entityManager->flush();

    //     return new Response('Saved new category with id '.$prodcategoryuct->getId());
    // }

    
    // public function new(Request $request): Response
    // {
    //     // creates a task object and initializes some data for this example
    //     $dateImmutable = \DateTime::createFromFormat('Y-m-d H:i:s', strtotime('now'));

    //     $category = new Category();
    //     $category->setName('Keyboard');
    //     $category->setActive(1);
    //     $category->setCreateAt($dateImmutable);
    //     $category->setUpdateAt($dateImmutable);

    //     $form = $this->createFormBuilder($task)
    //         ->add('name', TextType::class)
    //         ->add('active', IntegerType::class)
    //         ->add('create_at', DateTimeType::class)
    //         ->add('update_at', DateTimeType::class)
    //         ->add('save', SubmitType::class, ['label' => 'Create Task'])
    //         ->getForm();

    // }

    
    // public function number(): Response
    // {
    //     $number = random_int(0, 100);

    //     return $this->render('category/new.html.twig', [
    //         'number' => $number,
    //     ]);
    // }
    


}
