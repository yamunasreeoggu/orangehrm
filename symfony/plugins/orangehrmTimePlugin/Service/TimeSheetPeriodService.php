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

namespace OrangeHRM\Time\Service;

use OrangeHRM\Core\Traits\Service\ConfigServiceTrait;
use OrangeHRM\Time\Factory\TimesheetPeriodFactory;

class TimeSheetPeriodService
{
    use ConfigServiceTrait;

    public const DEFAULT_TIMESHEET_START_DATE = 1;

    /**
     * @param string $currentDate
     * @return mixed
     */
    public function getDefinedTimesheetPeriod(string $currentDate)
    {
        // TODO
        $xmlString = $this->getConfigService()->getTimeSheetPeriodConfig();
        $xml = simplexml_load_String($xmlString);
        return $this->getDaysOfTheTimesheetPeriod($xml, $currentDate);
    }

    /**
     * @param $xml
     * @param string $currentDate
     * @return mixed
     */
    public function getDaysOfTheTimesheetPeriod($xml, string $currentDate)
    {
        // TODO
        $timesheetPeriodFactory = new TimesheetPeriodFactory();
        $timesheetPeriodObject = $timesheetPeriodFactory->createTimesheetPeriod($xml);
        return $timesheetPeriodObject->calculateDaysInTheTimesheetPeriod($currentDate, $xml);
    }

    /**
     * @return bool
     */
    public function isTimesheetPeriodDefined(): bool
    {
        return $this->getConfigService()->isTimesheetPeriodDefined();
    }

    /**
     * @param string $startDay
     * @return void
     */
    public function setTimesheetPeriod(string $startDay): void
    {
        $timesheetPeriodFactory = new TimesheetPeriodFactory();
        $timesheetPeriodObject = $timesheetPeriodFactory->setTimesheetPeriod();
        $xml = $timesheetPeriodObject->setTimesheetPeriodAndStartDate($startDay);
        $this->getConfigService()->setTimeSheetPeriodSetValue("Yes");
        $this->getConfigService()->setTimeSheetPeriodConfig($xml);
    }

    /**
     * @return string
     */
    public function getTimesheetHeading(): string
    {
        $xmlString = $this->getConfigService()->getTimeSheetPeriodConfig();
        $xml = simplexml_load_String($xmlString);
        return $xml->Heading;
    }

    /**
     * @return string
     */
    public function getTimesheetStartDate(): string
    {
        $xmlString = $this->getConfigService()->getTimeSheetPeriodConfig();
        $xml = simplexml_load_String($xmlString);
        return (string)$xml->StartDate;
    }
}
