<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\LibraryRepository as BookRepository;
use Symfony\Component\Routing\Attribute\Route;

class LibraryApiController extends AbstractController
{
    /**
     * API
     */
    #[Route('/api/library/books', name: '/api/library/books', methods: ['GET'])]
    public function apiLibraryBooks(
        BookRepository $bookRepository
    ): Response {
        $books = $bookRepository->findAll();
        return $this->json($books);
    }

    #[Route('/api/library/show/{isbn}', name: '/api/library/show', methods: ['GET'])]
    public function apiLibraryShow(
        BookRepository $bookRepository,
        string $isbn
    ): Response {
        $book = $bookRepository->findOneBy(['isbn' => $isbn]);
        if (!$book) {
            throw $this->createNotFoundException('No book found with ISBN: ' . $isbn);
        }
        return $this->json($book);
    }

    #[Route('/api/library/example', name: 'api_library_show_example', methods: ['GET'])]
    public function apiLibraryShowExample(
    ): Response {
        return $this->redirectToRoute('/api/library/show', ['isbn' => '9789139030201']);
    }
}
