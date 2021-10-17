<?php

namespace App\Service;

use App\Exception\RequestException;
use App\Request\CreateAdvisorRequest;
use App\Request\FilterAdvisor;
use App\Request\OrderByAdvisor;
use App\Repository\AdvisorRepository;
use App\Entity\Advisor as AdvisorEntity;
use App\Request\UpdateAdvisorRequest;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AdvisorService implements AdvisorServiceInterface
{
    public function __construct(
        private AdvisorRepository $advisorRepository,
        private ValidatorInterface $validator
    ){}

    /**
     * Creates new Advisor
     *
     * @param CreateAdvisorRequest $advisor
     * @return AdvisorEntity|null
     * @throws RequestException
     */
    public function create(CreateAdvisorRequest $advisor): ?AdvisorEntity
    {
        $errors = $this->validator->validate($advisor);

        if ($errors->count()) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[] = $error->getMessage();
            }
            throw new RequestException($messages);
        }

        $advisorEntity = AdvisorEntity::createFromRequest($advisor);

        return $this->advisorRepository->saveAdvisor($advisorEntity);
    }

    /**
     * Deletes Advisor and Advisor Languages
     *
     * @param int $advisorId
     * @return bool
     */
    public function delete(int $advisorId): bool
    {
        $advisor = $this->advisorRepository->findOneBy(['id' => $advisorId]);

        if (!$advisor){
            return false;
        }

        $this->advisorRepository->removeAdvisor($advisor);

        return true;
    }

    /**
     * Updates Advisor
     *
     * @param UpdateAdvisorRequest $request
     * @return AdvisorEntity|null
     */
    public function update(UpdateAdvisorRequest $request): ?AdvisorEntity
    {
        $errors = $this->validator->validate($request);

        if ($errors->count()) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[] = $error->getMessage();
            }
            throw new RequestException($messages);
        }

        $advisor = $this->advisorRepository->findOneBy(['id' => $request->id]);

        if (!$advisor){
            throw new RequestException(["Advisor with id: $request->id doesn't exist."]);
        }

        $advisor->updateFromRequest($request);

        return $this->advisorRepository->saveAdvisor($advisor);
    }

    /**
     * Returns One Advisor
     *
     * @param int $id
     * @return AdvisorEntity|null
     */
    public function getAdvisor(int $id): ?AdvisorEntity
    {
        return $this->advisorRepository->findOneBy(['id' => $id]);
    }

    /**
     * Returns array of Advisors
     *
     * @param FilterAdvisor $filter
     * @param OrderByAdvisor $orderBy
     * @return Advisor[]
     */
    public function getAdvisors(FilterAdvisor $filter, OrderByAdvisor $orderBy): array
    {
        return $this->advisorRepository->getAdvisorsByFilter($filter, $orderBy);
    }
}