<?php

namespace App\Managers;

use App\Articles;
use App\Box;

class BoxManager extends BaseManager
{
    /**
     * @return mixed
     */
    public static function findAllByCompany()
    {
        if (auth()->user()['isAdmin'] === 1) {
            $categories = Box::latest()
                ->with('company')
                ->get();
        } else {
            $company = CompanyManager::getCompanyByAdmin();
            $categories = Box::latest()
                ->where('company_id', '=', $company->id)
                ->with('shop')
                ->get();
        }

        return $categories;
    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function new($data)
    {
        $company = CompanyManager::getCompanyByAdmin();
        $box = Box::create([
            'name' => $data['name'],
            'shop_id' => $data['shop']['id']
        ]);
        if (isset($data['color'])) {
            $box->color = $data['color'];
        }
        $box['company_id'] = $company->id;
        $this->managerBy('new', $box);
        $box->save();
        return $box;
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function edit($id, $data)
    {
        $box = Box::findOrFail($id);
        if (isset($data['name'])) {
            $box->name = $data['name'];
        }
        if (isset($data['color'])) {
            $box->color = $data['color'];
        }
        $this->managerBy('edit', $box);
        $box->save();
        return $box;
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
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
        $box = Box::findOrFail($id);
        $this->managerBy('delete', $box);
        return $box->delete();
    }




}
