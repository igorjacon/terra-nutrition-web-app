<?php

namespace App\Controller\Professional;

use App\Entity\FoodItem;
use App\Form\FoodItemType;
use App\Repository\FoodItemRepository;
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
        $foods = $foodItemRepository->createQueryBuilder('o');
        $pagination = $paginator->paginate(
            $foods, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        return $this->render('admin/foods/index.html.twig', [
            'pagination' => $pagination,
            'title' => $this->translator->trans('ui.food_items')
        ]);
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
    public function removeCustomer(Request $request): Response
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