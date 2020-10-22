<?php

namespace App\Managers;

use App\Payment;

class PaymentManager
{

    /**
     * @return mixed
     */
    public function findAllByCompany()
    {
        if (auth()->user()['isAdmin'] === 1) {
            $payments = Payment::latest()
                ->with('company')
                ->get();
        } else {
            $company = CompanyManager::getCompanyByAdmin();
            $payments = Payment::latest()
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
        return Payment::create([
            'company_id' => $data['company_id'],
            'name' => $data['name'],
            'method' => $data['method'],
        ]);
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function edit($id, $data)
    {
        $payment = Payment::findOrFail($id);
        if (isset($data['name'])) {
            $payment->name = $data['name'];
        }
        if (isset($data['method'])) {
            $payment->method = $data['method'];
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
        return Payment::findOrFail($id)->delete();
    }

}
