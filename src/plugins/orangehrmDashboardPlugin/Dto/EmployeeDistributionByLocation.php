<?php
/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */

namespace OrangeHRM\Dashboard\Dto;

class EmployeeDistributionByLocation
{
    private array $locationCountPairs;
    private int $otherEmployeeCount;
    private int $unassignedEmployeeCount;
    private int $limit;

    /**
     * @param LocationEmployeeCount[] $locationCountPairs
     * @param int   $otherEmployeeCount
     * @param int   $unassignedEmployeeCount
     * @param int   $limit
     */
    public function __construct(
        array $locationCountPairs,
        int $otherEmployeeCount,
        int $unassignedEmployeeCount,
        int $limit
    ) {
        $this->locationCountPairs = $locationCountPairs;
        $this->otherEmployeeCount = $otherEmployeeCount;
        $this->unassignedEmployeeCount = $unassignedEmployeeCount;
        $this->limit = $limit;
    }

    /**
     * @return array
     */
    public function getLocationCountPairs(): array
    {
        return $this->locationCountPairs;
    }

    /**
     * @return int
     */
    public function getOtherEmployeeCount(): int
    {
        return $this->otherEmployeeCount;
    }

    /**
     * @return int
     */
    public function getUnassignedEmployeeCount(): int
    {
        return $this->unassignedEmployeeCount;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }
}
