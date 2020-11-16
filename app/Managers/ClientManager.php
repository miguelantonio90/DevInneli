<?php

namespace App\Managers;

use App\Client;

class ClientManager extends BaseManager
{

    /**
     * @return mixed
     */
    public function findAllByCompany()
    {
        if (auth()->user()['isAdmin'] === 1) {
            $clients = Client::latest()
                ->with('company')
                ->get();
        } else {
            $company = CompanyManager::getCompanyByAdmin();
            $clients = Client::latest()
                ->where('company_id', '=', $company->id)
                ->get();
        }
        return $clients;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function new($data)
    {
        $client = Client::create([
            'company_id' => $data['company_id'],
            'firstName' => $data['firstName'],
            'email' => $data['email']
        ]);
        return $this->updateData($client, $data, true);
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function edit($id, $data)
    {
        $client = Client::findOrFail($id);
        if (isset($data['firstName'])) {
            $client->firstName = $data['firstName'];
        }
        if (isset($data['email'])) {
            $client->email = $data['email'];
        }
        return $this->updateData($client, $data, false);
    }
    /**
     * @param $client
     * @param $data
     * @param bool $new
     * @return mixed
     */
    private function updateData($client, $data, $new)
    {
        $new? $this->managerBy('new', $client) : $this->managerBy('edit', $client);
        if (isset($data['phone'])) {
            $client->phone = $data['phone'];
        }
        if (isset($data['lastName'])) {
            $client->lastName = $data['lastName'];
        }
        if (isset($data['avatar'])) {
            $client->avatar = $data['avatar'];
        }
        if (isset($data['address'])) {
            $client->address = $data['address'];
        }
        if (isset($data['description'])) {
            $client->description = $data['description'];
        }
        if (isset($data['country'])) {
            $client->country = $data['country'];
        }
        if (isset($data['city'])) {
            $client->city = $data['city'];
        }
        if (isset($data['province'])) {
            $client->province = $data['province'];
        }
        if (isset($data['postalCode'])) {
            $client->postalCode = $data['postalCode'];
        }
        if (isset($data['barCode'])) {
            $client->barCode = $data['barCode'];
        }
        $client->save();
        return $client;
    }


    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        $client = Client::findOrFail($id);
        $this->managerBy('delete', $client);
        return $client->delete();
    }

}
