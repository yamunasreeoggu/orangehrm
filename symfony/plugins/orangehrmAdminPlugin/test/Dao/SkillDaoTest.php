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

namespace OrangeHRM\Admin\Tests\Dao;

use OrangeHRM\Admin\Dao\SkillDao;
use OrangeHRM\Admin\Dto\SkillSearchFilterParams;
use OrangeHRM\Config\Config;
use OrangeHRM\Core\Api\V2\RequestParams;
use OrangeHRM\Entity\Skill;
use OrangeHRM\Tests\Util\TestCase;
use OrangeHRM\Tests\Util\TestDataService;

/**
 * @group Admin
 */
class SkillDaoTest extends TestCase
{

    private $skillDao;
    protected $fixture;

    /**
     * Set up method
     */
    protected function setUp(): void
    {
        $this->skillDao = new SkillDao();
        $this->fixture = Config::get('ohrm_plugins_dir') . '/orangehrmAdminPlugin/test/fixtures/SkillDao.yml';
        TestDataService::populate($this->fixture);
    }

    public function testGetSkillsList(): void
    {
        $result = $this->skillDao->getSkills();
        $this->assertEquals(count($result), 3);
    }

    public function testGetSkillById(): void
    {
        $result = $this->skillDao->getSkillById(1);
        $this->assertEquals($result->getName(), 'Driving');
        $this->assertEquals($result->getDescription(), 'Ability to drive');
    }

    public function testDeleteSkill(): void
    {
        $toBedeletedIds = array(3, 2);
        $result = $this->skillDao->deleteSkills($toBedeletedIds);
        $this->assertEquals($result, 2);
    }

    public function testSearchSkill(): void
    {
        $skillSearchParams = new SkillSearchFilterParams();

        $result = $this->skillDao->searchSkill($skillSearchParams);
        $this->assertEquals(3,count($result) );
        $this->assertTrue($result[0] instanceof Skill);
    }

    public function testSearchSkillWithLimit(): void
    {
        $skillSearchParams = new SkillSearchFilterParams();
        $skillSearchParams->setLimit(2);

        $result = $this->skillDao->searchSkill($skillSearchParams);
        $this->assertEquals(2,count($result) );
    }

    public function testSaveSkill(): void
    {
        $skill = new Skill();
        $skill->setName("Swimming");
        $skill->setDescription("Ability to swim");
        $result = $this->skillDao->saveSkill($skill);
        $this->assertTrue($result instanceof Skill);
        $this->assertEquals("Swimming", $result->getName());
        $this->assertEquals("Ability to swim", $result->getDescription());
    }

    public function testEditSkill(): void
    {
        $skill = $this->skillDao->getSkillById(1);
        $skill->setName("Driving a truck");
        $skill->setDescription("Ability to drive a truck");
        $result = $this->skillDao->saveSkill($skill);
        $this->assertTrue($result instanceof Skill);
        $this->assertEquals("Driving a truck", $result->getName());
        $this->assertEquals("Ability to drive a truck", $result->getDescription());
        $this->assertEquals(1, $result->getId());
    }

    public function testGetSkills(): void
    {
        $result = $this->skillDao->getSkills();
        $this->assertEquals(3, count($result));
        $this->assertTrue($result[0] instanceof Skill);
    }

    public function testGetSearchSkillsCount(): void
    {
        $skillSearchParams = new SkillSearchFilterParams();

        $result = $this->skillDao->getSearchSkillsCount($skillSearchParams);
        $this->assertEquals(3,$result );
    }
}
