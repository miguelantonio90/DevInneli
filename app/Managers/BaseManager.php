<?php /** @noinspection ALL */


namespace App\Managers;


use App\Category;
use App\Notification;
use App\Shop;
use Exception as Exception;

class BaseManager
{

    /**
     * @param $action
     * @param $object
     * @throws Exception
     */
    public function managerBy($action, $object): void
    {
        if ($action === 'new') {
            $object->created_by = cache()->get('userPin')['id'];
        } else {
            if ($action === 'edit') {
                $object->updated_by = cache()->get('userPin')['id'];
            } else {
                $object->deleted_by = cache()->get('userPin')['id'];
            }
        }
        $object->save();
    }

    public function findCategoryByName($name)
    {
        $company = CompanyManager::getCompanyByAdmin();
        return Category::latest()
            ->where('company_id', '=', $company->id)
            ->where('name', '=', $name)
            ->get();

    }

    public function findShopByName($name)
    {
        $company = CompanyManager::getCompanyByAdmin();
        return Shop::latest()
            ->where('company_id', '=', $company->id)
            ->where('name', '=', $name)
            ->get();

    }
}
