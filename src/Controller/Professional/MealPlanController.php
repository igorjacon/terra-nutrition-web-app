<?php

namespace App\Controller\Professional;

use App\Entity\MealPlan;
use App\Entity\User;
use App\Form\MealPlanType;
use App\Repository\MealPlanRepository;
use App\Utils\Pagination;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/meal-plan', name: 'meal_plan_')]
class MealPlanController extends AbstractController
{
    private TranslatorInterface $translator;
    private ManagerRegistry $doctrine;

    public function __construct(TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $this->translator = $translator;
        $this->doctrine = $doctrine;
    }

    #[Route('/', name: 'index')]
    public function index(Request $request, MealPlanRepository $mealPlanRepository, PaginatorInterface $paginator): Response
    {
        $text = $request->get('search');
        $professional = $this->getUser()->getProfessional();
        if ($professional) {
            $mealPlansQuery = $mealPlanRepository->createQueryBuilder('o')
                ->where('o.professional = :professional')
                ->setParameter('professional', $professional);
        } else {
            $mealPlansQuery = $mealPlanRepository->createQueryBuilder('o');
        }
        if ($text) {
            $mealPlansQuery
                ->andWhere('o.title LIKE :searchText OR o.description LIKE :searchText')
                ->setParameter('searchText', '%' . $text . '%');
        }
        $pagination = $paginator->paginate(
            $mealPlansQuery, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            Pagination::PAGE_LIMIT /*limit per page*/
        );

        if ($request->isXmlHttpRequest()) {
            return $this->render('admin/meal_plans/_search_result.html.twig', [
                'pagination' => $pagination
            ]);
        } else {
            return $this->render('admin/meal_plans/index.html.twig', [
                'pagination' => $pagination,
                'title' => $this->translator->trans('ui.meal_plans')
            ]);
        }
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function newMealPlan(Request $request): Response
    {
        $professional = $this->getUser()->getProfessional();
        $mealPlan = new MealPlan();
        if ($professional) {
            $professional->addMealPlan($mealPlan);
        }
        $form = $this->createForm(MealPlanType::class, $mealPlan);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($mealPlan);
            $em->flush();

            return $this->redirectToRoute('professional_meal_plan_index');
        }

        return $this->render('admin/meal_plans/form.html.twig', [
            'title' => $this->translator->trans('ui.new_meal_plan'),
            'form'  => $form->createView()
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function editMealPlan(Request $request, MealPlan $mealPlan): Response
    {
        $form = $this->createForm(MealPlanType::class, $mealPlan);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($mealPlan);
            $em->flush();

            return $this->redirectToRoute('professional_meal_plan_index');
        }

        return $this->render('admin/meal_plans/form.html.twig', [
            'title' => $mealPlan->getTitle(),
            'form'  => $form->createView()
        ]);
    }

    #[Route('/remove/{id}', name: 'remove', methods: ['GET', 'DELETE'])]
    public function removeMealPlan(Request $request, MealPlan $mealPlan): Response
    {
        if ($request->isMethod('GET')) {
            return $this->render('admin/meal_plans/delete_form.html.twig', [
                'mealPlan' => $mealPlan,
            ]);
        } else {
            if ($this->isCsrfTokenValid('delete' . $mealPlan->getId(), $request->get('_token'))) {
                $em = $this->doctrine->getManager();
                $em->remove($mealPlan);
                $em->flush();
            }

            return $this->redirectToRoute('professional_meal_plan_index');
        }
    }
}