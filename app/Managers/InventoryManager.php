<?php


namespace App\Managers;


use App\Shop;
use App\Inventory;

class InventoryManager
{
    public function new($data)
    {
        return Inventory::create([
            'no_facture'=>$data['noFacture'],
            'pay'=>$data['pay']
        ]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return Inventory::findOrFail($id)->delete();
    }

}
