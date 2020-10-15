<?php

namespace App\Managers;

use App\Category;

class CategoryManager
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
            $category->save();
        }
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
        $category->save();
        return $category;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return Category::findOrFail($id)->delete();
    }

}
