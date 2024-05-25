<?php

namespace App\API;

use App\Entity\MealHistory;
use App\Repository\CustomerRepository;
use App\Repository\MealHistoryRepository;
use App\Repository\MealOptionRepository;
use App\Repository\MealRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class SaveMealOption extends AbstractController
{

    private ManagerRegistry $doctrine;
    private MealHistoryRepository $mealHistoryRepository;
    private CustomerRepository $customerRepository;
    private MealRepository $mealRepository;
    private MealOptionRepository $mealOptionRepository;

    public function __construct(ManagerRegistry $doctrine,
                                MealHistoryRepository $mealHistoryRepository,
                                CustomerRepository $customerRepository,
                                MealRepository $mealRepository,
                                MealOptionRepository $mealOptionRepository)
    {
        $this->doctrine = $doctrine;
        $this->mealHistoryRepository = $mealHistoryRepository;
        $this->customerRepository = $customerRepository;
        $this->mealRepository = $mealRepository;
        $this->mealOptionRepository = $mealOptionRepository;
    }

    public function __invoke(Request $request): Response
    {
        $customer = $this->customerRepository->find($request->get('customer'));
        $mealOption = $this->mealOptionRepository->find($request->get('option'));
        $meal = $this->mealRepository->find($request->get('meal'));
        $date = $request->get('date');

        // Bail early if missing parameters
        if (!$customer or !$meal or !$date or !$mealOption) {
            return new Response('error');
        }

        $dateObj = new \DateTime($date);

        $mealHistoryForDateMeal = $this->mealHistoryRepository->findOneBy([
            'customer' => $customer,
            'date' => $dateObj,
            'meal' => $meal
        ]);

        $em = $this->doctrine->getManager();
        if ($mealHistoryForDateMeal) {
            // If a record exists matching the date and meal, replace the meal option
            $mealHistoryForDateMeal->setMealOption($mealOption);
        } else {
            // Create new meal record
            $mealRecord = new MealHistory();
            $mealRecord->setDate($dateObj);
            $mealRecord->setCustomer($customer);
            $mealRecord->setMeal($meal);
            $mealRecord->setTime($meal->getTime());
            $mealRecord->setMealOption($mealOption);
//            $mealRecord->setMealPlan();
            $em->persist($mealRecord);
        }

        $em->flush();

        return new Response('saved');
    }
}