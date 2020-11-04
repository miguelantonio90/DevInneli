<?php

namespace App\Managers;

use App\Discount;

class DiscountManager
{

    /**
     * @return mixed
     */
    public function findAllByCompany()
    {
        if (auth()->user()['isAdmin'] === 1) {
            $categories = Discount::latest()
                ->with('company')
                ->get();
        } else {
            $company = CompanyManager::getCompanyByAdmin();
            $categories = Discount::latest()
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
        return Discount::create([
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
        $category = Discount::findOrFail($id);
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
        return Discount::findOrFail($id)->delete();
    }

}
