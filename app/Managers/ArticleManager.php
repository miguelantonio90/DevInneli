<?php


namespace App\Managers;


use App\Articles;
use App\Category;
use App\Position;
use App\Shop;
use App\User;
use Illuminate\Support\Facades\DB;

class ArticleManager
{

    public function findAllByCompany()
    {
        $articles = [];
        if (auth()->user()['isAdmin'] === 1) {
            $articles = Articles::latest()
                ->with('shops')
                ->with('categories')
                ->get();
        } else {
            $company_id = self::getCompanyByAdmin();
            $articles =  DB::table('articles')
                ->select(
                    'articles.*',
                    'shops.*'
                )
                ->join('categories', 'categories.id', '=', 'articles.category_id')
                ->join('articles_shop', 'articles.id', '=', 'articles_shop.articles_id')
                ->join('shops', 'shops.id', '=', 'articles_shop.shop_id')
                ->where('shops.company_id', '=',$company_id)
                ->get();
        }
        return $articles;
    }


    /**
     * Find Company Id using admin authenticate
     * @return string
     */
    public static function getCompanyByAdmin(): string
    {
        return DB::table('users')
            ->select('company_id')
            ->where('users.id', '=', auth()->id())
            ->get()[0]->company_id;
    }

    public function new($data)
    {
        $category = $data['category'];
        $shops = $data['shops'];
        $article = Articles::create([
            'category_id' => $category['id'],
            'name' => $data['name'],
        ]);
        return $this->updateData($article, $data, $shops);
    }

    private function updateData($article, $data, $shops)
    {
        if(isset($data['barCode']))$article->barCode = $data['barCode'];
        if(isset($data['composite']))$article->composite = $data['composite'];
        if(isset($data['cost']))$article->cost = $data['cost'];
        if(isset($data['inventory']))$article->inventory = $data['inventory'];
        if(isset($data['itbis']))$article->itbis = $data['inventory'];
        if(isset($data['lay']))$article->lay = $data['lay'];
        if(isset($data['price']))$article->price = $data['price'];
        if(isset($data['ref']))$article->ref = $data['ref'];
        if(isset($data['track_inventory']))$article->track_inventory = $data['track_inventory'];
        if(isset($data['unit']))$article->unit = $data['unit'];
        $idShops = [];
        foreach ($shops as $key => $value) {
            $idShops[$key] = $value['id'];
        }
        $employShop = Shop::find($idShops);
        $article->shops()->sync($employShop);
        $article->save();
        return $article;
    }

    public function edit($id, $data)
    {
        $category = $data['position'];
        $shops = $data['shops'];
        $article = User::findOrFail($id);
        return $this->updateData($article, $data, $shops);
    }

    public function delete($id)
    {
        return User::findOrFail($id)->delete();
    }

}
