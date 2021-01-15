<?php

namespace App\Managers;

use App\Box;
use App\OpenCloseBox;
use Exception;

class OpenCloseManager extends BaseManager
{
    /**
     * @return mixed
     */
    public static function findAllByCompany()
    {
        if (auth()->user()['isAdmin'] === 1) {
            $categories = Box::latest()
                ->with('company')
                ->get();
        } else {
            $company = CompanyManager::getCompanyByAdmin();
            $categories = Box::latest()
                ->where('company_id', '=', $company->id)
                ->with('shop')
                ->get();
        }

        return $categories;
    }

    /**
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function new($data)
    {
        $company = CompanyManager::getCompanyByAdmin();
        $openClose = OpenCloseBox::create([
            'box_id' => $data['box']['id'],
            'open_to' => $data['openTo']['id'],
            'open_money' => $data['cashOpen'],
        ]);
        $openClose['company_id'] = $company->id;
        $this->managerBy('new', $openClose);
        $openClose->save();
        return $openClose;
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function edit($id, $data)
    {
        $openClose = Box::findOrFail($id);
        $openClose->close_by = $data['closeBy']['id'];
        $this->managerBy('edit', $openClose);
        $openClose->save();
        return $openClose;
    }

    /**
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public function delete($id)
    {
        $openClose = OpenCloseBox::latest()
            ->where('category_id', '=', $id)
            ->get()[0];
        $this->managerBy('delete', $openClose);
        return $openClose->delete();
    }


}
