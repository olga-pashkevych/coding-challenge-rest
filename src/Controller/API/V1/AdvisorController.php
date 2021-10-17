<?php

namespace App\Controller\API\V1;

use App\Exception\RequestException;
use App\Request\CreateAdvisorRequest;
use App\Request\FilterAdvisor;
use App\Request\OrderByAdvisor;
use App\Request\UpdateAdvisorRequest;
use App\Service\AdvisorServiceInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Advisor;

class AdvisorController
{
    /**
     * @param AdvisorServiceInterface $advisorService
     */
    public function __construct(private AdvisorServiceInterface $advisorService){}

    /**
     * @Route("/api/v1/advisors", name="create_advisor", methods={"POST"})
     * @OA\Response(
     *     response=201,
     *     description="Returns the advisor",
     *     @OA\JsonContent(ref=@Model(type=Advisor::class))
     * )
     * @OA\RequestBody(
     *     @Model(type=Advisor::class, groups={"create_advisor"})
     * )
     */
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $advisor = new CreateAdvisorRequest(
            $data['name'] ?? "",
            $data['pricePerMinute'] ?? 0,
            $data['languages'] ?? [],
            $data['description'] ?? "",
            $data['availability'] ?? 0
        );
        try {
            $advisorCreated = $this->advisorService->create($advisor);
        } catch (RequestException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($advisorCreated->toArray(), Response::HTTP_CREATED);
    }

    /**
     * @Route("/api/v1/advisors/{id}", name="delete_advisor", methods={"DELETE"})
     * @OA\Response(
     *     response=200,
     *     description="Deletes the advisor",
     * )
     */
    public function delete(int $id): JsonResponse
    {
        if ($this->advisorService->delete($id)){
            return new JsonResponse(['message' => 'Advisor has been deleted'], Response::HTTP_OK);
        }

        return new JsonResponse(['message' => "Advisor with id: $id doesn't exist."], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/api/v1/advisors/{id}", name="update_advisor", methods={"PUT"})
     * @OA\Response(
     *     response=201,
     *     description="Returns the updated advisor",
     *     @OA\JsonContent(ref=@Model(type=Advisor::class))
     * )
     * @OA\RequestBody(
     *     @Model(type=Advisor::class, groups={"update_advisor"})
     * )
     */
    public function update(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $advisor = new UpdateAdvisorRequest(
            $id,
            $data['name'],
            $data['pricePerMinute'],
            $data['languages'],
            $data['description'],
            $data['availability']
        );

        try {
            $advisorUpdated = $this->advisorService->update($advisor);
        } catch (RequestException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($advisorUpdated->toArray(), Response::HTTP_CREATED);
    }

    /**
     * @Route("/api/v1/advisors/{id}", name="get_one_advisor", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns the advisor",
     *     @OA\JsonContent(ref=@Model(type=Advisor::class))
     * )
     */
    public function get(int $id): JsonResponse
    {
        $advisor = $this->advisorService->getAdvisor($id);

        if ($advisor) {
            return new JsonResponse($advisor->toArray(), Response::HTTP_OK);
        }

        return new JsonResponse("Advisor with id: $id doesn't found", Response::HTTP_NOT_FOUND);
    }

    /**
     * @Route("/api/v1/advisors", name="get_advisors", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns the advisors",
     *     @OA\JsonContent(ref=@Model(type=Advisor::class))
     * )
     * @OA\Parameter(
     *     name="name",
     *     in="query",
     *     description="The name of the Advisor",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="language",
     *     in="query",
     *     description="The language code of Advisor",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="price",
     *     in="query",
     *     description="Sort by price: use 'asc' or 'desc'",
     *     @OA\Schema(type="string")
     * )
     */
    public function getAll(Request $request): JsonResponse
    {
        $name = $request->query->get('name') ?? null;
        $language = $request->query->get('language') ?? null;
        $orderByField = $request->query->get('price') ? 'price' : null;
        $orderByDirection = $request->query->get('price') ?? null;

        $filterAdvisor = new FilterAdvisor($name, $language);
        $orderByAdvisor = new OrderByAdvisor($orderByField, $orderByDirection);

        $advisors = $this->advisorService->getAdvisors($filterAdvisor, $orderByAdvisor);

        $result = [];
        foreach ($advisors as $advisor) {
            $result[] = $advisor->toArray();
        }

        return new JsonResponse($result, Response::HTTP_OK);
    }
}