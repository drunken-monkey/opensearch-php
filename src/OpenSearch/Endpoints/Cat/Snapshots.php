<?php

declare(strict_types=1);

/**
 * Copyright OpenSearch Contributors
 * SPDX-License-Identifier: Apache-2.0
 *
 * OpenSearch PHP client
 *
 * @link      https://github.com/opensearch-project/opensearch-php/
 * @copyright Copyright (c) Elasticsearch B.V (https://www.elastic.co)
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @license   https://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License, Version 2.1
 *
 * Licensed to Elasticsearch B.V under one or more agreements.
 * Elasticsearch B.V licenses this file to you under the Apache 2.0 License or
 * the GNU Lesser General Public License, Version 2.1, at your option.
 * See the LICENSE file in the project root for more information.
 */

namespace OpenSearch\Endpoints\Cat;

use OpenSearch\Endpoints\AbstractEndpoint;

class Snapshots extends AbstractEndpoint
{
    protected $repository;

    public function getURI(): string
    {
        $repository = $this->repository ?? null;

        if (isset($repository)) {
            return "/_cat/snapshots/$repository";
        }
        return "/_cat/snapshots";
    }

    public function getParamWhitelist(): array
    {
        return [
            'format',
            'ignore_unavailable',
            'master_timeout',
            'h',
            'help',
            's',
            'time',
            'v',
            'cluster_manager_timeout'
        ];
    }

    public function getMethod(): string
    {
        return 'GET';
    }

    public function setRepository($repository): Snapshots
    {
        if (isset($repository) !== true) {
            return $this;
        }
        if (is_array($repository) === true) {
            $repository = implode(",", $repository);
        }
        $this->repository = $repository;

        return $this;
    }
    protected function getParamDeprecation(): array
    {
        return ['master_timeout' => 'cluster_manager_timeout'];
    }
}
