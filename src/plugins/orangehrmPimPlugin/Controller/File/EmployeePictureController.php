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

namespace OrangeHRM\Pim\Controller\File;

use OrangeHRM\Config\Config;
use OrangeHRM\Core\Controller\AbstractFileController;
use OrangeHRM\Core\Exception\DaoException;
use OrangeHRM\Entity\EmpPicture;
use OrangeHRM\Framework\Http\BinaryFileResponse;
use OrangeHRM\Framework\Http\Request;
use OrangeHRM\Framework\Http\Response;
use OrangeHRM\Pim\Service\EmployeePictureService;

class EmployeePictureController extends AbstractFileController
{
    /**
     * @var EmployeePictureService|null
     */
    protected ?EmployeePictureService $employeePictureService = null;

    /**
     * @return EmployeePictureService
     */
    public function getEmployeePictureService(): EmployeePictureService
    {
        if (!$this->employeePictureService instanceof EmployeePictureService) {
            $this->employeePictureService = new EmployeePictureService();
        }
        return $this->employeePictureService;
    }

    /**
     * @param Request $request
     * @return BinaryFileResponse|Response
     * @throws DaoException
     */
    public function handle(Request $request)
    {
        $empNumber = $request->attributes->get('empNumber');
        if (!is_null($empNumber)) {
            $empPicture = $this->getEmployeePictureService()->getEmpPictureByEmpNumber($empNumber);
            if ($empPicture instanceof EmpPicture) {
                $response = $this->getResponse();
                $response->setEtag($this->getEmployeePictureService()->getETagByEmpPicture($empPicture));

                if (!$response->isNotModified($request)) {
                    $response->setContent($empPicture->getDecorator()->getPicture());
                    $this->setCommonHeaders($response, $empPicture->getFileType());
                }

                $response->setMaxAge(Config::get(Config::MAX_SESSION_IDLE_TIME));
                return $response;
            }
        }

        $publicPath = Config::get(Config::PUBLIC_DIR);
        $response = $this->getFileResponse(realpath($publicPath . '/images/default-photo.png'));
        $response->setAutoEtag();
        $this->setCommonHeaders($response, "image/png");
        $response->isNotModified($request);
        return $response;
    }

    /**
     * @param BinaryFileResponse|Response $response
     * @param string $contentType
     */
    private function setCommonHeaders($response, string $contentType): void
    {
        $response->headers->set('Content-Type', $contentType);
        $response->setPrivate();
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->headers->set('Pragma', 'Public');
    }
}
