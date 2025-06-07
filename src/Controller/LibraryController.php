<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Library as Book;
use App\Repository\LibraryRepository as BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

final class LibraryController extends AbstractController
{
    #[Route('/library', name: 'library')]
    public function index(): Response
    {
        return $this->render('library/index.html.twig');
    }

    #[Route('/library/all', name: 'library_all')]
    public function libraryAll(
        BookRepository $bookRepository
    ): Response {
        $books = $bookRepository
            ->findAll();

        $data = [
            'books' => $books
        ];
        return $this->render('library/show_all.html.twig', $data);
    }

    #[Route('/library/show/{isbn}', name: 'library_show')]
    public function libraryShow(
        BookRepository $bookRepository,
        string $isbn
    ): Response {
        $book = $bookRepository->findOneBy(['isbn' => $isbn]);
        if (!$book) {
            throw $this->createNotFoundException('No book found with ISBN: ' . $isbn);
        }
        return $this->render('library/show_book.html.twig', ['book' => $book]);
    }

    #[Route('/library/form/add', name: 'library_form_add', methods: ['GET'])]
    public function libraryAdd(
    ): Response {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('library_add_post'))
            ->setMethod('POST')
            ->add('title', TextType::class, [
                'label' => 'Titel',
            ])
            ->add('author', TextType::class, [
                'label' => 'Författare',
            ])
            ->add('isbn', TextType::class, [
                'label' => 'ISBN',
            ])
            ->add('img', TextType::class, [
                'label' => 'Bild',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Lägg till bok',
            ])
            ->getForm();
        return $this->render('library/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * C in CRUD. Create a new book.
     */
    #[Route('/library/add', name: 'library_add_post', methods: ['POST'])]
    public function libraryAddPost(
        ManagerRegistry $doctrine,
        Request $request
    ): Response {
        $requestData = $request->request->all();
        $book = new Book();
        $book->setTitle($requestData['form']['title']);
        $book->setAuthor($requestData['form']['author']);
        $book->setIsbn($requestData['form']['isbn']);
        $book->setImg($requestData['form']['img']);
        $entityManager = $doctrine->getManager();
        $entityManager->persist($book);
        $entityManager->flush();
        return $this->redirectToRoute('library_all');
    }

    /**
     * U in CRUD. Update a book.
     */
    #[Route('/library/form/edit/{isbn}', name: 'library_form_edit', methods: ['GET'])]
    public function libraryEdit(
        ManagerRegistry $doctrine,
        string $isbn
    ): Response {
        $book = $doctrine->getRepository(Book::class)->findOneBy(['isbn' => $isbn]);
        if (!$book) {
            throw $this->createNotFoundException('No book found with ISBN: ' . $isbn);
        }
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('library_edit_post', ['isbn' => $isbn]))
            ->setMethod('POST')
            ->add('title', TextType::class, [
                'label' => 'Titel',
                'data' => $book->getTitle(),
            ])
            ->add('author', TextType::class, [
                'label' => 'Författare',
                'data' => $book->getAuthor(),
            ])
            ->add('isbn', TextType::class, [
                'label' => 'ISBN',
                'data' => $book->getIsbn(),
                'disabled' => true,
            ])
            ->add('img', TextType::class, [
                'label' => 'Bild',
                'data' => $book->getImg(),
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Uppdatera bok',
            ])
            ->getForm();
        return $this->render('library/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/library/edit/{isbn}', name: 'library_edit_post', methods: ['POST'])]
    public function libraryEditPost(
        ManagerRegistry $doctrine,
        string $isbn,
        Request $request
    ): Response {
        $requestData = $request->request->all();
        $book = $doctrine->getRepository(Book::class)->findOneBy(['isbn' => $isbn]);
        if (!$book) {
            throw $this->createNotFoundException('No book found with ISBN: ' . $isbn);
        }
        $book->setTitle($requestData['form']['title']);
        $book->setAuthor($requestData['form']['author']);
        $book->setImg($requestData['form']['img']);
        $entityManager = $doctrine->getManager();
        $entityManager->persist($book);
        $entityManager->flush();
        return $this->redirectToRoute('library_all');
    }

    /**
     * D in CRUD. Delete a book.
     */
    #[Route('/library/form/delete/{isbn}', name: 'library_form_delete', methods: ['GET'])]
    public function libraryFormDelete(
        string $isbn
    ): Response {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('library_delete', ['isbn' => $isbn]))
            ->setMethod('POST')
            ->add('isbn', TextType::class, [
                'label' => 'ISBN',
                'data' => $isbn,
                'disabled' => true,
            ])
            ->add('cancel', SubmitType::class, [
                'label' => 'Avbryt',
                'attr' => [
                    'formaction' => $this->generateUrl('library_all')
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ta bort bok',
            ])
            ->getForm();
        return $this->render('library/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/library/delete/{isbn}', name: 'library_delete', methods: ['POST'])]
    public function libraryDelete(
        ManagerRegistry $doctrine,
        string $isbn
    ): Response {
        $book = $doctrine->getRepository(Book::class)->findOneBy(['isbn' => $isbn]);
        if (!$book) {
            throw $this->createNotFoundException('No book found with ISBN: ' . $isbn);
        }
        $entityManager = $doctrine->getManager();
        $entityManager->remove($book);
        $entityManager->flush();
        return $this->redirectToRoute('library_all');
    }
}
