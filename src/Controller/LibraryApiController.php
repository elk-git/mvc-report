<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\LibraryRepository as BookRepository;
use Symfony\Component\Routing\Attribute\Route;

final class LibraryApiController extends AbstractController
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
}
