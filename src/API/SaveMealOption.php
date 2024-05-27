<?php

namespace App\API;

use App\Entity\MealHistory;
use App\Repository\CustomerRepository;
use App\Repository\MealHistoryRepository;
use App\Repository\MealOptionRepository;
use App\Repository\MealPlanRepository;
use App\Repository\MealRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    private MealPlanRepository $mealPlanRepository;

    public function __construct(ManagerRegistry $doctrine,
                                MealHistoryRepository $mealHistoryRepository,
                                CustomerRepository $customerRepository,
                                MealRepository $mealRepository,
                                MealPlanRepository $mealPlanRepository,
                                MealOptionRepository $mealOptionRepository)
    {
        $this->doctrine = $doctrine;
        $this->mealHistoryRepository = $mealHistoryRepository;
        $this->customerRepository = $customerRepository;
        $this->mealRepository = $mealRepository;
        $this->mealPlanRepository = $mealPlanRepository;
        $this->mealOptionRepository = $mealOptionRepository;
    }

    public function __invoke(Request $request)
    {
        // Get the raw request body content
        $requestBody = $request->getContent();
        $data = json_decode($requestBody, true);

        $customer = $this->customerRepository->find($data['customer']);
        $mealOption = $this->mealOptionRepository->find($data['option']);
        $meal = $this->mealRepository->find($data['meal']);
        $date = $data['date'];
        if (isset($data['mealPlan'])) {
            $mealPlan = $this->mealPlanRepository->find($data['mealPlan']);
        } else {
            $mealPlan = null;
        }

        // Bail early if missing parameters
        if (!$customer or !$meal or !$date or !$mealOption) {
            return new Response('error');
        }

        $dateObj = new \DateTime($date);

        $mealRecord = $this->mealHistoryRepository->findOneBy([
            'customer' => $customer,
            'date' => $dateObj,
            'meal' => $meal
        ]);

        $em = $this->doctrine->getManager();
        if ($mealRecord) {
            // If a record exists matching the date and meal, replace the meal option
            $mealRecord->setMealOption($mealOption);
        } else {
            // Create new meal record
            $mealRecord = new MealHistory();
            $mealRecord->setDate($dateObj);
            $mealRecord->setCustomer($customer);
            $mealRecord->setMeal($meal);
            $mealRecord->setTime($meal->getTime());
            $mealRecord->setMealOption($mealOption);
            $mealRecord->setMealPlan($mealPlan);
            $em->persist($mealRecord);
        }

        $em->flush();

        return $mealRecord;
    }
}