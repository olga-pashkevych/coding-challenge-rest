<?php

namespace App\Service;

use App\Entity\Advisor as AdvisorEntity;
use App\Request\CreateAdvisorRequest;
use App\Request\FilterAdvisor;
use App\Request\OrderByAdvisor;
use App\Request\UpdateAdvisorRequest;

interface AdvisorServiceInterface
{
    /**
     * Creates new Advisor
     *
     * @param CreateAdvisorRequest $advisor
     * @return AdvisorEntity|null
     */
    public function create(CreateAdvisorRequest $advisor): ?AdvisorEntity;

    /**
     * Deletes Advisor and Advisor Languages
     *
     * @param int $advisorId
     * @return bool
     */
    public function delete(int $advisorId): bool;

    /**
     * Updates Advisor
     *
     * @param UpdateAdvisorRequest $request
     * @return AdvisorEntity|null
     */
    public function update(UpdateAdvisorRequest $request): ?AdvisorEntity;

    /**
     * Returns One Advisor
     *
     * @param int $id
     * @return AdvisorEntity|null
     */
    public function getAdvisor(int $id): ?AdvisorEntity;

    /**
     * Returns array of Advisors
     *
     * @param FilterAdvisor $filter
     * @param OrderByAdvisor $orderBy
     * @return CreateAdvisorRequest[]
     */
    public function getAdvisors(FilterAdvisor $filter, OrderByAdvisor $orderBy): array;
}