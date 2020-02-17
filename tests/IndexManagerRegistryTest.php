<?php

declare(strict_types=1);

namespace Versh23\ManticoreBundle\Tests;

use Doctrine\Common\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use Versh23\ManticoreBundle\Connection;
use Versh23\ManticoreBundle\Index;
use Versh23\ManticoreBundle\IndexManager;
use Versh23\ManticoreBundle\IndexManagerRegistry;
use Versh23\ManticoreBundle\ManticoreException;
use Versh23\ManticoreBundle\Tests\Entity\SimpleEntity;

class IndexManagerRegistryTest extends TestCase
{
    public function testIndexManager()
    {
        $registry = new IndexManagerRegistry();
        $connection = $this->createMock(Connection::class);
        $managerRegistry = $this->createMock(ManagerRegistry::class);
        $index = new Index('test_index', SimpleEntity::class, [
            'name' => ['property' => 'name'],
        ], [
            'status' => ['property' => 'status', 'type' => 'string'],
        ]);

        $indexManager = new IndexManager($connection, $index, $managerRegistry);

        $registry->addIndexManager($indexManager);

        $indexManagerFound = $registry->getIndexManager('test_index');
        $indexManagerFoundByCLass = $registry->getIndexManagerByClass(SimpleEntity::class);

        $this->assertSame($indexManagerFound, $indexManagerFoundByCLass[0]);

        $this->assertTrue($indexManagerFound->isIndexable(new SimpleEntity()));

        $this->assertInstanceOf(IndexManager::class, $indexManagerFound);
        $this->assertSame($index, $indexManagerFound->getIndex());
        $this->assertSame(SimpleEntity::class, $registry->getClassByIndex('test_index'));

        $this->expectException(ManticoreException::class);
        $registry->getClassByIndex('invalid_index');

        $this->expectException(ManticoreException::class);
        $registry->getIndexManager('invalid_index');
    }
}
