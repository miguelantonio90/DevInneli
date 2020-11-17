<?php


namespace App\Managers;


use App\ExchangeRate;

class ExchangeRateManger extends BaseManager
{
    /**
     * @return mixed
     */
    public function findAllByCompany()
    {
        if (auth()->user()['isAdmin'] === 1) {
            $categories = ExchangeRate::latest()
                ->with('company')
                ->get();
        } else {
            $company = CompanyManager::getCompanyByAdmin();
            $categories = ExchangeRate::latest()
                ->where('company_id', '=', $company->id)
                ->get();
        }

        return $categories;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function new($data)
    {
        $company = CompanyManager::getCompanyByAdmin();
        $response = [];
        $exist = ExchangeRate::where('currency', '=', $data['country']['currencyId'])->first();
        if ($exist === null) {
            $response = ExchangeRate::create([
                'company_id' => $company->id,
                'country' => $data['country']['name'],
                'currency' => $data['country']['currencyId'],
                'change' => $data['change'],
            ]);
            $this->managerBy('new', $response);
            $response->save();
            return $response;
        } else {
            return ['success' => false, 'message' => 'exist'.$data['currencyId']];
        }
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function edit($id, $data)
    {
        $exchangeRate = ExchangeRate::findOrFail($id);
        if (isset($data['country']['name'])) {
            $exchangeRate->country = $data['country']['name'];
        }
        if (isset($data['country']['currencyId'])) {
            $exchangeRate->currency = $data['country']['currencyId'];
        }
        if (isset($data['change'])) {
            $exchangeRate->change = $data['change'];
        }
        $this->managerBy('edit', $exchangeRate);
        $exchangeRate->save();

        return $exchangeRate;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        $exchangeRate = ExchangeRate::findOrFail($id);
        $this->managerBy('delete', $exchangeRate);
        return $exchangeRate->delete();
    }

}
