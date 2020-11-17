<?php

namespace App\Managers;

use App\Discount;

class DiscountManager extends BaseManager
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
        $discount = Discount::create([
            'company_id' => $data['company_id'],
            'name' => $data['name'],
            'value' => $data['value'],
            'percent' => $data['percent'],
        ]);
        $this->managerBy('new', $discount);
        $discount->save();
        return $discount;
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function edit($id, $data)
    {
        $discount = Discount::findOrFail($id);
        if (isset($data['name'])) {
            $discount->name = $data['name'];
        }
        if (isset($data['value'])) {
            $discount->value = $data['value'];
        }
        if (isset($data['percent'])) {
            $discount->percent = $data['percent'];
        }
        $this->managerBy('edit', $discount);
        $category->save();
        return $category;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        $discount =Discount::findOrFail($id);
        $this->managerBy('delete', $discount);
        $discount->save();
        return $discount->delete();
    }

}
