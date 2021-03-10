<?php

namespace App\Managers;

use App\Articles;
use App\Bank;
use App\Category;
use App\Shop;
use Exception;

class BankManager extends BaseManager
{
    /**
     * @return mixed
     */
    public static function findAllByCompany()
    {
        if (auth()->user()['isAdmin'] === 1) {
            $banks = Bank::latest()
                ->with('company')
                ->with('currency')
                ->get();
        } else {
            $company = CompanyManager::getCompanyByAdmin();
            $banks = Bank::latest()
                ->with('company')
                ->with('currency')
                ->where('company_id', '=', $company->id)
                ->get();
        }
        return $banks;
    }

    /**
     * @param $data
     * @return mixed
     */
    public static function getCategoriesShop($data)
    {
        return Shop::latest()->where('name', '=', $data['shopName'])->get();
    }

    /**
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function new($data)
    {
        $company = CompanyManager::getCompanyByAdmin();
        $bank = Bank::create([
            'company_id' => $company->id,
            'name' => $data['name'],
            'count_number' => $data['count_number'],
            'count_type' => $data['count_type'],
            'init_balance' => $data['init_balance']
        ]);

        $bank->color = $data['color'] ?? '#1FBC9C';
        $bank->date = $data['date'];
        if (array_key_exists('id', $data['currency'])) {
            $bank->currency_id = $data['currency']['id'];
        }
        $bank->description = $data['description'];
        $this->managerBy('new', $bank);
        $bank->save();
        return $bank;
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function edit($id, $data)
    {
        $bank = Bank::findOrFail($id);
        if (isset($data['name'])) {
            $bank->name = $data['name'];
        }
        $bank->color = $data['color'] ?? '#1FBC9C';
        $bank->count_type = $data['count_type'];
        if (array_key_exists('id', $data['currency'])) {
            $bank->currency_id = $data['currency']['id'];
        }
        $bank->count_number = $data['count_number'];
        $bank->init_balance = $data['init_balance'];
        $bank->date = $data['date'];
        $bank->description = $data['description'];
        $this->managerBy('edit', $bank);
        $bank->save();
        return $bank;
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
        $bank = Bank::findOrFail($id);
        $this->managerBy('delete', $bank);
        return $bank->delete();
    }


}
