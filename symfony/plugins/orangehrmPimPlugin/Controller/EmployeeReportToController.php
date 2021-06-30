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

namespace OrangeHRM\Pim\Controller;

use OrangeHRM\Core\Vue\Component;
use OrangeHRM\Core\Vue\Prop;
use OrangeHRM\Framework\Http\Request;
use OrangeHRM\Entity\EmployeeLanguage;

class EmployeeReportToController extends BaseViewEmployeeController
{
    public function preRender(Request $request): void
    {
        $empNumber = $request->get('empNumber');
        if ($empNumber) {
            $component = new Component('employee-report-to');
            $fluencies = array_map(function ($item, $index) {
                return [
                    "id" => $index,
                    "label" => $item,
                ];
            }, EmployeeLanguage::FLUENCIES, array_keys(EmployeeLanguage::FLUENCIES));
            $competencies = array_map(function ($item, $index) {
                return [
                    "id" => $index,
                    "label" => $item,
                ];
            }, EmployeeLanguage::COMPETENCIES, array_keys(EmployeeLanguage::COMPETENCIES));

            $component->addProp(new Prop('emp-number', Prop::TYPE_NUMBER, $empNumber));
            $component->addProp(new Prop('fluencies', Prop::TYPE_ARRAY, $fluencies));
            $component->addProp(new Prop('competencies', Prop::TYPE_ARRAY, $competencies));

            $this->setComponent($component);
        } else {
            $this->handleBadRequest();
        }
    }
}
