<?php

namespace App\Managers;

use App\ExpenseCategory;

class ExpenseCategoryManager extends BaseManager
{

    /**
     * @return mixed
     */
    public function findAllByCompany()
    {
        if (auth()->user()['isAdmin'] === 1) {
            $categories = ExpenseCategory::latest()
                ->with('company')
                ->get();
        } else {
            $company = CompanyManager::getCompanyByAdmin();
            $categories = ExpenseCategory::latest()
                ->where('company_id', '=', $company->id)
                ->with('company')
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
        $category = ExpenseCategory::create([
            'company_id' => $data['company_id'],
            'name' => $data['name'],
        ]);
        if (isset($data['description'])) {
            $category->description = $data['description'];
            $category->save();
        }
        $this->managerBy('new', $category);
        return $category;
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function edit($id, $data)
    {
        $category = ExpenseCategory::findOrFail($id);
        if (isset($data['name'])) {
            $category->name = $data['name'];
        }
        if (isset($data['description'])) {
            $category->description = $data['description'];
        }
        $this->managerBy('delete', $category);
        $category->save();
        return $category;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        $category = ExpenseCategory::findOrFail($id);
        $this->managerBy('delete', $category);
        return $category->delete();
    }
}
