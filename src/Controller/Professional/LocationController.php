<?php

namespace App\Controller\Professional;

use App\Entity\Location;
use App\Form\LocationType;
use App\Repository\LocationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/locations', name: 'location_')]
class LocationController extends AbstractController
{
    private TranslatorInterface $translator;
    private ManagerRegistry $doctrine;

    public function __construct(TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $this->translator = $translator;
        $this->doctrine = $doctrine;
    }

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(LocationRepository $locationRepository): Response
    {
        $professional = $this->getUser()->getProfessional();
        $locations = $locationRepository->findBy(['professional' => $professional]);

        return $this->render('admin/professionals/location/index.html.twig', [
            'locations' => $locations,
            'title' => $this->translator->trans('ui.locations')
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function newLocation(Request $request): Response
    {
        $location = new Location();
        $location->setProfessional($this->getUser()->getProfessional());
        $form = $this->createForm(LocationType::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($location);
            $em->flush();

            return $this->redirectToRoute('professional_location_index');
        }

        return $this->render('admin/professionals/location/form.html.twig', [
            'title' => $this->translator->trans('ui.new_location'),
            'form'  => $form->createView()
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function editLocation(Request $request, Location $location): Response
    {
        $form = $this->createForm(LocationType::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->flush();

            return $this->redirectToRoute('professional_location_index');
        }

        return $this->render('admin/professionals/location/form.html.twig', [
            'title' => $location->getName(),
            'form'  => $form->createView()
        ]);
    }

    #[Route('/remove/{id}', name: 'remove', methods: ['GET', 'DELETE'])]
    public function removeLocation(Request $request, Location $location): Response
    {
        if ($request->isMethod('GET')) {
            return $this->render('admin/professionals/location/delete_form.html.twig', [
                'location' => $location,
            ]);
        } else {
            if ($this->isCsrfTokenValid('delete' . $location->getId(), $request->get('_token'))) {
                $em = $this->doctrine->getManager();

                $em->remove($location);
                $em->flush();
            }

            return $this->redirectToRoute('professional_location_index');
        }
    }
}