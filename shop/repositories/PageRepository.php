<?php

namespace shop\repositories;

use shop\entities\Page;
use Yii;
use yii\caching\TagDependency;

class PageRepository
{
    public function get($id): Page
    {
        if (!$page = Page::findOne($id)) {
            throw new \RuntimeException('Page is not found.');
        }
        return $page;
    }

    public function save(Page $page): void
    {
        if (!$page->save()) {
            throw new \RuntimeException('Saving error.');
        }
        TagDependency::invalidate(Yii::$app->cache, 'pageUrlRoute');
    }

    public function remove(Page $page): void
    {
        if (!$page->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }
}