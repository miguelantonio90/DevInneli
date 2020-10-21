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
        return Supplier::create([
            'company_id' => $data['company_id'],
            'name' => $data['name'],
            'address' => $data['address'],
        ]);
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
