<?php


namespace App\Managers;


use App\Modifiers;
use App\Shop;

class ModifiersManager extends BaseManager
{

    /**
     * @return mixed
     */
    public function findAllByCompany()
    {
        if (auth()->user()['isAdmin'] === 1) {
            $modifiers = Modifiers::latest()
                ->with('company')
                ->with('shops')
                ->get();
        } else {
            $company = CompanyManager::getCompanyByAdmin();
            $modifiers = Modifiers::latest()
                ->where('company_id', '=', $company->id)
                ->with('shops')
                ->get();

            foreach ($modifiers as $k => $user) {
                $shopNames = [];
                foreach ($user['shops'] as $sh => $shop) {
                    $shopNames[$sh] = $shop['name'];
                }
                $modifiers[$k]['shopsNames'] = array_unique($shopNames);
            }

        }

        return $modifiers;
    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function new($data)
    {
        $modifier = Modifiers::create([
            'company_id' => $data['company_id'],
            'name' => $data['name'],
            'value' => $data['value'],
            'percent' => $data['percent'],
        ]);
        $idShops = [];
        foreach ($data['shops'] as $sh => $shop) {
            $idShops[$sh] = $shop['id'];
        }
        $shops = Shop::find($idShops);
        $modifier->shops()->sync($shops);
        $this->managerBy('new', $modifier);
        $modifier->save();
        return $modifier;
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function edit($id, $data)
    {
        $modifier = Modifiers::findOrFail($id);
        if (isset($data['name'])) {
            $modifier->name = $data['name'];
        }
        if (isset($data['value'])) {
            $modifier->value = $data['value'];
        }
        if (isset($data['percent'])) {
            $modifier->percent = $data['percent'];
        }
        $idShops = [];
        foreach ($data['shops'] as $sh => $shop) {
            $idShops[$sh] = $shop['id'];
        }
        $shops = Shop::find($idShops);
        $modifier->shops()->sync($shops);
        $this->managerBy('edit', $modifier);
        $modifier->save();
        return $modifier;
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function delete($id)
    {
        $modifier = Modifiers::findOrFail($id);
        $this->managerBy('delete', $modifier);
        $modifier->save();
        return $modifier->delete();
    }
}
