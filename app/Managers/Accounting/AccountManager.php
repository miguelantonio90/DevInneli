<?php

namespace App\Managers\Accounting;

use App\Managers\CompanyManager;
use App\Models\Accounting\AccountingAccount;
use App\Articles;
use App\Managers\BaseManager;
use App\Models\Accounting\AccountingCategory;
use App\Shop;
use Exception;

class AccountManager extends BaseManager
{
    /**
     * @return mixed
     */
    public static function findAllByCompany()
    {
        $company = CompanyManager::getCompanyByAdmin();
        $categories = AccountingCategory::latest()
            ->where('company_id', $company->id)
            ->select('id')
            ->get();
        if (auth()->user()['isAdmin'] === 1) {
            $accounts = AccountingAccount::latest()
                ->whereIn('category_id', $categories)
                ->with('category')
                ->get();
        } else {
            $accounts = AccountingAccount::latest()
                ->whereIn('category_id', $categories)
                ->with('category')
                ->get();
        }
        return $accounts;
    }

    /**
     * @param $data
     * @return mixed
     */
    public static function getCategoriesShop($data)
    {
        return Shop::latest()->where('name', '=', $data['shopName'])->get();
    }

    public function getAccountById($id)
    {
        return AccountingAccount::latest()->where('id','=', $id)->with('category')->first();
    }

    /**
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function new($data)
    {
        $account = AccountingAccount::create([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'code' => $data['code'],
        ]);
        $account->init_balance = $data['init_balance'];
        $account->current_balance = $data['init_balance'];
        $account->description = $data['description'];
        $this->managerBy('new', $account);
        $account->save();
        return $account;
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function edit($id, $data)
    {
        $account = AccountingAccount::findOrFail($id);
        $account->name = $data['name'];
        $account->code = $data['code'];
        $account->description = $data['description'];
        $this->managerBy('edit', $account);
        $account->save();
        return $account;
    }

    /**
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public function delete($id)
    {
        $account = AccountingAccount::findOrFail($id);
        $this->managerBy('delete', $account);
        return $account->delete();
    }


}
