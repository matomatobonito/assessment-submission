<?php

declare(strict_types=1);

namespace App\Controller\Answer;

use App\Domain\AssessmentAnswer;
use App\Domain\AssessmentInstance;
use App\Domain\AssessmentAnswerOption;
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
    ) 
    {
        $this->entityManager = $entityManager;
        $this->answer = $answer;
        $this->instance = $instance;
        $this->option = $option;
    }

    /**
     * @Route("/api/assessment/answers", methods={"POST"})
     */

    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $instanceId = $data['instance_id'] ?? null;
        $questionId = $data['question_id'] ?? null;
        $optionId = $data['answer_option_id'] ?? null;

         if (!$instanceId || !$questionId || !$option_id) {
        return new JsonResponse(
            ['error' => 'Required fields missing, please check request.'],
            Response::HTTP_BAD_REQUEST
          );
        }

        $instance = $entityManager->getRepository(AssessmentInstance::class)
        ->find($instanceId);

        if (!$instance) {
        return new JsonResponse(
            ['error' => 'No such instance found in database.'],
            Response::HTTP_NOT_FOUND
          );
        }

        $option = $entityManager ->getRepository(AssessmentAnswerOption::class)
        ->find($optionId);

         if (!$option) {
            return new JsonResponse(
                ['error' => 'No such option found for this answer.'],
                Response::HTTP_NOT_FOUND
            );
        }

        if ($option->getAssessmentQuestion()->getId() !== $questionId) {
            return new JsonResponse(
                ['error' => 'Answer option does not belong to question'],
                Response::HTTP_BAD_REQUEST
            );
        }

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
