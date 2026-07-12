<?php

namespace App\Services;

class CategoryTraversalService
{
    /**
     * Return category IDs in Depth-First Search (DFS) order.
     *
     * @param array $adjacency
     * @return array<int>
     */
    public function depthFirstIds(array $adjacency): array
    {
        $result = [];

        $this->traverse(
        null,
            $adjacency,
            $result
        );

        return $result;
    }

    /**
     * Return all descendant IDs of a category.
     *
     * The given category ID is NOT included in the result.
     *
     * @param int $categoryId
     * @param array $adjacency
     * @return array<int>
     */
    public function descendantIds(
        int $categoryId,
        array $adjacency
    ): array {

        $result = [];

        $this->traverse(
            $categoryId,
            $adjacency,
            $result
        );

        return $result;
    }

    /**
     * Determine whether a category exists in the adjacency list.
     */
    public function exists(
        int $categoryId,
        array $adjacency
    ): bool {

        return array_key_exists(
            $categoryId,
            $adjacency
        );
    }

    /**
     * Recursive DFS traversal.
     *
     * @param int|null $parentId
     * @param array $adjacency
     * @param array<int> $result
     */
    private function traverse(
        ?int $parentId,
        array $adjacency,
        array &$result
    ): void {

        foreach ($adjacency[$parentId] ?? [] as $childId) {

            $result[] = $childId;

            $this->traverse(
                $childId,
                $adjacency,
                $result
            );
        }
    }
}
