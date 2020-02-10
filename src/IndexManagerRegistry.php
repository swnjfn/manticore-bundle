<?php

declare(strict_types=1);

namespace Versh23\ManticoreBundle;

class IndexManagerRegistry
{
    private $indexMap;
    private $classMap;

    public function addIndexManager(IndexManager $manager)
    {
        $index = $manager->getIndex();
        $this->indexMap[$index->getName()] = $manager;
        $this->classMap[$index->getClass()][] = $index->getName();
    }

    public function getClassByIndex(string $index): string
    {
        foreach ($this->classMap as $class => $indexes) {
            if (false !== array_search($index, $indexes)) {
                return $class;
            }
        }

        throw new ManticoreException('no class found by index '.$index);
    }

    public function getIndexManager(string $index): IndexManager
    {
        if (!isset($this->indexMap[$index])) {
            throw new ManticoreException('no indexManager found by index '.$index);
        }

        return $this->indexMap[$index];
    }
}