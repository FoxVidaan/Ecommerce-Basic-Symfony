<?php

namespace App\Controller\Purchase;

use App\Cart\CartService;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use App\Entity\User;
use App\Form\CartConfirmationType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;

class PurchaseConfirmationController extends AbstractController
{
    protected $cartService;
    protected $manager;

    public function __construct(CartService $cartService, EntityManagerInterface $manager)
    {
        $this->cartService = $cartService;
        $this->manager = $manager;
    }

    /**
     * @Route(path="/purchase/confirm", name="purchase_confirm")
     * @IsGranted("ROLE_USER", message="Vous devez être connecté pour confirmer une commande")
     */
    public function confirm(Request $request)
    {
        $form = $this->createForm(CartConfirmationType::class);

        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            $this->addFlash('warning', 'Vous devez remplir le formulaire de confirmation');
            return $this->redirectToRoute('cart_show');
        }

        /** @var User $user */
        $user = $this->getUser();

        $cartItems = $this->cartService->getDetailCartItems();

        if (count($cartItems) === 0) {
            $this->addFlash('warning', 'Vous ne pouvez pas confirmer une commande avec un panier vide');
            return $this->redirectToRoute('cart_show');
        }

        /** @var Purchase $purchase */
        $purchase = $form->getData();

        $purchase
            ->setUser($user)
            ->setPurchasedAt(new \DateTime())
            ->setTotal($this->cartService->getTotal());

        $this->manager->persist($purchase);

        foreach ($this->cartService->getDetailCartItems() as $cartItem) {
            $purchaseItem = new PurchaseItem();
            $purchaseItem
                ->setPurchase($purchase)
                ->setProduct($cartItem->product)
                ->setQuantity($cartItem->qty)
                ->setTotal($cartItem->getTotal())
                ->setProductName($cartItem->product->getName())
                ->setProductPrice($cartItem->product->getPrice());

            $this->manager->persist($purchaseItem);
        }

        $this->manager->flush();

        $this->cartService->empty();

        $this->addFlash('success', "C'est good");
        return $this->redirectToRoute('purchase_index');
    }
}