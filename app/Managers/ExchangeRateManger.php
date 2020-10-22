<?php


namespace App\Managers;


use App\ExchangeRate;

class ExchangeRateManger
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
        foreach ($data['country'] as $k => $value) {
            $exist = ExchangeRate::where('currency', '=', $value['currencyId'])->first();
            if ($exist === null) {
                $response[$k] = ExchangeRate::create([
                    'company_id' => $company->id,
                    'country' => $value['name'],
                    'currency' => $value['currencyId']
                ]);

                return $response;
            } else {
                $response[$k] = ['success' => false, 'message' => 'exist'.$value['currencyId']];
            }

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
        $exchangeRate->save();

        return $exchangeRate;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return ExchangeRate::findOrFail($id)->delete();
    }

}
