<?php


namespace App\Managers;


use App\Shop;
use App\ShopTypeOfOrder;
use App\TypeOfOrder;

class TypeOfOrderManager
{
    /**
     * @return mixed
     */
    public function findAllByCompany()
    {

        $company = CompanyManager::getCompanyByAdmin();
        if (auth()->user()['isAdmin'] === 1) {
            $typeOrder = TypeOfOrder::latest()
                ->with('company')
                ->with('shopTypeOfOrders')
                ->get();
        } else {
            $typeOrder = $this->getShopTypeOrder($company);
        }

        return $typeOrder;

    }

    /**
     * @param $company
     * @return array
     */
    public function getShopTypeOrder($company): array
    {
        $shopTypeOfOrder = ShopTypeOfOrder::latest()
            ->with([
                'typeOfOrder' => function ($q) use ($company) {
                    $q->where('type_of_orders.company_id', '=', $company->id)
                        ->with('shops');
                }
            ])
            ->with('shop')
            ->get();
        $result = [];
        if (count($shopTypeOfOrder) > 0) {
            foreach ($shopTypeOfOrder as $k => $value) {
                if(($value['typeOfOrder'])){
                    $result[$k]['id'] = $value['typeOfOrder']['id'];
                    $result[$k]['name'] = $value['typeOfOrder']['name'];
                    $result[$k]['description'] = $value['typeOfOrder']['description'];
                    $result[$k]['shops'] = $value['typeOfOrder']['shops'];
                    $result[$k]['idShopOrder'] = $value['id'];
                    $result[$k]['available'] = $value['available'] === 1;
                    $result[$k]['principal'] = $value['principal'] === 1;
                    $result[$k]['shopName'] = $value['shop']['name'];
                }
            }
        }
        return $result;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function new($data)
    {
        $typeOrder = TypeOfOrder::create([
            'company_id' => $data['company_id'],
            'name' => $data['name'],
            'description' => $data['description'],
        ]);

        $idShops = [];
        foreach ($data['shops'] as $sh => $shop) {
            $idShops[$sh] = $shop['id'];
            $shopType = new ShopTypeOfOrder();
            $shopType->type_of_order_id = $typeOrder->id;
            $shopType->shop_id = $shop['id'];
            $shopType->available = $shop['id'] ? true : false;
            $shopType->save();

            $typeOrder->shopTypeOfOrders()->saveMany([$shopType]);
        }
        $employShop = Shop::find($idShops);
        $typeOrder->shops()->sync($employShop);

        $typeOrder->save();

        return $typeOrder;
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function edit($id, $data)
    {
        $typeOrder = TypeOfOrder::findOrFail($id);
        $typeOrder->company_id = $data['company_id'];
        $typeOrder->name = $data['name'];
        $typeOrder->description = $data['description'];
        $idShops = [];
        $typeOrder->shopTypeOfOrders()->delete();
        foreach ($data['shops'] as $sh => $shop) {
            $idShops[$sh] = $shop['id'];
            $shopType = new ShopTypeOfOrder();
            $shopType->type_of_order_id = $typeOrder->id;
            $shopType->shop_id = $shop['id'];
            $shopType->save();
            $typeOrder->shopTypeOfOrders()->saveMany([$shopType]);
        }
        $employShop = Shop::find($idShops);
        $typeOrder->shops()->sync($employShop);

        $typeOrder->save();

        return $typeOrder;
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
