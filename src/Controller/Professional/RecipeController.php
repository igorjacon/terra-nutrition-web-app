<?php

namespace App\Controller\Professional;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use App\Utils\Pagination;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/recipe', name: 'recipe_')]
class RecipeController extends AbstractController
{
    private TranslatorInterface $translator;
    private ManagerRegistry $doctrine;

    public function __construct(TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $this->translator = $translator;
        $this->doctrine = $doctrine;
    }

    #[Route('/', name: 'index')]
    public function index(Request $request, RecipeRepository $recipeRepository, PaginatorInterface $paginator): Response
    {
        $professional = $this->getUser()->getProfessional();
        if ($professional) {
            $recipeQuery = $recipeRepository->createQueryBuilder('o')
                ->where('o.professional = :professional')
                ->setParameter('professional', $professional);
        } else {
            $recipeQuery = $recipeRepository->createQueryBuilder('o');
        }
        $pagination = $paginator->paginate(
            $recipeQuery, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            Pagination::PAGE_LIMIT /*limit per page*/
        );
        return $this->render('admin/recipe/index.html.twig', [
            'pagination' => $pagination,
            'title' => $this->translator->trans('ui.recipes')
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function newRecipe(Request $request): Response
    {
        $professional = $this->getUser()->getProfessional();
        $recipe = new Recipe();
        if ($professional) {
            $professional->addRecipe($recipe);
        }
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($recipe);
            $em->flush();

            return $this->redirectToRoute('professional_recipe_index');
        }

        return $this->render('admin/recipe/form.html.twig', [
            'title' => $this->translator->trans('ui.new_recipe'),
            'form'  => $form->createView()
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function editRecipe(Request $request, Recipe $recipe): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($recipe);
            $em->flush();

            return $this->redirectToRoute('professional_recipe_index');
        }

        return $this->render('admin/recipe/form.html.twig', [
            'title' => $recipe->getName(),
            'form'  => $form->createView()
        ]);
    }

    #[Route('/remove/{id}', name: 'remove', methods: ['GET', 'DELETE'])]
    public function removeRecipe(Request $request, Recipe $recipe): Response
    {
        if ($request->isMethod('GET')) {
            return $this->render('admin/recipe/delete_form.html.twig', [
                'recipe' => $recipe,
            ]);
        } else {
            if ($this->isCsrfTokenValid('delete' . $recipe->getId(), $request->get('_token'))) {
                $em = $this->doctrine->getManager();
                $em->remove($recipe);
                $em->flush();
            }

            return $this->redirectToRoute('professional_recipe_index');
        }
    }
}