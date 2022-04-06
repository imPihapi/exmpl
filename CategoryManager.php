<?php

namespace App\Manager;

use App\Entity\Category;
use App\Repository\CategoryRepository;

/**
 * Менеджер для работы с категориями
 */
final class CategoryManager
{
    /**
     * @var CategoryRepository
     */
    private CategoryRepository $categoryRepository;

    /**
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(
        CategoryRepository $categoryRepository
    )
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Генерация url для категории.
     * return: 'parentN.slug/parent1.slug/category.slug'
     *
     * @param $category
     *
     * @return string
     */
    public function generateCategoryURLBy($category): string
    {
        /** @var Category $category */
        $parentsSlugs = $this->_generateParentsURLByCategory($category);
        $slugs = [$category->getSlug(), ...$parentsSlugs];
        $slugs = array_reverse($slugs);

        return implode('/', $slugs);
    }

    /**
     * Рекурсивно собирает массив с url родителей категории.
     * return: [parent1.slug, parentN.slug]
     *
     * @param Category $category
     *
     * @return array
     */
    private function _generateParentsURLByCategory(Category $category): array
    {
        if($category->getParentCategory() instanceof Category)
        {
            return array_merge([$category->getParentCategory()->getSlug()], $this->_generateParentsURLByCategory($category->getParentCategory()));
        }

        return [];
    }

    /**
     * @param string|null $url
     *
     * @return Category|null
     */
    public function getCategoryByURL(string $url = null): ?Category
    {
        return $this->categoryRepository->getCategoryByURL($url);
    }
}