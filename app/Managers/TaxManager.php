<?php

namespace App\Managers;

use App\Tax;

class TaxManager
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
        return Tax::create([
            'company_id' => $data['company_id'],
            'name' => $data['name'],
            'value' => $data['value'],
            'percent' => $data['percent'],
        ]);
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function edit($id, $data)
    {
        $category = Tax::findOrFail($id);
        if (isset($data['name'])) {
            $category->name = $data['name'];
        }
        if (isset($data['value'])) {
            $category->value = $data['value'];
        }
        if (isset($data['percent'])) {
            $category->percent = $data['percent'];
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
        return Tax::findOrFail($id)->delete();
    }

}
