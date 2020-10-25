<?php


namespace App\Managers;


use App\Shop;
use App\TypeOfOrder;

class TypeOfOrderManager
{
    /**
     * @return mixed
     */
    public function findAllByCompany()
    {
        if (auth()->user()['isAdmin'] === 1) {
            $typeOrder = TypeOfOrder::latest()
                ->with('company')
                ->with('shops')
                ->orderBy('principal', 'DESC')
                ->get();
        } else {
            $company = CompanyManager::getCompanyByAdmin();
            $typeOrder = TypeOfOrder::latest()
                ->where('company_id', '=', $company->id)
                ->with('company')
                ->with('shops')
                ->orderBy('created_at', 'ASC')
                ->get();
        }
        foreach ($typeOrder as $k => $value) {
            $shopNames = [];
            foreach ($value['shops'] as $sh => $shop) {
                $shopNames[$sh] = $shop['name'];
            }
            $typeOrder[$k]['shopsNames'] = array_unique($shopNames);
        }

        return $typeOrder;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function new($data)
    {
        $shops = $data['shops'];
        $typeOrder = TypeOfOrder::create([
            'company_id' => $data['company_id'],
            'name' => $data['name'],
            'description' => $data['description']
        ]);
        return $this->updateData($typeOrder, $data, $shops);
    }

    /**
     * @param $typeOrder
     * @param $data
     * @param $shops
     * @return mixed
     */
    private function updateData($typeOrder, $data, $shops)
    {
        $typeOrder->name = $data['name'];
        $typeOrder->description = $data['description'];

        $idShops = [];
        foreach ($shops as $key => $value) {
            $idShops[$key] = $value['id'];
        }
        $typeOrderShop = Shop::find($idShops);

        $typeOrder->shops()->sync($typeOrderShop);
        $typeOrder->save();

        $this->setPrincipal($typeOrder->id, $data['principal']);

        return $typeOrder;
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function edit($id, $data)
    {
        $shops = $data['shops'];
        $typeOrder = TypeOfOrder::findOrFail($id);

        return $this->updateData($typeOrder, $data, $shops);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return TypeOfOrder::findOrFail($id)->delete();
    }

    public function setPrincipal($id, $data)
    {
        $company = CompanyManager::getCompanyByAdmin();
        if ($data['principal']) {
            $typeOrder = TypeOfOrder::findOrFail($id);
            $typeOrder->principal = $data['principal'];
            $typeOrder->save();

            $others = $typeOrder = TypeOfOrder::latest()
                ->where('company_id', '=', $company->id)
                ->where('id', '<>', $id)
                ->with('company')
                ->with('shops')
                ->get();
            if (count($others) > 0) {
                foreach ($others as $key => $other) {
                    $type = TypeOfOrder::findOrFail($other['id']);
                    $type->principal = false;
                    $type->save();
                }
            }
            return true;
        }
        return ['success' => true, 'message' => 'Updated all principal fields'];
    }

}
