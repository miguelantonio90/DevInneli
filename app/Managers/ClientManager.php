<?php


namespace App\Managers;


use App\Client;
use Illuminate\Support\Facades\DB;

class ClientManager
{

    public function findAllByCompany()
    {
        $clients = [];
        if (auth()->user()['isAdmin'] === 1) {
            $clients = Client::latest()
                ->with('company')
                ->get();
        } else {
            $company_id = self::getCompanyByAdmin();
            $clients = Client::latest()
                ->where('company_id', '=', $company_id)
                ->get();
        }
        return $clients;
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
        $client = Client::create([
            'company_id' => $data['company_id'],
            'firstName' => $data['firstName'],
            'email' => $data['email']
        ]);
        return $this->updateData($client, $data);
    }

    public function edit($id, $data)
    {
        $client = Client::findOrFail($id);
        if(isset($data['firstName'])) $client->firstName = $data['firstName'];
        if(isset($data['email'])) $client->email = $data['email'];
        return $this->updateData($client, $data);
    }

    private function updateData($client, $data)
    {
        if (isset($data['phone'])) $client->phone = $data['phone'];
        if (isset($data['lastName'])) $client->lastName = $data['lastName'];
        if (isset($data['avatar'])) $client->avatar = $data['avatar'];
        if (isset($data['address'])) $client->address = $data['address'];
        if (isset($data['description'])) $client->description = $data['description'];
        if (isset($data['country'])) $client->country = $data['country'];
        if (isset($data['city'])) $client->city = $data['city'];
        if (isset($data['province'])) $client->province = $data['province'];
        if (isset($data['postalCode'])) $client->postalCode = $data['postalCode'];
        if (isset($data['barCode'])) $client->barCode = $data['barCode'];
        $client->save();
        return $client;
    }

    public function delete($id)
    {
        return Client::findOrFail($id)->delete();
    }

}
