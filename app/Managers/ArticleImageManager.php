<?php


namespace App\Managers;


use App\ArticleImage;

class ArticleImageManager
{

    /**
     * @param $articleId
     * @param $images
     */
    public static function new($articleId, $images): void
    {
        if ($articleId && count($images) > 0) {
            foreach ($images as $key => $image) {
                ArticleImage::create([
                    'name' => $image['name'],
                    'path' => $image['path'],
                    'default' => $image['default'],
                    'article_id' => $articleId
                ]);
            }
        }
    }

}
