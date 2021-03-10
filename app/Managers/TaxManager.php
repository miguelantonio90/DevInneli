<?php

namespace App\Managers;

use App\Articles;
use App\Tax;
use Exception;

class TaxManager extends BaseManager
{

    /**
     * @return mixed
     */
    public function findAllByCompany()
    {
        if (auth()->user()['isAdmin'] === 1) {
            $categories = Tax::latest()
                ->with('company')
                ->get();
        } else {
            $company = CompanyManager::getCompanyByAdmin();
            $categories = Tax::latest()
                ->where('company_id', '=', $company->id)
                ->with('article')
                ->get();
        }

        return $categories;
    }

    /**
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function new($data)
    {
        $tax = Tax::create([
            'company_id' => $data['company_id'],
            'name' => $data['name'],
            'value' => $data['value'],
            'type' => $data['type'],
            'existing' => $data['existing'],
            'percent' => $data['percent'],
        ]);
        $this->managerBy('new', $tax);
        if ($tax->existing) {
            $this->addToAllArticle($tax);
        }
        return $tax;
    }

    /**
     * @param  Tax  $tax
     */
    public function addToAllArticle(Tax $tax): void
    {
        $company = CompanyManager::getCompanyByAdmin();
        $articles = Articles::latest()
            ->where('company_id', '=', $company->id)
            ->get();
        if (count($articles) > 0) {
            $idArticles = [];
            foreach ($articles as $key => $article) {
                $idArticles[] = $article->id;
            }
            $tax->article()->sync($idArticles);
            $tax->save();
        }

    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function edit($id, $data)
    {
        $tax = Tax::findOrFail($id);
        if (isset($data['name'])) {
            $tax->name = $data['name'];
        }
        if (isset($data['value'])) {
            $tax->value = $data['value'];
        }
        if (isset($data['percent'])) {
            $tax->percent = $data['percent'];
        }
        $this->managerBy('edit', $tax);
        $tax->save();
        return $tax;
    }

    /**
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public function delete($id)
    {
        $tax = Tax::findOrFail($id);
        $this->managerBy('delete', $tax);
        return $tax->delete();
    }

}
