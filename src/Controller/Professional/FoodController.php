<?php

namespace App\Controller\Professional;

use App\Entity\FoodItem;
use App\Form\FoodItemType;
use App\Repository\FoodItemRepository;
use App\Utils\Pagination;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/food', name: 'food_')]
class FoodController extends AbstractController
{
    private TranslatorInterface $translator;
    private ManagerRegistry $doctrine;
    private FoodItemRepository $foodItemRepository;

    public function __construct(TranslatorInterface $translator, ManagerRegistry $doctrine, FoodItemRepository $foodItemRepository)
    {
        $this->translator = $translator;
        $this->doctrine = $doctrine;
        $this->foodItemRepository = $foodItemRepository;
    }

    #[Route('/', name: 'index')]
    public function index(Request $request, FoodItemRepository $foodItemRepository, PaginatorInterface $paginator): Response
    {
        $text = $request->get('search');
        $foods = $foodItemRepository->createQueryBuilder('o');
        if ($text) {
            $foods->where('o.name LIKE :searchText')
                ->orWhere('o.description LIKE :searchText')
                ->orWhere('o.classificationName LIKE :searchText')
                ->setParameter('searchText', '%' . $text . '%');
        }
        $pagination = $paginator->paginate(
            $foods, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            Pagination::PAGE_LIMIT /*limit per page*/
        );

        if ($request->isXmlHttpRequest()) {
            return $this->render('admin/foods/_search_result.html.twig', [
                'pagination' => $pagination
            ]);
        } else {
            return $this->render('admin/foods/index.html.twig', [
                'pagination' => $pagination,
                'title' => $this->translator->trans('ui.food_items')
            ]);
        }
    }

    #[Route('/new', name: 'new_food', methods: ['GET', 'POST'])]
    public function newFood(Request $request): Response
    {
        $food = new FoodItem();
        $form = $this->createForm(FoodItemType::class, $food);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($food);
            $em->flush();

            return $this->redirectToRoute('professional_food_index');
        }

        return $this->render('admin/foods/form.html.twig', [
            'title' => $this->translator->trans('ui.new_food'),
            'form'  => $form->createView()
        ]);
    }

    #[Route('/food/edit/{foodKey}', name: 'edit_food', methods: ['GET', 'POST'])]
    public function editFood(Request $request): Response
    {
        $foodItem = $this->foodItemRepository->findOneBy(['foodKey' => $request->get('foodKey')]);
        $form = $this->createForm(FoodItemType::class, $foodItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->flush();

            return $this->redirectToRoute('professional_food_index');
        }

        return $this->render('admin/foods/form.html.twig', [
            'title' => $foodItem->getName(),
            'form'  => $form->createView()
        ]);
    }

    #[Route('/food/remove/{foodKey}', name: 'remove_food', methods: ['GET', 'DELETE'])]
    public function removeFood(Request $request): Response
    {
        $foodItem = $this->foodItemRepository->findOneBy(['foodKey' => $request->get('foodKey')]);
        if ($request->isMethod('GET')) {
            return $this->render('admin/foods/delete_form.html.twig', [
                'foodItem' => $foodItem,
            ]);
        } else {
            if ($this->isCsrfTokenValid('delete' . $foodItem->getFoodKey(), $request->get('_token'))) {
                $em = $this->doctrine->getManager();

                $em->remove($foodItem);
                $em->flush();
            }

            return $this->redirectToRoute('professional_food_index');
        }
    }
}