<?php

namespace App\Managers;

use App\Payment;

class PaymentManager extends BaseManager
{

    /**
     * @return mixed
     */
    public static function findAllByCompany()
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
     * @throws \Exception
     */
    public function new($data)
    {
        $payment = Payment::create([
            'company_id' => $data['company_id'],
            'name' => $data['name'],
            'method' => $data['method'],
        ]);
        $this->managerBy('new', $payment);
        return $payment;
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @throws \Exception
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
        $this->managerBy('edit', $payment);
        $payment->save();
        return $payment;
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function delete($id)
    {
        $payment = Payment::findOrFail($id);
        $this->managerBy('delete', $payment);
        return $payment->delete();
    }

}
