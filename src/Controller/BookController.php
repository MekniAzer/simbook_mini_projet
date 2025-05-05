<?php


namespace App\Controller;

use App\Entity\Livres;
use App\Repository\LivresRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BookController extends AbstractController
{
    #[Route('/books', name: 'app_books')]
    public function index(LivresRepository $livresRepository, Request $request): Response
    {
        $searchQuery = $request->query->get('search', '');
        $category = $request->query->get('category', '');

        // Get books based on status (recommended or recently_added)
        $recommendedBooks = $livresRepository->findBySearchQuery($searchQuery, 'recommended', $category);
        $recentlyAddedBooks = $livresRepository->findBySearchQuery($searchQuery, 'recently_added', $category);

        return $this->render('book/index.html.twig', [
            'recommendedBooks' => $recommendedBooks,
            'recentlyAddedBooks' => $recentlyAddedBooks,
            'search' => $searchQuery,
            'category' => $category, // Pass selected category to the template
        ]);
    }

    #[Route('/books/{id}', name: 'app_books_show')]
    public function show(LivresRepository $livresRepository, int $id): Response
    {
        // Fetch the book based on ID
        $book = $livresRepository->find($id);

        if (!$book) {
            throw $this->createNotFoundException('Book not found');
        }

        return $this->render('book/show.html.twig', ['book' => $book]);
    }
    #[Route('/cart/{id}', name: 'app_books_show2')]
    public function show2(LivresRepository $livresRepository, int $id): Response
    {
        // Fetch the book based on ID
        $book = $livresRepository->find($id);

        if (!$book) {
            throw $this->createNotFoundException('Book not found');
        }

        return $this->render('book/show2.html.twig', ['book' => $book]);
    }

    #[Route('/add-to-cart/{id}', name: 'app_books_add_to_cart')]
    public function addToCart(LivresRepository $livresRepository, int $id, SessionInterface $session): Response
    {
        // Fetch the book from the database
        $book = $livresRepository->find($id);

        // Check if the book exists
        if (!$book) {
            throw $this->createNotFoundException('Book not found');
        }

        // Get the cart from the session
        $cart = $session->get('cart', []);

        // Add the book to the cart (You can customize this logic if needed, e.g., quantity management)
        $cart[] = $book;

        // Save the cart back to the session
        $session->set('cart', $cart);

        return $this->redirectToRoute('app_books');
    }

    #[Route('/cart', name: 'app_cart')]
    public function viewCart(SessionInterface $session): Response
    {
        // Get the cart from the session
        $cart = $session->get('cart', []);

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
        ]);
    }

    #[Route('/remove-from-cart/{id}', name: 'app_books_remove_from_cart')]
    public function removeFromCart(int $id, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);

        // Remove the book with the specified ID from the cart
        $cart = array_filter($cart, fn($book) => $book->getId() !== $id);

        // Reindex the array after removal
        $cart = array_values($cart);

        // Save the updated cart to the session
        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/checkout', name: 'app_checkout')]
    public function checkout(SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);

        if (empty($cart)) {
            return $this->redirectToRoute('app_books'); // Redirect if cart is empty
        }

        // You can add logic for checkout process here

        // For now, just render a checkout page with cart details
        return $this->render('cart/checkout.html.twig', [
            'cart' => $cart,
        ]);
    }

}
