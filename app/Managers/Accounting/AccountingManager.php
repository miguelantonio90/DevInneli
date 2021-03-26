<?php

namespace App\Managers\Accounting;

use App\Models\Accounting\AccountingAccount;
use App\Models\Accounting\AccountingCategory;
use App\Articles;
use App\Managers\BaseManager;
use App\Managers\CompanyManager;
use App\Models\Accounting\AccountMove;
use App\Shop;
use Exception;

/**
 * Class AccountingManager
 * @package App\Managers\Accounting
 */
class AccountingManager extends BaseManager
{
    /**
     * @return mixed
     */
    public static function findAllByCompany()
    {
        if (auth()->user()['isAdmin'] === 1) {
            $categories = AccountingCategory::latest()
                ->get();
        } else {
            $company = CompanyManager::getCompanyByAdmin();
            $categories = AccountingCategory::latest()
                ->where('company_id', '=', $company->id)
                ->get();
        }
        return $categories;
    }
    /**
     * @return mixed
     */
    public static function getTree()
    {
        if (auth()->user()['isAdmin'] === 1) {
            $categories = AccountingCategory::latest()
                ->with('company')
                ->with('accounts')
                ->with('sub_categories')
                ->get();
        } else {
            $company = CompanyManager::getCompanyByAdmin();
            $categories = AccountingCategory::latest()
                ->where('company_id', '=', $company->id)
                ->with('accounts')
                ->with('sub_categories')
                ->get();
        }
        $result = [];
        foreach ($categories as $key => $value) {
            if (!$value->parent_id) {
                $value['children'] = self::getChildrenValue($value);
                $result[] = $value;
            }
        }
        return $result;
    }

    /**
     * @param $value
     * @return mixed
     */
    public static function getChildrenValue($value)
    {
        $result = [];
        $pos = 0;
        if (isset($value['sub_categories'])) {
            $pos = count($value['sub_categories']);
        }
        if ($pos === 0) {
            return isset($value['accounts']) ? self::getAccounts($value['accounts'], [], $pos) : [];
        } else {
            foreach ($value['sub_categories'] as $key => $v) {
                $element = AccountingCategory::latest()
                    ->where('id', '=', $v['id'])
                    ->with('accounts')
                    ->with('sub_categories')
                    ->first();
                $element['children'] = self::getChildrenValue($element);
                $result[] = $element;
            }
        }
        $result = self::getAccounts($value['accounts'], $result, $pos);
        return $result;
    }

    public static function getAccounts($values, $result, $pos){
        foreach ($values as $key => $v) {
            $result[$pos] = AccountingAccount::latest()->where('id', '=', $v['id'])->with('category')->first();
            $pos++;
        }
        return $result;
    }

    /**
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function new($data)
    {
        $company = CompanyManager::getCompanyByAdmin();
        $category = AccountingCategory::create([
            'company_id' => $company->id,
            'name' => $data['name'],
            'color' => $data['color'] ?? '#1FBC9C'
        ]);
        $this->managerBy('new', $category);
        if ($data['parent_id']) {
            $category->parent_id = $data['parent_id'];
            $category->nature = AccountingCategory::findOrFail($data['parent_id'])->nature;
        }
        else{
            $category->nature = $data['nature'];
        }
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
        $category = AccountingCategory::findOrFail($id);
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
        $category = AccountingCategory::findOrFail($id);
        $this->managerBy('delete', $category);
        return $category->delete();
    }


}
