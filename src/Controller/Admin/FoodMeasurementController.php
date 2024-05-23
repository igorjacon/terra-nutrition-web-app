<?php

namespace App\Controller\Admin;

use App\Entity\FoodMeasurement;
use App\Form\FoodMeasurementType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/food-measurement', name: 'food_measurement_')]
class FoodMeasurementController extends AbstractController
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
        $foodMeasurement = new FoodMeasurement();
        $targetId = $request->get('target_id');
        $form = $this->createForm(FoodMeasurementType::class, $foodMeasurement, [
            'action' => $this->generateUrl('admin_food_measurement_new', ['target_id' => $targetId])
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($foodMeasurement);
            $em->flush();

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse([
                    'form_field_id' => '#' . $targetId,
                    'data' => [
                        [
                            'entityId' => $foodMeasurement->getId(),
                            'entityName' => $foodMeasurement->getName()
                        ],
                    ],
                ]);
            }
        }

        return $this->render('default/_modal.html.twig', [
            'title' => $this->translator->trans('ui.modal.new_food_measurement'),
            'form' => $form->createView(),
            'modal_size' => 'modal-md'
        ]);
    }
}