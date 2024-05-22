<?php

namespace App\Controller\Professional;

use App\Entity\MealOption;
use App\Form\OptionMealType;
use App\Repository\MealOptionRepository;
use App\Utils\Pagination;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/meal-option', name: 'meal_option_')]
class MealOptionController extends AbstractController
{
    private TranslatorInterface $translator;
    private ManagerRegistry $doctrine;

    public function __construct(TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $this->translator = $translator;
        $this->doctrine = $doctrine;
    }

    #[Route('/', name: 'index')]
    public function index(Request $request, MealOptionRepository $mealOptionRepository, PaginatorInterface $paginator): Response
    {
        $professional = $this->getUser()->getProfessional();
        if ($professional) {
            $mealPlansQuery = $mealOptionRepository->createQueryBuilder('o')
                ->leftJoin('o.meals', 'meals')
                ->leftJoin('meals.mealPlans', 'mealPlans')
                ->where('mealPlans.professional = :professional')
                ->orWhere('o.professional = :professional')
                ->setParameter('professional', $professional);
        } else {
            $mealPlansQuery = $mealOptionRepository->createQueryBuilder('o');
        }
        $pagination = $paginator->paginate(
            $mealPlansQuery, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            Pagination::PAGE_LIMIT /*limit per page*/
        );
        return $this->render('admin/meal_option/index.html.twig', [
            'pagination' => $pagination,
            'title' => $this->translator->trans('ui.meals')
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function newMealOption(Request $request): Response
    {
        $professional = $this->getUser()->getProfessional();
        $mealOption = new MealOption();
        $mealOption->setProfessional($professional);
        $form = $this->createForm(OptionMealType::class, $mealOption);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($mealOption);
            $em->flush();

            return $this->redirectToRoute('professional_meal_option_index');
        }

        return $this->render('admin/meal_option/form.html.twig', [
            'title' => $this->translator->trans('ui.new_meal'),
            'form'  => $form->createView()
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function editMealOption(Request $request, MealOption $mealOption): Response
    {
        $professional = $this->getUser()->getProfessional();
        $mealOption->setProfessional($professional);
        $form = $this->createForm(OptionMealType::class, $mealOption);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($mealOption);
            $em->flush();

            return $this->redirectToRoute('professional_meal_option_index');
        }

        return $this->render('admin/meal_option/form.html.twig', [
            'title' => substr($mealOption,0, 50) . '...',
            'form'  => $form->createView()
        ]);
    }

    #[Route('/remove/{id}', name: 'remove', methods: ['GET', 'DELETE'])]
    public function removeMeal(Request $request, MealOption $mealOption): Response
    {
        if ($request->isMethod('GET')) {
            return $this->render('admin/meal_option/delete_form.html.twig', [
                'meal' => $mealOption,
            ]);
        } else {
            if ($this->isCsrfTokenValid('delete' . $mealOption->getId(), $request->get('_token'))) {
                $em = $this->doctrine->getManager();
                $em->remove($mealOption);
                $em->flush();
            }

            return $this->redirectToRoute('professional_meal_option_index');
        }
    }
}