<?php

namespace App\Managers;

use App\Articles;
use App\Category;
use App\Shop;
use Exception;

class CategoryManager extends BaseManager
{
    /**
     * @return mixed
     */
    public static function findAllByCompany()
    {
        if (auth()->user()['isAdmin'] === 1) {
            $categories = Category::latest()
                ->with('company')
                ->get();
        } else {
            $company = CompanyManager::getCompanyByAdmin();
            $categories = Category::latest()
                ->where('company_id', '=', $company->id)
                ->get();
        }
        return $categories;
    }

    /**
     * @param $data
     * @return mixed
     */
    public static function getCategoriesShop($data)
    {
        var_dump($data);
        return Shop::latest()->where('name', '=', $data['shopName'])->get();
    }

    /**
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function new($data)
    {
        $company = CompanyManager::getCompanyByAdmin();
        $category = Category::create([
            'company_id' => $company->id,
            'name' => $data['name'],
        ]);
        $category->color = $data['color'] ?? '#1FBC9C';
        $this->managerBy('new', $category);
        $category->save();
        return $category;
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function edit($id, $data)
    {
        $category = Category::findOrFail($id);
        if (isset($data['name'])) {
            $category->name = $data['name'];
        }
        $category->color = $data['color'] ?? '#1FBC9C';
        $this->managerBy('edit', $category);
        $category->save();
        return $category;
    }

    /**
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public function delete($id)
    {
        $articles = Articles::latest()
            ->where('category_id', '=', $id)
            ->get();
        foreach ($articles as $k => $article) {
            $article->category_id = null;
            $article->save();
        }
        $category = Category::findOrFail($id);
        $this->managerBy('delete', $category);
        return $category->delete();
    }


}
