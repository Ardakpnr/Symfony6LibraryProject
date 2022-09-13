<?php

namespace App\Controller;

use App\Entity\ShopCart;
use App\Form\ShopCartType;
use App\Repository\ShopCartRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/shop/cart')]
class ShopCartController extends AbstractController
{
    #[Route('/', name: 'app_shop_cart_index', methods: ['GET'])]
    public function index(ShopCartRepository $shopCartRepository): Response
    {
        return $this->render('shop_cart/index.html.twig', [
            'shop_carts' => $shopCartRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_shop_cart_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ShopCartRepository $shopCartRepository): Response
    {
        $shopCart = new ShopCart();
        $form = $this->createForm(ShopCartType::class, $shopCart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $shopCartRepository->add($shopCart, true);

            return $this->redirectToRoute('app_shop_cart_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('shop_cart/new.html.twig', [
            'shop_cart' => $shopCart,
            'form' => $form,
        ]);
    }

    
    #[Route('/add', name: 'app_shop_cart_add', methods: ['GET', 'POST'])]
    public function update(Request $request, ManagerRegistry $doctrine, $id): Response
    {
        $entityManager = $doctrine->getManager();
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $conn= $doctrine->getManager()->getConnection();
        $sql = '
            INSERT INTO shop_card (quantity, product_id, user_id)
            VALUES(:quantity, :product_id,:user_id)
        ';
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery([
            'quantity' => $request->get('quantity'),
            'product_id' => $request->get('product_id'),
            'user_id' => $user->getId()
        ]);
        $this->addFlash(
            'success',
            'Ürün başarılı eklendi'
        );
        $route = $request->headers->get('referer'); 
        return $this->redirect($route);

    }



    #[Route('/{id}', name: 'app_shop_cart_show', methods: ['GET'])]
    public function show(ShopCart $shopCart): Response
    {
        return $this->render('shop_cart/show.html.twig', [
            'shop_cart' => $shopCart,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_shop_cart_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ShopCart $shopCart, ShopCartRepository $shopCartRepository): Response
    {
        $form = $this->createForm(ShopCartType::class, $shopCart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $shopCartRepository->add($shopCart, true);

            return $this->redirectToRoute('app_shop_cart_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('shop_cart/edit.html.twig', [
            'shop_cart' => $shopCart,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_shop_cart_delete', methods: ['POST'])]
    public function delete(Request $request, ShopCart $shopCart, ShopCartRepository $shopCartRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$shopCart->getId(), $request->request->get('_token'))) {
            $shopCartRepository->remove($shopCart, true);
        }

        return $this->redirectToRoute('app_shop_cart_index', [], Response::HTTP_SEE_OTHER);
    }
}
