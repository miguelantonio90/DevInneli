<?php


namespace App\Managers;


use App\Shop;
use App\User;
use Illuminate\Support\Facades\DB;

class UserManager
{

    public static function loginByPincode($login)
    {

        $company = CompanyManager::getCompanyByEmail($login['username']);

        return User::where('pinCode', '=', $login['password'])
            ->where('isAdmin', '=', '0')
            ->where('company_id', '=', $company->id)
            ->with('company')
            ->with(['position' => function ($q) use ($company) {
                $q->where('positions.company_id', '=', $company->id)
                    ->where('positions.accessPin', '=', 1);
            }])
            ->with('shops')
            ->get();
    }

    public function findAllByCompany()
    {
        if (auth()->user()['isAdmin'] === 1) {
            $users = User::latest()
                ->with('company')
                ->get();
        } else {
            $company_id = $this->getCompanyByAdmin();
            $users = User::findOrFail(auth()->id())
                ->where('isAdmin', '=', '0')
                ->where('company_id', '=', $company_id)
                ->with('company')
                ->with(['position' => function ($q) use ($company_id) {
                    $q->where('positions.company_id', '=', $company_id);
                }])
                ->with('shops')
                ->get();
        }
        return $users;
    }

    /**
     * Find Company Id using admin authenticate
     * @return string
     */
    public static function getCompanyByAdmin(): string
    {
        return DB::table('users')
            ->select('company_id')
            ->where('users.id', '=', auth()->id())
            ->get()[0]->company_id;
    }

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

    public function edit($id, $data)
    {
        $positions = $data['position'];
        $shops = $data['shops'];
        $user = User::findOrFail($id);
        return $this->updateData($user, $data, $shops, $positions);
    }

    public function delete($id)
    {
        return User::findOrFail($id)->delete();
    }

}
