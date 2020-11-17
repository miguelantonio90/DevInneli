<?php


namespace App\Managers;


class BaseManager
{

    /**
     * @param $action
     * @param $object
     * @throws \Exception
     */
    public function managerBy($action, $object): void
    {
        if ($action === 'new') {
            $object->created_by = cache()->get('userPin')['id'];
        } else if ($action === 'edit') {
            $object->updated_by =cache()->get('userPin')['id'];
        } else {
            $object->deleted_by = cache()->get('userPin')['id'];
        }
        $object->save();
    }
}
