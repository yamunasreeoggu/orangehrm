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

namespace OrangeHRM\Core\Api\Rest;

use OrangeHRM\Admin\Api\Model\UserModel;
use OrangeHRM\Admin\Service\UserService;
use OrangeHRM\Core\Api\CommonParams;
use OrangeHRM\Core\Api\V2\Endpoint;
use OrangeHRM\Core\Api\V2\EndpointResourceResult;
use OrangeHRM\Core\Api\V2\EndpointResult;
use OrangeHRM\Core\Api\V2\RequestParams;
use OrangeHRM\Core\Api\V2\ResourceEndpoint;
use OrangeHRM\Core\Api\V2\Validator\ParamRule;
use OrangeHRM\Core\Api\V2\Validator\ParamRuleCollection;
use OrangeHRM\Core\Api\V2\Validator\Rule;
use OrangeHRM\Core\Api\V2\Validator\Rules;
use OrangeHRM\Core\Exception\PasswordDoesNotMatchException;
use OrangeHRM\Core\Traits\ServiceContainerTrait;
use OrangeHRM\Core\Traits\UserRoleManagerTrait;
use OrangeHRM\Framework\Services;

class UpdatePasswordAPI extends Endpoint implements ResourceEndpoint
{
    use UserRoleManagerTrait;
    use ServiceContainerTrait;

    public const PARAMETER_CURRENT_PASSWORD = 'currentPassword';
    public const PARAMETER_NEW_PASSWORD = 'newPassword';
    public const PARAMETER_CONFIRM_PASSWORD = 'confirmPassword';

    public const PARAM_RULE_STRING_MAX_LENGTH = 64;

    /**
     * @return UserService|null
     */
    public function getSystemUserService(): ?UserService
    {
        return $this->getContainer()->get(Services::USER_SERVICE);
    }

    /**
     * @inheritDoc
     */
    public function getOne(): EndpointResult
    {
        throw $this->getNotImplementedException();
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForGetOne(): ParamRuleCollection
    {
        throw $this->getNotImplementedException();
    }

    /**
     * @inheritDoc
     */
    public function update(): EndpointResult
    {
        $user = $this->getUserRoleManager()->getUser();
        $currentPassword = $this->getRequestParams()->getString(
            RequestParams::PARAM_TYPE_BODY,
            self::PARAMETER_CURRENT_PASSWORD
        );
        $newPassword = $this->getRequestParams()->getString(
            RequestParams::PARAM_TYPE_BODY,
            self::PARAMETER_NEW_PASSWORD
        );
        try {
            $user = $this->getSystemUserService()->updateLoggedInUserPassword($user, $currentPassword, $newPassword);
            return new EndpointResourceResult(UserModel::class, $user);
        } catch (PasswordDoesNotMatchException $passwordDoesNotMatchException) {
            throw $this->getBadRequestException($passwordDoesNotMatchException->getMessage());
        }
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForUpdate(): ParamRuleCollection
    {
        return new ParamRuleCollection(
            new ParamRule(
                CommonParams::PARAMETER_ID
            ),
            $this->getValidationDecorator()->requiredParamRule(
                new ParamRule(
                    self::PARAMETER_CURRENT_PASSWORD,
                    new Rule(Rules::STRING_TYPE),
                    new Rule(Rules::LENGTH, [null, self::PARAM_RULE_STRING_MAX_LENGTH]),
                )
            ),
            $this->getValidationDecorator()->requiredParamRule(
                new ParamRule(
                    self::PARAMETER_NEW_PASSWORD,
                    new Rule(Rules::STRING_TYPE),
                    new Rule(Rules::LENGTH, [null, self::PARAM_RULE_STRING_MAX_LENGTH]),
                ),
            ),
            $this->getValidationDecorator()->requiredParamRule(
                new ParamRule(
                    self::PARAMETER_CONFIRM_PASSWORD,
                    new Rule(Rules::STRING_TYPE),
                    new Rule(Rules::LENGTH, [null, self::PARAM_RULE_STRING_MAX_LENGTH]),
                    new Rule(Rules::CALLBACK, [
                        function () {
                            $newPassword = $this->getRequestParams()->getString(
                                RequestParams::PARAM_TYPE_BODY,
                                self::PARAMETER_NEW_PASSWORD
                            );
                            $confirmPassword = $this->getRequestParams()->getString(
                                RequestParams::PARAM_TYPE_BODY,
                                self::PARAMETER_CONFIRM_PASSWORD
                            );
                            if ($newPassword === $confirmPassword) {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    ])
                ),
            ),
        );
    }

    /**
     * @inheritDoc
     */
    public function delete(): EndpointResult
    {
        throw $this->getNotImplementedException();
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForDelete(): ParamRuleCollection
    {
        throw $this->getNotImplementedException();
    }
}
