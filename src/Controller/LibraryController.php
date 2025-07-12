<?php

namespace App\Controller;

use App\Entity\Library;
use App\Repository\LibraryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class LibraryController extends AbstractController
{
    /** Index page */
    #[Route('library/', name: 'app_library')]//1 Active
    public function index(): Response
    {
        return $this->render('library/index.html.twig', [
            'controller_name' => 'Book goblins',
        ]);
    }

    /** [READ ONE] Show one library object */
    #[Route('library/book/{id}', name: 'book_by_id')]//6 Active
    public function showBookById(
        LibraryRepository $libraryRepository,
        int $id
    ): Response {
        $books = $libraryRepository->find($id);

        $data = [
            'books' => $books
        ];

        return $this->render('library/readOne.html.twig', $data);
    }

    /** [READ MANY] See all the library objects */
    #[Route('library/view', name: 'library_view_all')]
    public function viewAllProduct(
        LibraryRepository $libraryRepository
    ): Response {
        $books = $libraryRepository->findAll();

        $data = [
            'books' => $books
        ];

        return $this->render('library/readMany.html.twig', $data);
    }

    /** [CREATE] Create library object routes */
    #[Route('library/create', name: 'library_create_get', methods: ['GET'])]//2 Active
    public function createStart(): Response
    {
        return $this->render('library/create.html.twig');
    }

    #[Route('library/create', name: 'library_create_post', methods: ['POST'])]//3 Active
    public function createLibrary(
        LibraryRepository $libraryRepository,
        ManagerRegistry $doctrine,
        Request $requestBook
    ): Response {
        $entityManager = $doctrine->getManager();
        $isbnTaken = $libraryRepository->findOneBy(['isbn' => (string)$requestBook->request->get('isbn')]);

        if (!$isbnTaken) {
            $book = new Library();
            $book->setTitle((string)$requestBook->request->get('title'));
            $book->setAuthor((string)$requestBook->request->get('author'));
            $book->setCover((string)$requestBook->request->get('cover'));
            $book->setIsbn((string)$requestBook->request->get('isbn'));
            $book->setDescription((string)$requestBook->request->get('description'));

            $entityManager->persist($book);
            $entityManager->flush();
            return $this->redirectToRoute('library_view_all');
        }

        return $this->redirectToRoute('library_create_get');
    }

    /** [UPDATE] Update route */
    #[Route('library/update/{id}', name: 'library_update_get', methods: ['GET'])]
    public function updateStart(
        ManagerRegistry $doctrine,
        int $id
    ): Response {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Library::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }

        $data = [
            'books' => $book
        ];

        return $this->render('library/update.html.twig', $data);
    }

    #[Route('library/update/{id}', name: 'library_update_post', methods: ['POST'])]
    public function updateLibrary(
        LibraryRepository $libraryRepository,
        ManagerRegistry $doctrine,
        Request $requestBook,
        int $id
    ): Response {
        $entityManager = $doctrine->getManager();

        $book = $entityManager->getRepository(Library::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }

        if ($book->getIsbn() != (string)$requestBook->request->get('isbn')) {
            $isbnTaken = $libraryRepository->findOneBy(['isbn' => (string)$requestBook->request->get('isbn')]);
            if ($isbnTaken) {
                return $this->redirectToRoute('library_update_get', ['id' => $id]);
            }
        }

        $book->setTitle((string)$requestBook->request->get('title'));
        $book->setAuthor((string)$requestBook->request->get('author'));
        $book->setCover((string)$requestBook->request->get('cover'));
        $book->setIsbn((string)$requestBook->request->get('isbn'));
        $book->setDescription((string)$requestBook->request->get('description'));

        $entityManager->flush();

        return $this->redirectToRoute('library_update_get', ['id' => $id]);
    }

    /** [DELETE] Delete library object routes */
    #[Route('library/delete', name: 'library_delete_get', methods: ['GET'])]
    public function deleteStart(
        LibraryRepository $libraryRepository
    ): Response {
        $books = $libraryRepository->findAll();

        $data = [
            'books' => $books
        ];
        return $this->render('library/delete.html.twig', $data);
    }

    #[Route('library/delete', name: 'library_delete_post', methods: ['POST'])]
    public function deleteLibraryItemsById(
        Request $requestBook,
        ManagerRegistry $doctrine,
    ): Response {
        $entityManager = $doctrine->getManager();
        $removeBooks = $requestBook->request->all('delete');

        foreach ($removeBooks as $id) {
            $book = $entityManager->getRepository(Library::class)->find($id);

            if ($book) {
                $entityManager->remove($book);
            }
        }

        $entityManager->flush();
        return $this->redirectToRoute('library_view_all');
    }

    /** JSON routes */
    #[Route('api/library/books', name: 'library_show_all_json')]
    public function showAllLibrary(
        LibraryRepository $libraryRepository
    ): Response {
        $library = $libraryRepository->findAll();

        return $this->json($library);
    }

    #[Route('api/library/book/{isbn}', name: 'view_book_by_isbn')]
    public function viewBooksWithMinimumValue(
        LibraryRepository $libraryRepository,
        string $isbn
    ): Response {
        $books = $libraryRepository->findOneBySomeField($isbn);

        $data = [
            'books' => $books
        ];

        return $this->json($books);
    }
}
