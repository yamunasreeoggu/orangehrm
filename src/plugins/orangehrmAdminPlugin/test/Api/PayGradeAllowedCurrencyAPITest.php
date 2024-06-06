<?php
/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software: you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with OrangeHRM.
 * If not, see <https://www.gnu.org/licenses/>.
 */

namespace OrangeHRM\Tests\Admin\Api;

use OrangeHRM\Admin\Api\PayGradeAllowedCurrencyAPI;
use OrangeHRM\Entity\PayGrade;
use OrangeHRM\Entity\PayGradeCurrency;
use OrangeHRM\Framework\Services;
use OrangeHRM\Tests\Util\EndpointIntegrationTestCase;
use OrangeHRM\Tests\Util\Integration\TestCaseParams;
use OrangeHRM\Tests\Util\TestDataService;

/**
 * @group Admin
 * @group APIv2
 */
class PayGradeAllowedCurrencyAPITest extends EndpointIntegrationTestCase
{
    protected function setUp(): void
    {
        TestDataService::truncateSpecificTables([PayGrade::class, PayGradeCurrency::class]);
        $this->populateFixtures('PayGradeAPI.yml');
    }

    /**
     * @dataProvider dataProviderForTestGetAll
     */
    public function testGetAll(TestCaseParams $testCaseParams): void
    {
        $this->createKernelWithMockServices([Services::AUTH_USER => $this->getMockAuthUser($testCaseParams)]);
        $this->registerServices($testCaseParams);
        $api = $this->getApiEndpointMock(PayGradeAllowedCurrencyAPI::class, $testCaseParams);
        $this->assertValidTestCase($api, 'getAll', $testCaseParams);
    }

    public function dataProviderForTestGetAll(): array
    {
        return $this->getTestCases('PayGradeAllowedCurrencyAPITestCases.yml', 'GetAll');

    }

    public function testCreate(): void
    {
        $api = new PayGradeAllowedCurrencyAPI($this->getRequest());
        $this->expectNotImplementedException();
        $api->create();
    }

    public function testGetValidationRuleForCreate(): void
    {
        $api = new PayGradeAllowedCurrencyAPI($this->getRequest());
        $this->expectNotImplementedException();
        $api->getValidationRuleForCreate();
    }
    public function testDelete(): void
    {
        $api = new PayGradeAllowedCurrencyAPI($this->getRequest());
        $this->expectNotImplementedException();
        $api->delete();
    }

    public function testGetValidationRuleForDelete(): void
    {
        $api = new PayGradeAllowedCurrencyAPI($this->getRequest());
        $this->expectNotImplementedException();
        $api->getValidationRuleForDelete();
    }
}
