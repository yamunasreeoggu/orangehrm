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

namespace OrangeHRM\Installer\Migration\V5_7_0;

use Doctrine\DBAL\Exception;
use OrangeHRM\Installer\Util\V1\AbstractMigration;
use OrangeHRM\Installer\Util\V1\LangStringHelper;

class Migration extends AbstractMigration
{
    protected ?LangStringHelper $langStringHelper = null;

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function up(): void
    {
        $this->deleteLangStringTranslationByLangStringUnitId('translate_text_manually', $this->getLangHelper()->getGroupIdByName('admin'));

        $this->getLangHelper()->deleteLangStringByUnitId(
            'translate_text_manually',
            $this->getLangHelper()->getGroupIdByName('admin')
        );

        $localizationDataGroupId = $this->getDataGroupHelper()->getDataGroupIdByName(
            'apiv2_admin_localization_languages'
        );
        $this->createQueryBuilder()->update('ohrm_data_group')
            ->andWhere('id = :id')
            ->set('can_delete', ':value')
            ->setParameter('id', $localizationDataGroupId)
            ->setParameter('value', 1)
            ->executeQuery();
        $this->createQueryBuilder()->update('ohrm_user_role_data_group')
            ->andWhere('data_group_id = :dataGroupId')
            ->andWhere('user_role_id = :userRoleId')
            ->set('can_delete', ':value')
            ->setParameter('dataGroupId', $localizationDataGroupId)
            ->setParameter('userRoleId', $this->getDataGroupHelper()->getUserRoleIdByName('Admin'))
            ->setParameter('value', 1)
            ->executeQuery();

        $groups = ['admin'];
        foreach ($groups as $group) {
            $this->getLangStringHelper()->insertOrUpdateLangStrings(__DIR__, $group);
        }

        $this->updateLangStringVersion($this->getVersion());

        $this->getDataGroupHelper()->insertApiPermissions(__DIR__ . '/permission/api.yaml');
    }

    /**
     * @inheritDoc
     */
    public function getVersion(): string
    {
        return '5.7.0';
    }

    private function getLangStringHelper(): LangStringHelper
    {
        if (is_null($this->langStringHelper)) {
            $this->langStringHelper = new LangStringHelper(
                $this->getConnection()
            );
        }
        return $this->langStringHelper;
    }

    /**
     * @throws Exception
     */
    private function updateLangStringVersion(string $version): void
    {
        $qb = $this->createQueryBuilder()
            ->update('ohrm_i18n_lang_string', 'lang_string')
            ->set('lang_string.version', ':version')
            ->setParameter('version', $version);
        $qb->andWhere($qb->expr()->isNull('lang_string.version'))
            ->executeStatement();
    }

    private function deleteLangStringTranslationByLangStringUnitId(string $unitId, int $groupId): void
    {
        $id = $this->getConnection()->createQueryBuilder()
            ->select('id')
            ->from('ohrm_i18n_lang_string', 'langString')
            ->andWhere('langString.unit_id = :unitId')
            ->setParameter('unitId', $unitId)
            ->andWhere('langString.group_id = :groupId')
            ->setParameter('groupId', $groupId)
            ->executeQuery()
            ->fetchOne();

        $this->createQueryBuilder()
            ->delete('ohrm_i18n_translate')
            ->andWhere('ohrm_i18n_translate.lang_string_id = :id')
            ->setParameter('id', $id)
            ->executeQuery();
    }
}
