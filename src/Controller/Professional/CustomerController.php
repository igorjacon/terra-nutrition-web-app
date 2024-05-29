<?php

namespace App\Controller\Professional;

use App\Entity\Customer;
use App\Entity\CustomerMeasurement;
use App\Entity\MealPlan;
use App\Entity\Professional;
use App\Entity\User;
use App\Form\CustomerMeasurementType;
use App\Form\CustomerType;
use App\Form\MealPlanType;
use App\Repository\CustomerRepository;
use App\Service\Mailer;
use App\Utils\Pagination;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\PaginatorInterface;
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

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(Request $request, CustomerRepository $customerRepository, PaginatorInterface $paginator): Response
    {
        $text = $request->get('search');
        $professional = $this->getUser()->getProfessional();
        $customerQuery = $customerRepository->createQueryBuilder('o')
            ->leftJoin('o.user', 'u')
            ->leftJoin('u.phones', 'p')
            ->where('o.professional = :professional')
            ->setParameter('professional', $professional);

        if ($text) {
            $customerQuery
                ->andWhere('u.firstName LIKE :searchText OR u.lastName LIKE :searchText OR u.email LIKE :searchText OR p.number LIKE :searchText')
                ->setParameter('searchText', '%' . $text . '%');
        }

        $pagination = $paginator->paginate(
            $customerQuery, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            Pagination::PAGE_LIMIT, /*limit per page*/
            ['wrap-queries' => true]
        );

        if ($request->isXmlHttpRequest()) {
            return $this->render('admin/professionals/customers/_search_result.html.twig', [
                'pagination' => $pagination
            ]);
        } else {
            return $this->render('admin/professionals/customers/index.html.twig', [
                'pagination' => $pagination,
                'title' => $this->translator->trans('ui.customers')
            ]);
        }
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function viewCustomer(Customer $customer): Response
    {
        return $this->render('admin/professionals/customers/show.html.twig', [
            'customer' => $customer,
            'title' => $customer
        ]);
    }

    #[Route('/new/{id}', name: 'new', methods: ['GET', 'POST'])]
    public function newCustomer(Request $request, Professional $professional, Mailer $mailer): Response
    {
        $customer = new Customer();
        $customer->setProfessional($professional);
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

        return $this->render('admin/professionals/customers/form.html.twig', [
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

        return $this->render('admin/professionals/customers/form.html.twig', [
            'title' => $customer,
            'customer' => $customer,
            'form'  => $form->createView()
        ]);
    }

    #[Route('/remove/{id}', name: 'remove', methods: ['GET', 'DELETE'])]
    public function removeCustomer(Request $request, Customer $customer): Response
    {
        if ($request->isMethod('GET')) {
            return $this->render('admin/professionals/customers/delete_form.html.twig', [
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
        $professional = $this->getUser()->getProfessional();
        $mealPlan = new MealPlan();
        $mealPlan->addCustomer($customer);
        $mealPlan->setProfessional($professional);
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

    #[Route('/new/measurement/{id}', name: 'new_measurement', methods: ['GET', 'POST'])]
    public function newMeasurement(Request $request, Customer $customer): Response
    {
        $measurement = new CustomerMeasurement();
        $measurement->setCustomer($customer);

        $form = $this->createForm(CustomerMeasurementType::class, $measurement);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($measurement);
            $em->flush();

            return $this->redirectToRoute('professional_customer_show', [
                'id' => $customer->getId()
            ]);
        }

        return $this->render('admin/professionals/customers/measurements/form.html.twig', [
            'title' => $this->translator->trans('ui.new_measurement'),
            'customer' => $customer,
            'form'  => $form->createView()
        ]);
    }

    #[Route('/measurement/{id}', name: 'edit_measurement', methods: ['GET', 'POST'])]
    public function editMeasurement(Request $request, CustomerMeasurement $customerMeasurement): Response
    {
        $form = $this->createForm(CustomerMeasurementType::class, $customerMeasurement);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($customerMeasurement);
            $em->flush();

            return $this->redirectToRoute('professional_customer_show', [
                'id' => $customerMeasurement->getCustomer()->getId()
            ]);
        }

        return $this->render('admin/professionals/customers/measurements/form.html.twig', [
            'title' => substr($customerMeasurement->getDescription(),0, 50) . '...',
            'customer' => $customerMeasurement->getCustomer(),
            'form'  => $form->createView()
        ]);
    }

    #[Route('/remove/measurement/{id}', name: 'remove_measurement', methods: ['GET', 'DELETE'])]
    public function removeMeasurement(Request $request, CustomerMeasurement $customerMeasurement): Response
    {
        $customer = $customerMeasurement->getCustomer();
        if ($request->isMethod('GET')) {
            return $this->render('admin/professionals/customers/measurements/delete_form.html.twig', [
                'measurement' => $customerMeasurement,
            ]);
        } else {
            if ($this->isCsrfTokenValid('delete' . $customerMeasurement->getId(), $request->get('_token'))) {
                $em = $this->doctrine->getManager();
                $em->remove($customerMeasurement);
                $em->flush();
            }

            return $this->redirectToRoute('professional_customer_show', [
                'id' => $customer->getId()
            ]);
        }
    }
}