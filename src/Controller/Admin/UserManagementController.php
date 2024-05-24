<?php

namespace App\Controller\Admin;

use App\Entity\Customer;
use App\Entity\Professional;
use App\Entity\User;
use App\Form\CustomerType;
use App\Form\ProfessionalType;
use App\Form\UserType;
use App\Repository\CustomerRepository;
use App\Repository\ProfessionalRepository;
use App\Repository\UserRepository;
use App\Service\Mailer;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/user/management', name: 'user_management_')]
class UserManagementController extends AbstractController
{
    private TranslatorInterface $translator;
    private ManagerRegistry $doctrine;

    public function __construct(TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $this->translator = $translator;
        $this->doctrine = $doctrine;
    }

    #[Route('/professionals', name: 'professionals', methods: ['GET'])]
    public function professionals(ProfessionalRepository $professionalRepository): Response
    {
        $professionals = $professionalRepository->findAll();
        return $this->render('admin/professionals/index.html.twig', [
            'professionals' => $professionals,
            'title' => $this->translator->trans('ui.professionals')
        ]);
    }

    #[Route('/professional/new', name: 'new_professional', methods: ['GET', 'POST'])]
    public function newProfessional(Request $request): Response
    {
        $professional = new Professional();
        $form = $this->createForm(ProfessionalType::class, $professional, [
            'role' => User::ROLE_NUTRITIONIST
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($professional);
            $em->flush();

            return $this->redirectToRoute('admin_user_management_professionals');
        }

        return $this->render('admin/professionals/form.html.twig', [
            'form' => $form->createView(),
            'title' => $this->translator->trans('ui.new_professional'),
            'professional' => $professional
        ]);
    }

    #[Route('/professional/edit/{id}', name: 'edit_professional', methods: ['GET', 'POST'])]
    public function editProfessional(Request $request, Professional $professional): Response
    {
        $form = $this->createForm(ProfessionalType::class, $professional);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {

            $em = $this->doctrine->getManager();
            $em->flush();

            return $this->redirectToRoute('admin_user_management_professionals');
        }

        return $this->render('admin/professionals/form.html.twig', [
            'form' => $form->createView(),
            'title' => $professional,
            'professional' => $professional
        ]);
    }

    #[Route('/professional/remove/{id}', name: 'remove_professional', methods: ['GET', 'DELETE'])]
    public function removeProfessional(Request $request, Professional $professional): Response
    {
        if ($request->isMethod('GET')) {
            return $this->render('admin/professionals/delete_form.html.twig', [
                'professional' => $professional,
            ]);
        } else {
            if ($this->isCsrfTokenValid('delete' . $professional->getId(), $request->get('_token'))) {
                $em = $this->doctrine->getManager();

                $em->remove($professional);
                $em->flush();
            }

            return $this->redirectToRoute('admin_user_management_professionals');
        }
    }

    #[Route('/customers', name: 'customers')]
    public function customers(CustomerRepository $customerRepository): Response
    {
        $customers = $customerRepository->findAll();
        return $this->render('admin/customers/index.html.twig', [
            'customers' => $customers,
            'title' => $this->translator->trans('ui.customers')
        ]);
    }

    #[Route('/customer/{id}', name: 'view_customer', methods: ['GET'])]
    public function viewCustomer(Customer $customer): Response
    {
        return $this->render('admin/customers/show.html.twig', [
            'customer' => $customer,
            'title' => $customer
        ]);
    }

    #[Route('/customers/new', name: 'new_customer', methods: ['GET', 'POST'])]
    public function newCustomer(Request $request, Mailer $mailer): Response
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer, [
            'role' => User::ROLE_CUSTOMER
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($customer);
            $em->flush();

            $user = $customer->getUser();
            $mailer->sendEmail('Welcome',
                null,
                $user->getEmail(),
                'email/welcome.html.twig', [
                    'username' => $user->getUsername(),
                    'url_forgotPW' => $this->generateUrl('resetting_request', [], UrlGeneratorInterface::ABSOLUTE_URL),
                ]
            );

            return $this->redirectToRoute('admin_user_management_customers');
        }

        return $this->render('admin/customers/form.html.twig', [
            'title' => $this->translator->trans('ui.new_customer'),
            'customer' => $customer,
            'form'  => $form->createView()
        ]);
    }

