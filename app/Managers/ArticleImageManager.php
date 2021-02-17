<?php


namespace App\Managers;


use App\ArticleImage;
use App\Articles;

class ArticleImageManager
{

    /**
     * @param $articleId
     * @param $images
     */
    public function new($articleId, $images): void
    {
        if ($articleId && count($images) > 0) {
            foreach ($images as $key => $image) {
                $art = ArticleImage::create([
                    'name' => $image['name'],
                    'path' => $image['path'],
                    'default' => $image['default']
                ]);
                $art['article_id'] = $articleId;
                $art->save();
            }
        }
    }



    /**
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public function delete($id)
    {
        $article = ArticleImage::findOrFail($id);
        $this->managerBy('delete', $article);
        return $article->delete();
    }

}
