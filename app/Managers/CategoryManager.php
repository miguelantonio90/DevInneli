<?php

namespace App\Managers;

use App\Articles;
use App\Category;

class CategoryManager extends BaseManager
{
    /**
     * @return mixed
     */
    public function findAllByCompany()
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
    public function new($data)
    {
        $category = Category::create([
            'company_id' => $data['company_id'],
            'name' => $data['name'],
        ]);
        if (isset($data['color'])) {
            $category->color = $data['color'];
        }
        $this->managerBy('new', $category);
        $category->save();
        return $category;
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function edit($id, $data)
    {
        $category = Category::findOrFail($id);
        if (isset($data['name'])) {
            $category->name = $data['name'];
        }
        if (isset($data['color'])) {
            $category->color = $data['color'];
        }
        $this->managerBy('edit', $category);
        $category->save();
        return $category;
    }

    /**
     * @param $id
     * @return mixed
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