    #[Route('/customers/edit/{id}', name: 'edit_customer', methods: ['GET', 'POST'])]
    public function editCustomer(Request $request, Customer $customer): Response
    {
        $form = $this->createForm(CustomerType::class, $customer, [
            'role' => User::ROLE_CUSTOMER
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->flush();

            return $this->redirectToRoute('admin_user_management_customers');
        }

        return $this->render('admin/customers/form.html.twig', [
            'title' => $customer,
            'customer' => $customer,
            'form'  => $form->createView()
        ]);
    }

    #[Route('/customer/remove/{id}', name: 'remove_customer', methods: ['GET', 'DELETE'])]
    public function removeCustomer(Request $request, Customer $customer): Response
    {
        if ($request->isMethod('GET')) {
            return $this->render('admin/customers/delete_form.html.twig', [
                'customer' => $customer,
            ]);
        } else {
            if ($this->isCsrfTokenValid('delete' . $customer->getId(), $request->get('_token'))) {
                $em = $this->doctrine->getManager();

                $em->remove($customer);
                $em->flush();
            }

            return $this->redirectToRoute('admin_user_management_customers');
        }
    }

    #[Route('/admins', name: 'admins', methods: ['GET'])]
    public function administrators(UserRepository $userRepository): Response
    {
        $admins = $userRepository->createQueryBuilder('o')
            ->where('o.roles LIKE :adminRole')
            ->setParameter('adminRole', '%' . User::ROLES_ALLOWED[User::ROLE_ADMIN] . '%')
            ->getQuery()->getResult();

        return $this->render('admin/administrators/index.html.twig', [
            'admins' => $admins,
            'title' => $this->translator->trans('ui.administrators')
        ]);
    }

    #[Route('/admins/new', name: 'new_admin', methods: ['GET', 'POST'])]
    public function newAdmin(Request $request): Response
    {
        $admin = new User();
        $admin->addRole(User::ROLES_ALLOWED[User::ROLE_ADMIN]);
        $form = $this->createForm(UserType::class, $admin, [
            'role' => User::ROLE_ADMIN
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($admin);
            $em->flush();

            return $this->redirectToRoute('admin_user_management_admins');
        }

        return $this->render('admin/administrators/form.html.twig', [
            'form' => $form->createView(),
            'title' => $this->translator->trans('ui.new_admin'),
            'user' => $admin
        ]);
    }

    #[Route('/admin/edit/{id}', name: 'edit_admin', methods: ['GET', 'POST'])]
    public function editAdmin(Request $request, User $admin): Response
    {
        $form = $this->createForm(UserType::class, $admin, [
            'role' => User::ROLE_ADMIN
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {

            $em = $this->doctrine->getManager();
            $em->flush();

            return $this->redirectToRoute('admin_user_management_admins');
        }

        return $this->render('admin/administrators/form.html.twig', [
            'form' => $form->createView(),
            'title' => $admin,
            'user' => $admin
        ]);
    }

    #[Route('/admins/remove/{id}', name: 'remove_admin', methods: ['GET', 'DELETE'])]
    public function removeAdmin(Request $request, User $admin): Response
    {
        if ($request->isMethod('GET')) {
            return $this->render('admin/administrators/delete_form.html.twig', [
                'admin' => $admin,
            ]);
        } else {
            if ($this->isCsrfTokenValid('delete' . $admin->getId(), $request->get('_token'))) {
                $em = $this->doctrine->getManager();

                $em->remove($admin);
                $em->flush();
            }

            return $this->redirectToRoute('admin_user_management_admins');
        }
    }
}
