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

namespace OrangeHRM\Core\Helper;

use OrangeHRM\Admin\Service\UserService;
use OrangeHRM\Authentication\Auth\User;
use OrangeHRM\Config\Config;
use OrangeHRM\Core\Dto\AttributeBag;
use OrangeHRM\Core\Exception\DaoException;
use OrangeHRM\Core\Exception\ServiceException;
use OrangeHRM\Core\Vue\Component;
use Symfony\Component\HttpFoundation\Request;

class VueControllerHelper
{
    public const COMPONENT_NAME = 'componentName';
    public const COMPONENT_PROPS = 'componentProps';
    public const PUBLIC_PATH = 'publicPath';
    public const BASE_URL = 'baseUrl';
    public const ASSETS_VERSION = 'assetsVersion';
    public const USER = 'user';
    public const SIDE_PANEL_MENU_ITEMS = 'sidePanelMenuItems';
    public const TOP_MENU_ITEMS = 'topMenuItems';

    /**
     * @var Request|null
     */
    protected ?Request $request = null;
    /**
     * @var null|Component
     */
    protected ?Component $component = null;
    /**
     * @var AttributeBag
     */
    protected AttributeBag $context;
    /**
     * @var UserService|null
     */
    protected ?UserService $userService = null;

    public function __construct()
    {
        $this->context = new AttributeBag();
    }

    /**
     * @return Request|null
     */
    public function getRequest(): ?Request
    {
        return $this->request;
    }

    /**
     * @param Request|null $request
     */
    public function setRequest(?Request $request): void
    {
        $this->request = $request;
    }

    /**
     * @return Component|null
     */
    public function getComponent(): ?Component
    {
        return $this->component;
    }

    /**
     * @param Component|null $component
     */
    public function setComponent(?Component $component): void
    {
        $this->component = $component;
    }

    /**
     * @return array
     * @throws ServiceException|DaoException
     */
    public function getContextParams(): array
    {
        list($sidePanelMenuItems, $topMenuItems) = $this->getMenuItems();

        $this->context->add(
            [
                self::COMPONENT_NAME => $this->getComponent()->getName(),
                self::COMPONENT_PROPS => $this->getComponent()->getProps(),
                self::PUBLIC_PATH => $this->getRequest()->getBasePath(),
                self::BASE_URL => $this->getRequest()->getBaseUrl(),
                self::ASSETS_VERSION => $this->getAssetsVersion(),
                self::USER => $this->getUserObject(),
                self::SIDE_PANEL_MENU_ITEMS => $sidePanelMenuItems,
                self::TOP_MENU_ITEMS => $topMenuItems,
            ]
        );
        return $this->context->all();
    }

    /**
     * @return string
     */
    protected function getAssetsVersion(): string
    {
        return Config::get('ohrm_vue_build_timestamp');
    }

    /**
     * @return int|null
     */
    protected function getUserId(): ?int
    {
        return User::getInstance()->getUserId();
    }

    /**
     * @return array
     * @throws ServiceException
     */
    protected function getUserObject(): array
    {
        if (is_null($this->getUserId())) {
            // No logged in user, may be in login page
            return [];
        }

        $user = $this->getUserService()->getSystemUser($this->getUserId());
        // TODO:: provide actual user profile picture
        $profileImgUrl = sprintf(
            '%s/dist/img/user-default-400.png?%s',
            $this->getRequest()->getBasePath(),
            $this->getAssetsVersion()
        );

        $firstName = $user->getEmployee() ?
            $user->getEmployee()->getFirstName() :
            $user->getUserRole()->getDisplayName();
        $lastName = $user->getEmployee() ? $user->getEmployee()->getLastName() : null;
        return [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'profImgSrc' => $profileImgUrl,
        ];
    }

    /**
     * @return array[]
     * @throws DaoException
     */
    protected function getMenuItems(): array
    {
        try {
            return MenuHelper::getMenuItems($this->getRequest()->getBaseUrl());
        } catch (ServiceException $e) {
        }
        return [[], []];
    }

    /**
     * @return UserService
     */
    public function getUserService(): UserService
    {
        if (!$this->userService instanceof UserService) {
            $this->userService = new UserService();
        }
        return $this->userService;
    }

    /**
     * @param UserService $userService
     */
    public function setUserService(UserService $userService): void
    {
        $this->userService = $userService;
    }
}
