<?php

namespace shop\repositories\Shop;

use shop\entities\Shop\Category;
use Yii;
use yii\caching\TagDependency;

class CategoryRepository
{
    public function get($id): Category
    {
        if (!$category = Category::findOne($id)) {
            throw new \DomainException('Category is not found.');
        }
        return $category;
    }

    public function save(Category $category): void
    {
        if (!$category->save()) {
            throw new \RuntimeException('Saving error.');
        }
        TagDependency::invalidate(Yii::$app->cache, 'categoryUrlRoute');
    }

    public function remove(Category $category): void
    {
        if (!$category->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }
}