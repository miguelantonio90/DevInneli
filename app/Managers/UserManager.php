<?php


namespace App\Managers;


use App\Shop;
use App\User;
use Exception;

class UserManager extends BaseManager
{

    /**
     * @param $login
     * @return mixed
     */
    public static function loginByPincode($login)
    {
        $company = CompanyManager::getCompanyByEmail($login['email']);
        return User::where('pinCode', '=', $login['pincode'])
            ->where('company_id', '=', $company->id)
            ->with('company')
            ->with('shops')
            ->get();
    }

    /**
     * @return mixed
     */
    public function findAllByCompany()
    {
        if (auth()->user()['isAdmin'] === 1) {
            $users = User::latest()
                ->where('isAdmin', '<>', '1')
                ->with('company')
                ->with('position')
                ->with('shops')
                ->orderBy('created_at', 'ASC')
                ->get();
        } else {
            $company = CompanyManager::getCompanyByAdmin();
            $users = User::findOrFail(auth()->id())
                ->where('isAdmin', '=', '0')
                ->where('company_id', '=', $company->id)
                ->with('company')
                ->with([
                    'position' => function ($q) use ($company) {
                        $q->where('positions.company_id', '=', $company->id);
                    }
                ])
                ->with('shops')
                ->orderBy('created_at', 'ASC')
                ->groupBy()
                ->get();
        }
        foreach ($users as $k => $user) {
            $shopNames = [];
            foreach ($user['shops'] as $sh => $shop) {
                $shopNames[$sh] = $shop['name'];
            }

            $users[$k]['position']->accessPin = $user['position']['accessPin'] === 1;
            $users[$k]['position']->accessEmail = $user['position']['accessEmail'] === 1;
            $users[$k]['position']->disabled = $user['position']['key'] === 'super_manager';
            $users[$k]['shopsNames'] = array_unique($shopNames);
        }

        return $users;
    }

    /**
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function new($data)
    {
        $position = $data['position'];
        $shops = $data['shops'];
        $user = User::create([
            'company_id' => $data['company_id'],
            'position_id' => $position['id'],
            'firstName' => $data['firstName'],
            'email' => $data['email']
        ]);
        $this->managerBy('new', $user);
        return $this->updateData($user, $data, $shops, $position);
    }

    /**
     * @param $user
     * @param $data
     * @param $shops
     * @param $position
     * @return mixed
     */
    private function updateData($user, $data, $shops, $position)
    {
        $user->pinCode = $data['pinCode'];
        $user->firstName = $data['firstName'];
        $user->lastName = $data['lastName'];
        $user->email = $data['email'];
        $user->avatar = $data['avatar'];
        $user->phone = $data['phone'];
        $user->isAdmin = false;
        $user->isManager = $user['isManager'] === 1 ? $user['isManager'] : false;
        $position ? $user->position_id = $position['id'] : '';
        $idShops = [];
        foreach ($shops as $key => $value) {
            $idShops[$key] = $value['id'];
        }
        $employShop = Shop::find($idShops);

        $user->shops()->sync($employShop);

        $user->save();
        return $user;
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function edit($id, $data)
    {
        $position = $data['position'];
        $shops = $data['shops'];
        $user = User::findOrFail($id);
        $this->managerBy('edit', $user);
        return $this->updateData($user, $data, $shops, $position);
    }

    /**
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public function delete($id)
    {
        $user = User::findOrFail($id);
        $this->managerBy('new', $user);
        return $user->delete();
    }

}
