<?php

namespace App\Controller\Admin;

use App\Entity\MealType;
use App\Form\MealTypeType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/meal-type', name: 'meal_type_')]
class MealTypeController extends AbstractController
{
    private TranslatorInterface $translator;
    private ManagerRegistry $doctrine;

    public function __construct(TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $this->translator = $translator;
        $this->doctrine = $doctrine;
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $mealType = new MealType();
        $targetId = $request->get('target_id');
        $form = $this->createForm(MealTypeType::class, $mealType, [
            'action' => $this->generateUrl('admin_meal_type_new', ['target_id' => $targetId])
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($mealType);
            $em->flush();

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse([
                    'form_field_id' => '#' . $targetId,
                    'data' => [
                        [
                            'entityId' => $mealType->getId(),
                            'entityName' => $mealType->getName()
                        ],
                    ],
                ]);
            }
        }

        return $this->render('default/_modal.html.twig', [
            'title' => $this->translator->trans('ui.modal.new_meal_type'),
            'form' => $form->createView(),
            'modal_size' => 'modal-md'
        ]);
    }
}