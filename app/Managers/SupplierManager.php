<?php

namespace App\Managers;

use App\Supplier;

class SupplierManager
{

    /**
     * @return mixed
     */
    public function findAllByCompany()
    {
        if (auth()->user()['isAdmin'] === 1) {
            $payments = Supplier::latest()
                ->with('company')
                ->get();
        } else {
            $company = CompanyManager::getCompanyByAdmin();
            $payments = Supplier::latest()
                ->where('company_id', '=', $company->id)
                ->with('expanse')
                ->get();
        }

        return $payments;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function new($data)
    {
        $supplier = Supplier::create([
            'company_id' => $data['company_id'],
            'expense_id' => $data['expanse'],
            'name' => $data['name']
        ]);
        return $this->updateData($supplier, $data);
    }

    public function updateData($supplier, $data)
    {
        if (isset($data['name'])) {
            $supplier->name = $data['name'];
        }
        if (isset($data['address'])) {
            $supplier->address = $data['address'];
        }
        if (isset($data['address'])) {
            $supplier->address = $data['address'];
        }
        if (isset($data['identity'])) {
            $supplier->identity = $data['identity'];
        }
        if (isset($data['phone'])) {
            $supplier->phone = $data['phone'];
        }
        if (isset($data['email'])) {
            $supplier->email = $data['email'];
        }
        if (isset($data['country'])) {
            $supplier->country = $data['country'];
        }
        if (isset($data['contract'])) {
            $supplier->contract = $data['contract'];
        }
        if (isset($data['note'])) {
            $supplier->note = $data['note'];
        }
        $supplier->save();
        return $supplier;

    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function edit($id, $data)
    {
        $payment = Supplier::findOrFail($id);
        if (isset($data['name'])) {
            $payment->name = $data['name'];
        }
        if (isset($data['address'])) {
            $payment->address = $data['address'];
        }
        $payment->save();
        return $payment;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return Supplier::findOrFail($id)->delete();
    }

}
