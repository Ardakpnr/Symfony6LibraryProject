<?php

namespace App\Controller;

use id;
use DateTime;
use App\Entity\ShopCard;
use App\Form\ShopCartType;
use App\Repository\ShopCardRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/shop/cart')]
class ShopCartController extends AbstractController
{
    #[Route('/', name: 'app_shop_cart_index', methods: ['GET'])]
    public function index(ShopCardRepository $shopCardRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $shop_carts = $shopCardRepository->findBy(['user' => $user->getId()]);

        return $this->render('shop_cart/shopcard.html.twig', [
            'shop_carts' =>  $shop_carts,
        ]);
    }
    #[Route('/order', name: 'app_shop_cart_order', methods:['GET'])]
    public function order(ShopCardRepository $shopCardRepository): Response
    {
        
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $shop_carts = $shopCardRepository->findBy(['user' => $user->getId()]);
        return $this->render('shop_cart/payment.html.twig', [
            'shop_carts' =>  $shop_carts,
        ]);
    }
    #[Route('/complete', name: 'app_shop_cart_complete', methods:['GET', 'POST'])]
    public function complete(ManagerRegistry $doctrine, ShopCardRepository $shopCardRepository,  Request $request): Response
    {
        #kullanıcı bilgileri
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $shop_carts = $shopCardRepository->findBy(['user' => $user->getId()]);
        #ürün bilgileri
        $entityManager = $doctrine->getManager();
        $conn = $doctrine->getManager()->getConnection();
        $sql = '
            INSERT INTO `order` (user_id, `name`, email, address, phone,total, status, ip)
            VALUES(:user_id, :name, :email, :address, :phone, :total, :status, :ip)
        ';
        $stmt = $conn->prepare($sql);
        
        $stmt->executeQuery([
            'user_id' => $user->getId(),
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'address' => $request->get('address'),
            'phone' => $request->get('phone'),
            'total' => $request->get('total'),
            'status' => 'New',
            'ip' => $_SERVER['REMOTE_ADDR'],

        ]);
        

        $orderid=$entityManager->getConnection()->lastInsertId();
        foreach($shop_carts as $rs){
            $sql = '
            INSERT INTO `order_product` (user_id, product_id, orders_id, price, quantity,total)
            VALUES(:user_id, :product_id, :orders_id, :price, :quantity, :total)
        ';
        $stmt = $conn->prepare($sql);
        
        $stmt->executeQuery([
            'user_id' => $user->getId(),
            'product_id' => $rs->getProduct()->getId(),
            'orders_id' => $orderid,
            'price' => $rs->getProduct()->getPrice(),
            'quantity' => $rs->getQuantity(),
            'total' => $rs->getQuantity()*$rs->getProduct()->getPrice()

        ]);
    }
        $sql ='DELETE FROM shop_card WHERE user_id='.$user->getId();
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery();



        return $this->render('shop_cart/complete.html.twig', [
            'shop_carts' =>  $shop_carts,
        ]);
    }


    #[Route('/new', name: 'app_shop_cart_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ShopCardRepository $shopCardRepository): Response
    {
        $shopCard = new ShopCard();
        $form = $this->createForm(ShopCartType::class, $shopCard);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $shopCardRepository->add($shopCard, true);

            return $this->redirectToRoute('app_shop_cart_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('shop_cart/new.html.twig', [
            'shop_cart' => $shopCard,
            'form' => $form,
        ]);
    }


    #[Route('/add', name: 'app_shop_cart_add', methods: ['GET', 'POST'])]
    public function update(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $conn = $doctrine->getManager()->getConnection();
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
    public function show(ShopCard $shopCard): Response
    {
        return $this->render('shop_cart/show.html.twig', [
            'shop_cart' => $shopCard,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_shop_cart_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ShopCard $shopCard, ShopCardRepository $shopCardRepository): Response
    {
        $form = $this->createForm(ShopCartType::class, $shopCard);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $shopCardRepository->add($shopCard, true);

            return $this->redirectToRoute('app_shop_cart_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('shop_cart/edit.html.twig', [
            'shop_cart' => $shopCard,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_shop_cart_delete', methods: ['POST'])]
    public function delete(Request $request, ShopCard $shopCard, ShopCardRepository $shopCardRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $shopCard->getId(), $request->request->get('_token'))) {
            $shopCardRepository->remove($shopCard, true);
        }

        return $this->redirectToRoute('app_shop_cart_index', [], Response::HTTP_SEE_OTHER);
    }


}
