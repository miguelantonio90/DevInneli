<?php


namespace App\Managers;


use App\Shop;
use App\User;

class UserManager
{

    /**
     * @param $login
     * @return mixed
     */
    public static function loginByPincode($login)
    {

        $company = CompanyManager::getCompanyByEmail($login['email']);

        return User::where('pinCode', '=', $login['pincode'])
            ->where('isAdmin', '=', '0')
            ->where('company_id', '=', $company->id)
            ->with('company')
            ->with([
                'position' => function ($q) use ($company) {
                    $q->where('positions.company_id', '=', $company->id)
                        ->where('positions.accessPin', '=', 1);
                }
            ])
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
                ->with('company')
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
                ->get();
        }
        return $users;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function new($data)
    {
        $positions = $data['positions'];
        $shops = $data['shops'];
        $user = User::create([
            'company_id' => $data['company_id'],
            'position_id' => $positions['id'],
            'firstName' => $data['firstName'],
            'email' => $data['email']
        ]);
        return $this->updateData($user, $data, $shops, $positions);
    }

    /**
     * @param $user
     * @param $data
     * @param $shops
     * @param $positions
     * @return mixed
     */
    private function updateData($user, $data, $shops, $positions)
    {
        $user->pinCode = $data['pinCode'];
        $user->firstName = $data['firstName'];
        $user->lastName = $data['lastName'];
        $user->email = $data['email'];
        $user->avatar = $data['avatar'];
        $user->phone = $data['phone'];
        $user->isAdmin = 0;
        $user->isManager = $user['isManager'] === 1 ? $user['isManager'] : 0;
        $positions ? $user->position_id = $positions['id'] : '';
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
     */
    public function edit($id, $data)
    {
        $positions = $data['position'];
        $shops = $data['shops'];
        $user = User::findOrFail($id);
        return $this->updateData($user, $data, $shops, $positions);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return User::findOrFail($id)->delete();
    }

}
