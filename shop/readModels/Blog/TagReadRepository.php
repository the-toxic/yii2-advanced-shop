<?php

namespace shop\readModels\Blog;

use shop\entities\Blog\Tag;

class TagReadRepository
{
    public function find($slug): ?Tag
    {
        return Tag::findOne(['slug' => $slug]);
    }
}