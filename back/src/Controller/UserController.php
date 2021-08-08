<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Adress;
use App\Form\UserType;
use App\Entity\Payment;
use App\Form\AdressType;
use App\Form\PaymentType;
use App\Entity\OrderDetail;
use App\Form\ChangePasswordType;
use App\Repository\UserRepository;
use App\Repository\AdressRepository;
use App\Repository\OrderAdressRepository;
use App\Repository\PaymentRepository;
use App\Repository\OrderDetailRepository;
use App\Repository\OrderItemRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @Route("/profile")
 */
class UserController extends AbstractController
{

    // PAGE INFORMATION

    /**
     * @Route("/{slug}/info", name="user_info", methods={"GET"})
     */
    public function info(User $user): Response
    {
        return $this->render('main/profile/user/info.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="info_edit", methods={"GET","POST"})
     */
    public function infoEdit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_info', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('main/profile/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/delete/{slug}", name="user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $this->container->get('security.token_storage')->setToken(null);
            $entityManager->remove($user);
            $entityManager->flush();
        }
        
        $this->addFlash('success', 'Votre compte utilisateur a bien été supprimé !');
        return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
    }

     /**
     * @Route("/{slug}/change-password", methods="GET|POST", name="change_password")
     */
    public function changePassword(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($hasher->hashPassword($user, $form->get('newPassword')->getData()));

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_logout');
        }

        return $this->render('main/profile/user/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // PAGE ADRESSES

    /**
     * @Route("/{slug}/adress", name="user_adress", methods={"GET"})
     */
    public function adress(User $user): Response
    {
        $adresses = $user->getAdresses();

        return $this->render('main/profile/adress/adress.html.twig', [
            'user' => $user,
            'adresses' => $adresses,
        ]);
    }

    /**
     * @Route("/{slug}/adress/edit", name="adress_edit", methods={"GET","POST"})
     */
    public function adressEdit(Request $request, User $user, Adress $adress, AdressRepository $adressRepository): Response
    {
        $adresses = $user->getAdresses();
        $adress = $adressRepository->findOneBy(['id' => $request->get('id')]);

        $form = $this->createForm(AdressType::class, $adress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_adress', ['slug' => $user->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('main/profile/adress/adress.html.twig', [
            'user' => $user,
            'adresses' => $adresses,
            'form' => $form,
        ]);
    }    

    /**
     * @Route("/{slug}/adress/add", name="adress_add", methods={"GET","POST"})
     */
    public function newAdress(Request $request, User $user): Response
    {
        $adresses = $user->getAdresses();

        $adress = new adress();
        $adress->setUser($user);

        $form = $this->createForm(AdressType::class, $adress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($adress);
            $entityManager->flush();

            return $this->redirectToRoute('user_adress', ['slug' => $user->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('main/profile/adress/adress.html.twig', [
            'user' => $user,
            'adresses' => $adresses,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{slug}/adress/delete", name="adress_delete", methods={"POST", "GET"})
     */
    public function adressDelete(Request $request, User $user, AdressRepository $adressRepository, Adress $adress): Response
    {
        $adress = $adressRepository->findOneBy(['id' => $request->get('id')]);

        if ($this->isCsrfTokenValid('delete'.$adress->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($adress);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_adress', ['slug' => $user->getSlug()], Response::HTTP_SEE_OTHER);
    }

    // PAGE PAYMENT

    /**
     * @Route("/{slug}/payment", name="user_payment", methods={"GET"})
     */
    public function payment(User $user): Response
    {
        $payments = $user->getPayments();

        return $this->render('main/profile/payment/payment.html.twig', [
            'user' => $user,
            'payments' => $payments,
        ]);
    }

    /**
     * @Route("/{slug}/payment/edit", name="payment_edit", methods={"GET","POST"})
     */
    public function paymentEdit(Request $request, User $user, Payment $payment, PaymentRepository $paymentRepository): Response
    {
        $payments = $user->getPayments();
        $payment = $paymentRepository->findOneBy(['id' => $request->get('id')]);

        $form = $this->createForm(PaymentType::class, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_payment', ['slug' => $user->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('main/profile/payment/payment.html.twig', [
            'user' => $user,
            'payments' => $payments,
            'form' => $form,
        ]);
    }    

    /**
     * @Route("/{slug}/payment/add", name="payment_add", methods={"GET","POST"})
     */
    public function newPayment(Request $request, User $user): Response
    {
        $payments = $user->getPayments();

        $payment = new payment();
        $payment->setUser($user);

        $form = $this->createForm(PaymentType::class, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($payment);
            $entityManager->flush();

            return $this->redirectToRoute('user_payment', ['slug' => $user->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('main/profile/payment/payment.html.twig', [
            'user' => $user,
            'payments' => $payments,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{slug}/payment/delete", name="payment_delete", methods={"POST", "GET"})
     */
    public function paymentDelete(Request $request, User $user, PaymentRepository $paymentRepository, Payment $payment): Response
    {
        $payment = $paymentRepository->findOneBy(['id' => $request->get('id')]);

        if ($this->isCsrfTokenValid('delete'.$payment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($payment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_payment', ['slug' => $user->getSlug()], Response::HTTP_SEE_OTHER);
    }

    // PAGE COMMAND

    /**
     * @Route("/{slug}/command", name="command_index", methods={"GET"})
     */
    public function index(OrderDetailRepository $orderDetailRepository, User $user): Response
    {
        $orderDetails = $user->getorderDetails();

        return $this->render('main/profile/command/index.html.twig', [
            'order_details' => $orderDetails,
            'user' => $user,
        ]);
    }


    /**
     * @Route("/{slug}/command/{commandNumber}", name="command_show", methods={"GET"})
     */
    public function show(OrderDetail $orderDetail, OrderAdressRepository $orderAdressRepository, OrderItemRepository $orderItemRepository, ProductRepository $productRepository): Response
    {
        $orderAdress = $orderAdressRepository->findOneBy([
            'status' => 'adresse de livraison',
            'orderDetail' => $orderDetail,
        ]);

        $orderItems = $orderItemRepository->findBy([
            'orderDetail' => $orderDetail,
        ]);

        return $this->render('main/profile/command/show.html.twig', [
            'order_detail' => $orderDetail,
            'orderAdress' => $orderAdress,
            'orderItems' => $orderItems,
        ]);
    }



}
