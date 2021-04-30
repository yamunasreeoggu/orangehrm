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

namespace OrangeHRM\Core\Api\V2\Serializer;

use Exception;
use OrangeHRM\Core\Api\V2\ParameterBag;
use Traversable;

abstract class AbstractEndpointResult
{
    /**
     * @var string
     */
    protected string $modelClass;

    /**
     * @var array|object
     */
    protected $data;

    /**
     * @var ParameterBag|null
     */
    protected ?ParameterBag $meta = null;

    /**
     * @var ParameterBag|null
     */
    protected ?ParameterBag $rels = null;

    /**
     * AbstractEndpointResult constructor.
     * @param string $modelClass
     * @param array|object $data
     * @param ParameterBag|null $meta
     * @param ParameterBag|null $rels
     * @throws Exception
     */
    public function __construct(string $modelClass, $data, ParameterBag $meta = null, ParameterBag $rels = null)
    {
        if (!class_exists($modelClass)) {
            throw new Exception(
                sprintf('Could not found class `%s`. Hint: use fully qualified class name', $modelClass)
            );
        }
        $this->modelClass = $modelClass;
        $this->data = $data;
        $this->meta = $meta;
        $this->rels = $rels;
    }

    /**
     * Normalize object to associative array
     * @return array
     */
    abstract public function normalize(): array;

    /**
     * @return ParameterBag|null
     */
    public function getMeta(): ?ParameterBag
    {
        return $this->meta;
    }

    /**
     * @param ParameterBag|null $meta
     */
    public function setMeta(?ParameterBag $meta): void
    {
        $this->meta = $meta;
    }

    /**
     * @return ParameterBag|null
     */
    public function getRels(): ?ParameterBag
    {
        return $this->rels;
    }

    /**
     * @param ParameterBag|null $rels
     */
    public function setRels(?ParameterBag $rels): void
    {
        $this->rels = $rels;
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function _normalize(): array
    {
        $model = new $this->modelClass($this->data);
        if ($model instanceof Normalizable) {
            return $model->toArray();
        }
        throw new Exception(
            sprintf(
                'Model class should be instance of  `%s`',
                Normalizable::class
            )
        );
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function _normalizeArray(): array
    {
        if (is_iterable($this->data)) {
            $normalizedArray = [];
            foreach ($this->data as $data) {
                $model = new $this->modelClass($data);
                if ($model instanceof Normalizable) {
                    $normalizedArray[] = $model->toArray();
                } else {
                    throw new Exception(
                        sprintf(
                            'Model class should be instance of  `%s`',
                            Normalizable::class
                        )
                    );
                }
            }
            return $normalizedArray;
        }

        throw new Exception(
            sprintf(
                '$data should be instance of  `%s`',
                Traversable::class
            )
        );
    }
}
