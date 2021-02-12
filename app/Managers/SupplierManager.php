<?php

namespace App\Managers;

use App\Company;
use App\Notification;
use App\Supplier;
use App\User;
use Exception;

class SupplierManager extends BaseManager
{

    /**
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function new($data)
    {
        $supplier = Supplier::create([
            'company_id' => $data['company_id'],
            'expense_id' => $data['expanse'],
            'name' => $data['name']
        ]);
        $this->managerBy('new', $supplier);
        return $this->updateData($supplier, $data);
    }

    /**
     * @param $supplier
     * @param $data
     * @return mixed
     */
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
        $com = Company::latest()->where('email', '=', $data['email'])->get();
        if (count($com) > 0) {
            $user = User::latest()->where('email', '=', $data['email'])->first();
            $user->isSupplier = true;
            $user->save();
            $notification = Notification::create([
                'company_id' => $supplier->company_id,
                'to' => $user->id,
                'params' => $supplier->name,
                'msg' => 'supplier_is_register',
                'type' => 'info',
                'read' => false
            ]);
            $this->managerBy('new', $notification);
        }
        return $supplier;

    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function edit($id, $data)
    {
        $supplier = Supplier::findOrFail($id);
        if (isset($data['name'])) {
            $supplier->name = $data['name'];
        }
        if (isset($data['address'])) {
            $supplier->address = $data['address'];
        }
        $supplier->save();
        $this->managerBy('edit', $supplier);
        return $supplier;
    }

    /**
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public function delete($id)
    {
        $supplier = Supplier::findOrFail($id);
        $this->managerBy('delete', $supplier);
        return $supplier->delete();
    }

    public function getSupplierClients()
    {

        $suppliers = $this->findAllByCompany();
        $users = [];
        foreach ($suppliers as $k => $supplier) {
            $user = User::latest()->where('email', '=', $supplier->email)->get();
            if (count($user) > 0) {
                if ($user[0]->isSupplier) {
                    $users [] = $supplier;
                }
            }
        }
        return $users;


    }

    /**
     * @return mixed
     */
    public function findAllByCompany()
    {
        if (auth()->user()['isAdmin'] === 1) {
            $suppliers = Supplier::latest()
                ->with('company')
                ->get();
        } else {
            $company = CompanyManager::getCompanyByAdmin();
            $suppliers = Supplier::latest()
                ->where('company_id', '=', $company->id)
                ->with('expanse')
                ->get();
        }

        return $suppliers;
    }
}
