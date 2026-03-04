<?php

declare(strict_types=1);

namespace App\Controller\Answer;

use App\Domain\AssessmentAnswer;
use App\Domain\AssessmentInstance;
use App\Domain\AssessmentAnswerOption;
use App\Domain\AssessmentQuestion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnswerSubmitController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        AssessmentAnswer $answer,
        AssessmentInstance $instance,
        AssessmentAnswerOption $option,
        AssessmentQuestion $question,
    ) 
    {
        $this->entityManager = $entityManager;
        $this->answer = $answer;
        $this->instance = $instance;
        $this->option = $option;
        $this->question = $question;
    }

    /**
     * @Route("/api/assessment/answers", methods={"POST"})
     */
}
