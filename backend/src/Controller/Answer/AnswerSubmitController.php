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
use Symfony\Component\HttpFoundation\Request;

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

    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $instanceId = $data['instance_id'] ?? null;
        $questionId = $data['question_id'] ?? null;
        $answerOptionId = $data['answer_option_id'] ?? null;

        $instance = $entityManager->getRepository(AssessmentInstance::class)
        ->find($instanceId);

        $option = $entityManager ->getRepository(AssessmentAnswerOption::class)
        ->find($answerOptionId);

        $answer = new AssessmentAnswer(
            null,
            $instance,
            $option
        );

        $entityManager->persist($answer);
        $entityManager->flush();

         return new JsonResponse(
        ['id' => $answer->getId()],
        Response::HTTP_CREATED
    );
    }
}
