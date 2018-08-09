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
 *
 */

class OrangeHrmRegisterService
{
    private $configService;

    /**
     * Send details of employee count change to the server
     * @return bool
     * @throws CoreServiceException
     */
    public function sendRegistrationData() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://osreg-test-iris.orangehrm.com/registerAcceptor.php");
        curl_setopt($ch, CURLOPT_POST, 1);

        $data = "username=" . $_SESSION['defUser']['AdminUserName']
            . "&userEmail=" . $_SESSION['defUser']['organizationEmailAddress']
            . "&telephone=" . $_SESSION['defUser']['contactNumber']
            . "&admin_first_name=" . $_SESSION['defUser']['adminEmployeeFirstName']
            . "&admin_last_name=" . $_SESSION['defUser']['adminEmployeeLastName']
            . "&timezone=" . $_SESSION['defUser']['timezone']
            . "&language=" . $_SESSION['defUser']['language']
            . "&country=" . $_SESSION['defUser']['country']
            . "&organization_name=" . $_SESSION['defUser']['organizationName']
            . "&instance_identifier=" . $this->getInstanceIdentifier()
            . "&type=" . $_SESSION['defUser']['type']
            . "&employee_count=" . $_SESSION['defUser']['employee_count'];

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        if (strpos($response, 'SUCCESSFUL') === false) {
            return false;
        } else {

            return true;
        }
    }

    /**
     * Get ConfigService instance
     * @return ConfigService
     */
    private function getConfigService() {
        if (!($this->configService instanceof ConfigService)) {
            $this->configService = new ConfigService();
        }
        return $this->configService;
    }

    /**
     * Get the instance Identifier value
     * @return String
     * @throws CoreServiceException
     */
    private function getInstanceIdentifier() {
        return $this->getConfigService()->getInstanceIdentifier();
    }
}