<?php

namespace App\Controller\Admin;

use App\Entity\Customer;
use App\Entity\Professional;
use App\Entity\User;
use App\Form\ProfessionalType;
use App\Repository\CustomerRepository;
use App\Repository\ProfessionalRepository;
use Cassandra\Custom;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
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

    #[Route('/professionals', name: 'professionals')]
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
            'title' => $this->translator->trans('ui.new_professional')
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
            'title' => $this->translator->trans('ui.edit_professional')
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
            'title' => $this->translator->trans('ui.customers')
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
}
