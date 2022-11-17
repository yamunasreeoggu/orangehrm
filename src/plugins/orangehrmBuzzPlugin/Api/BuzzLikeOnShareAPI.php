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
 * Boston, MA 02110-1301, USA
 */

namespace OrangeHRM\Buzz\Api;

use OpenApi\Annotations as OA;
use OrangeHRM\Buzz\Api\Model\BuzzLikeOnShareModel;
use OrangeHRM\Buzz\Dto\BuzzLikeSearchFilterParams;
use OrangeHRM\Buzz\Traits\Service\BuzzServiceTrait;
use OrangeHRM\Core\Api\CommonParams;
use OrangeHRM\Core\Api\V2\CollectionEndpoint;
use OrangeHRM\Core\Api\V2\Endpoint;
use OrangeHRM\Core\Api\V2\EndpointCollectionResult;
use OrangeHRM\Core\Api\V2\EndpointResourceResult;
use OrangeHRM\Core\Api\V2\EndpointResult;
use OrangeHRM\Core\Api\V2\Model\ArrayModel;
use OrangeHRM\Core\Api\V2\ParameterBag;
use OrangeHRM\Core\Api\V2\RequestParams;
use OrangeHRM\Core\Api\V2\Validator\ParamRule;
use OrangeHRM\Core\Api\V2\Validator\ParamRuleCollection;
use OrangeHRM\Core\Api\V2\Validator\Rule;
use OrangeHRM\Core\Api\V2\Validator\Rules;
use OrangeHRM\Core\Traits\Auth\AuthUserTrait;
use OrangeHRM\Entity\BuzzLikeOnShare;
use OrangeHRM\Entity\BuzzShare;

class BuzzLikeOnShareAPI extends Endpoint implements CollectionEndpoint
{
    use AuthUserTrait;
    use BuzzServiceTrait;

    public const PARAMETER_SHARE_ID = 'shareId';

