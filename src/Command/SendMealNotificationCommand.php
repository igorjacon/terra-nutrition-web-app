<?php

namespace App\Command;

use App\Repository\MealRepository;
use App\Service\NotificationService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendMealNotificationCommand extends Command
{
    public function __construct(private MealRepository $mealRepository)
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('app:send_meal_notification');
        $this->setDescription('Send meal notification');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $weekday = (int)date('N');
        $time = date('H:i');
        if ($weekday == 7) {
            $weekday = 0;
        }

        $meals = $this->mealRepository->findBy(['time' => $time]);

        if (count($meals)) {
            foreach ($meals as $meal) {
//                $title = 'Meal Reminder';
//                $body = 'Time to eat ' . $mealPlan['meal'];
//                $this->notificationService->sendNotification($deviceToken, $title, $body);
//                break;
            }
        }
        return Command::SUCCESS;
    }

}