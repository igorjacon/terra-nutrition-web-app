<?php

namespace App\Controller\Professional;

use App\Entity\Customer;
use App\Entity\MealPlan;
use App\Entity\User;
use App\Form\CustomerType;
use App\Form\MealPlanType;
use App\Repository\CustomerRepository;
use App\Service\Mailer;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/customers', name: 'customer_')]
class CustomerController extends AbstractController
{
    private TranslatorInterface $translator;
    private ManagerRegistry $doctrine;

    public function __construct(TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $this->translator = $translator;
        $this->doctrine = $doctrine;
    }

    #[Route('/', name: 'index')]
    public function index(CustomerRepository $customerRepository): Response
    {
        $professional = $this->getUser()->getProfessional();
        $customers = $customerRepository->findBy(['professional' => $professional]);
        return $this->render('admin/customers/index.html.twig', [
            'customers' => $customers,
            'title' => $this->translator->trans('ui.customers')
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function viewCustomer(Customer $customer): Response
    {
        return $this->render('admin/customers/show.html.twig', [
            'customer' => $customer,
            'title' => $customer
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
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

            return $this->redirectToRoute('professional_customer_index');
        }

        return $this->render('admin/customers/form.html.twig', [
            'title' => $this->translator->trans('ui.new_customer'),
            'customer' => $customer,
            'form'  => $form->createView()
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function editCustomer(Request $request, Customer $customer): Response
    {
        $form = $this->createForm(CustomerType::class, $customer, [
            'role' => User::ROLE_CUSTOMER
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->flush();

            return $this->redirectToRoute('professional_customer_index');
        }

        return $this->render('admin/customers/form.html.twig', [
            'title' => $customer,
            'customer' => $customer,
            'form'  => $form->createView()
        ]);
    }

    #[Route('/remove/{id}', name: 'remove', methods: ['GET', 'DELETE'])]
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

            return $this->redirectToRoute('professional_customer_index');
        }
    }

    #[Route('/new/meal-plan/{id}', name: 'new_meal_plan', methods: ['GET', 'POST'])]
    public function newMealPlan(Request $request, Customer $customer): Response
    {
        $mealPlan = new MealPlan();
        $mealPlan->addCustomer($customer);
        $form = $this->createForm(MealPlanType::class, $mealPlan);
        $form->remove('customers');
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($mealPlan);
            $em->flush();

            return $this->redirectToRoute('professional_customer_show', [
                'id' => $customer->getId()
            ]);
        }

        return $this->render('admin/customers/meal_plans/meal_plan.html.twig', [
            'title' => $this->translator->trans('ui.new_meal_plan'),
            'customer' => $customer,
            'form'  => $form->createView()
        ]);
    }

    #[Route('/meal-plan/{id}/{customer}', name: 'meal_plan', methods: ['GET', 'POST'])]
    public function editMealPlan(Request $request, MealPlan $mealPlan, Customer $customer): Response
    {
        $form = $this->createForm(MealPlanType::class, $mealPlan);
        $form->remove('customers');
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($mealPlan);
            $em->flush();

            return $this->redirectToRoute('professional_customer_show', [
                'id' => $customer->getId()
            ]);
        }

        return $this->render('admin/customers/meal_plans/meal_plan.html.twig', [
            'title' => $mealPlan->getTitle(),
            'customer' => $customer,
            'form'  => $form->createView()
        ]);
    }

    #[Route('/remove/meal-plan/{id}/{customer}', name: 'remove_meal_plan', methods: ['GET', 'DELETE'])]
    public function removeMealPlan(Request $request, MealPlan $mealPlan, Customer $customer): Response
    {
        if ($request->isMethod('GET')) {
            return $this->render('admin/customers/meal_plans/delete_form.html.twig', [
                'mealPlan' => $mealPlan,
                'customer' => $customer
            ]);
        } else {
            if ($this->isCsrfTokenValid('delete' . $mealPlan->getId(), $request->get('_token'))) {
                $em = $this->doctrine->getManager();
                $em->remove($mealPlan);
                $em->flush();
            }

            return $this->redirectToRoute('professional_customer_show', [
                'id' => $customer->getId()
            ]);
        }
    }
}