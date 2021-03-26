<?php

namespace App\Managers\Accounting;

use App\Models\Accounting\AccountingAccount;
use App\Models\Accounting\AccountMove;
use App\Managers\BaseManager;
use App\Shop;
use Exception;

class MoveManager extends BaseManager
{
    /**
     * @return mixed
     */
    public static function findAllByCompany()
    {
        return [];
    }

    /**
     * @param $data
     * @return mixed
     */
    public static function getMoveAccount($data)
    {
        return AccountMove::latest()->where('account_id', '=', $data['account'])->get();
    }

    /**
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function new($data)
    {
        $move = AccountMove::create([
            'account_id' => $data['account_id'],
            'debit' => $data['debit'],
            'credit' => $data['credit'],
            'detail' => $data['detail']
        ]);
        $move->ref = $data['ref'];
        $move->date = $data['date'];
        $move->save();
        $account = AccountingAccount::latest()->where('id', '=', $data['account_id'])->with('category')->first();
        if($account['category']->nature === 'creditor') {
            $account->current_balance = $account->current_balance + $move->credit - $move->debit;
        }else{
            $account->current_balance = $account->current_balance - $move->credit + $move->debit;
        }
        $account->save();
        $this->managerBy('new', $move);
        $account->save();
        return $move;
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function edit($id, $data)
    {
        $account = AccountMove::findOrFail($id);
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
        $move = AccountMove::findOrFail($id);
        $account = AccountingAccount::latest()->where('id', '=', $move->account_id)->with('category')->first();
        if($account['category']->nature === 'creditor') {
            $account->current_balance = $account->current_balance - $move->credit + $move->debit;
        }else{
            $account->current_balance = $account->current_balance + $move->credit - $move->debit;
        }
        $account->save();
        $this->managerBy('delete', $move);
        $move->delete();
        return $move;
    }


}