    /**
     * @OA\Get(
     *     path="/api/v2/buzz/shares/{shareId}/likes",
     *     tags={"Buzz/Share Likes"},
     *     @OA\PathParameter(
     *         name="shareId",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="sortField",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", enum=BuzzLikeSearchFilterParams::ALLOWED_SORT_FIELDS)
     *     ),
     *     @OA\Parameter(ref="#/components/parameters/sortOrder"),
     *     @OA\Parameter(ref="#/components/parameters/limit"),
     *     @OA\Parameter(ref="#/components/parameters/offset"),
     *     @OA\Response(
     *         response="200",
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Buzz-BuzzLikeOnShareModel")
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="total", type="integer")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Unprocessable Content - Invalid Share ID",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="object",
     *                 @OA\Property(property="status", type="string", default="422"),
     *                 @OA\Property(property="message", type="string", default="Invalid Parameter"),
     *                 @OA\Property(
     *                     property="data",
     *                     type="object",
     *                     @OA\Property(
     *                         property="invalidParamKeys",
     *                         type="array",
     *                         @OA\Items(default="shareId")
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     * @inheritDoc
     */
    public function getAll(): EndpointResult
    {
        $shareId = $this->getRequestParams()->getInt(
            RequestParams::PARAM_TYPE_ATTRIBUTE,
            self::PARAMETER_SHARE_ID
        );
        $buzzShare = $this->getBuzzService()->getBuzzDao()->getBuzzShareById($shareId);
        if (!$buzzShare instanceof BuzzShare) {
            throw $this->getInvalidParamException(self::PARAMETER_SHARE_ID);
        }

        $buzzLikeSearchFilterParams = new BuzzLikeSearchFilterParams();
        $buzzLikeSearchFilterParams->setShareId($shareId);

        $this->setSortingAndPaginationParams($buzzLikeSearchFilterParams);

        $likes = $this->getBuzzService()->getBuzzLikeDao()->getBuzzLikeOnShareList($buzzLikeSearchFilterParams);
        $likeCount = $this->getBuzzService()->getBuzzLikeDao()->getBuzzLikeOnShareCount($buzzLikeSearchFilterParams);

        return new EndpointCollectionResult(
            BuzzLikeOnShareModel::class,
            $likes,
            new ParameterBag([CommonParams::PARAMETER_TOTAL => $likeCount])
        );
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForGetAll(): ParamRuleCollection
    {
        return new ParamRuleCollection(
            new ParamRule(
                self::PARAMETER_SHARE_ID,
                new Rule(Rules::POSITIVE),
            ),
            ...$this->getSortingAndPaginationParamsRules(BuzzLikeSearchFilterParams::ALLOWED_SORT_FIELDS)
        );
    }

    /**
     * @OA\Post(
     *     path="/api/v2/buzz/shares/{shareId}/likes",
     *     tags={"Buzz/Share Likes"},
     *     @OA\PathParameter(
     *         name="shareId",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/Buzz-BuzzLikeOnShareModel"
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="total", type="integer")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Unprocessable Content - Invalid Share ID",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="object",
     *                 @OA\Property(property="status", type="string", default="422"),
     *                 @OA\Property(property="message", type="string", default="Invalid Parameter"),
     *                 @OA\Property(
     *                     property="data",
     *                     type="object",
     *                     @OA\Property(
     *                         property="invalidParamKeys",
     *                         type="array",
     *                         @OA\Items(default="shareId")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad Request - Liking a post that is already liked",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="object",
     *                 @OA\Property(property="status", type="string", default="400"),
     *                 @OA\Property(property="message", type="string", default="Share is already liked")
     *             )
     *         )
     *     )
     * )
     * @inheritDoc
     */
    public function create(): EndpointResult
    {
        $shareId = $this->getRequestParams()->getInt(
            RequestParams::PARAM_TYPE_ATTRIBUTE,
            self::PARAMETER_SHARE_ID
        );

        $buzzShare = $this->getBuzzService()->getBuzzDao()->getBuzzShareById($shareId);
        if (!$buzzShare instanceof BuzzShare) {
            throw $this->getInvalidParamException(self::PARAMETER_SHARE_ID);
        }

        $buzzShareOnLike = $this->getBuzzService()
            ->getBuzzLikeDao()
            ->getBuzzLikeOnShareByShareIdAndEmpNumber($shareId, $this->getAuthUser()->getEmpNumber());
        if ($buzzShareOnLike instanceof BuzzLikeOnShare) {
            throw $this->getBadRequestException('Share is already liked');
        }

        $buzzShare->getDecorator()->increaseNumOfLikesByOne();
        $this->getBuzzService()->getBuzzDao()->saveBuzzShare($buzzShare);

        $like = new BuzzLikeOnShare();
        $this->setBuzzLikeOnShare($like);

        $like = $this->getBuzzService()->getBuzzLikeDao()->saveBuzzLikeOnShare($like);
        return new EndpointResourceResult(BuzzLikeOnShareModel::class, $like);
    }

    /**
     * @param BuzzLikeOnShare $buzzLikeOnShare
     */
    private function setBuzzLikeOnShare(BuzzLikeOnShare $buzzLikeOnShare): void
    {
        $buzzLikeOnShare->getDecorator()->setShareByShareId(
            $this->getRequestParams()->getInt(
                RequestParams::PARAM_TYPE_ATTRIBUTE,
                self::PARAMETER_SHARE_ID
            )
        );

        $buzzLikeOnShare->getDecorator()->setEmployeeByEmpNumber(
            $this->getAuthUser()->getEmpNumber()
        );

        $buzzLikeOnShare->setLikedAtUtc();
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForCreate(): ParamRuleCollection
    {
        return new ParamRuleCollection(
            new ParamRule(
                self::PARAMETER_SHARE_ID,
                new Rule(Rules::POSITIVE),
            ),
        );
    }

    /**
     * @OA\Delete(
     *     path="/api/v2/buzz/shares/{shareId}/likes",
     *     tags={"Buzz/Share Likes"},
     *     @OA\PathParameter(
     *         name="shareId",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="shareId", type="integer")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Unprocessable Content - Invalid Share ID",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="object",
     *                 @OA\Property(property="status", type="string", default="422"),
     *                 @OA\Property(property="message", type="string", default="Invalid Parameter"),
     *                 @OA\Property(
     *                     property="data",
     *                     type="object",
     *                     @OA\Property(
     *                         property="invalidParamKeys",
     *                         type="array",
     *                         @OA\Items(default="shareId")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad Request - Disliking a post that is not liked",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="object",
     *                 @OA\Property(property="status", type="string", default="400"),
     *                 @OA\Property(property="message", type="string", default="Share is not liked")
     *             )
     *         )
     *     )
     * )
     * @inheritDoc
     */
    public function delete(): EndpointResult
    {
        $shareId = $this->getRequestParams()->getInt(
            RequestParams::PARAM_TYPE_ATTRIBUTE,
            self::PARAMETER_SHARE_ID
        );

        $buzzShare = $this->getBuzzService()->getBuzzDao()->getBuzzShareById($shareId);
        if (!$buzzShare instanceof BuzzShare) {
            throw $this->getInvalidParamException(self::PARAMETER_SHARE_ID);
        }

        $buzzShareOnLike = $this->getBuzzService()
            ->getBuzzLikeDao()
            ->getBuzzLikeOnShareByShareIdAndEmpNumber($shareId, $this->getAuthUser()->getEmpNumber());
        if (!$buzzShareOnLike instanceof BuzzLikeOnShare) {
            throw $this->getBadRequestException('Share is not liked');
        }

        $buzzShare->getDecorator()->decreaseNumOfLikesByOne();
        $this->getBuzzService()->getBuzzDao()->saveBuzzShare($buzzShare);

        $this->getBuzzService()->getBuzzLikeDao()->deleteBuzzLikeOnShare($shareId, $this->getAuthUser()->getEmpNumber());
        return new EndpointResourceResult(ArrayModel::class, [self::PARAMETER_SHARE_ID => $shareId]);
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForDelete(): ParamRuleCollection
    {
        return new ParamRuleCollection(
            new ParamRule(
                self::PARAMETER_SHARE_ID,
                new Rule(Rules::POSITIVE),
            ),
        );
    }
}