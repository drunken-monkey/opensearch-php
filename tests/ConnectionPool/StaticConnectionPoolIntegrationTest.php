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

namespace OpenSearch\Tests\ConnectionPool;

use OpenSearch;
use OpenSearch\Tests\Utility;

/**
 * Class StaticConnectionPoolIntegrationTest
 *
 * @subpackage Tests/StaticConnectionPoolTest
 * @group Integration
 */
class StaticConnectionPoolIntegrationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var string
     */
    private $host;

    public function setUp(): void
    {
        $this->host = Utility::getHost();
    }

    // Issue #636
    public function test404Liveness()
    {
        $client = \OpenSearch\ClientBuilder::create()
            ->setHosts([$this->host])
            ->setConnectionPool(\OpenSearch\ConnectionPool\StaticConnectionPool::class)
            ->setSSLVerification(false)
            ->build();

        $connection = $client->transport->getConnection();

        // Ensure connection is dead
        $connection->markDead();

        // The index doesn't exist, but the server is up so this will return a 404
        $this->assertFalse($client->indices()->exists(['index' => 'not_existing_index']));

        // But the node should be marked as alive since the server responded
        $this->assertTrue($connection->isAlive());
    }
}
