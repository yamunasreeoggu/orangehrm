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

namespace OrangeHRM\Tests\Buzz\Entity;

use DateTime;
use OrangeHRM\Config\Config;
use OrangeHRM\Entity\BuzzPost;
use OrangeHRM\Entity\BuzzShare;
use OrangeHRM\Entity\Employee;
use OrangeHRM\Tests\Util\EntityTestCase;
use OrangeHRM\Tests\Util\TestDataService;

/**
 * @group Buzz
 * @group Entity
 */
class BuzzShareTest extends EntityTestCase
{
    protected function setUp(): void
    {
        $fixture = Config::get(Config::PLUGINS_DIR) . '/orangehrmBuzzPlugin/test/fixtures/BuzzShare.yaml';
        TestDataService::populate($fixture);
        TestDataService::truncateSpecificTables([BuzzShare::class]);
    }

    public function testEntity(): void
    {
        $share = new BuzzShare();
        $share->setEmployee($this->getReference(Employee::class, 1));
        $share->setPost($this->getReference(BuzzPost::class, 1));
        $share->setNumOfLikes(1);
        $share->setNumOfComments(2);
        $share->setCreatedAt(new DateTime('2022-11-11 09:20'));
        $share->setUpdatedAt(new DateTime('2022-11-11 09:20'));

        $this->persist($share);

        $this->assertEquals(1, $share->getId());
        $this->assertEquals(1, $share->getNumOfLikes());
        $this->assertEquals(2, $share->getNumOfComments());
        $this->assertEquals('this is post text 01', $share->getPost()->getText());
    }
}
