<?php


namespace App\Managers;


use App\Assistance;
use Carbon\Carbon;
use Exception;

class AssistanceManager extends BaseManager
{

    /**
     * @return mixed
     */
    public function findAll()
    {
        return Assistance::latest()
            ->with('shop')
            ->with('user')
            ->get();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findByShop($id)
    {
        return Assistance::where('shop_id', '=', $id)
            ->with('user')
            ->with('shop')
            ->get();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findByUser($id)
    {
        return Assistance::where('user_id', '=', $id)
            ->with('user')
            ->with('shop')
            ->get();
    }

    /**
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function new($data)
    {
        $entry = trim(preg_replace("/\([^)]+\)/", "", $data['datetimeEntry']));
        $exit = trim(preg_replace("/\([^)]+\)/", "", $data['datetimeExit']));

        $assistance = Assistance::create([
            'datetimeEntry' => Carbon::parse($entry),
            'datetimeExit' => Carbon::parse($exit),
            'totalHours' => $data['totalHours'],
            'shop_id' => $data['shop'],
            'user_id' => $data['user'],
            'company_id' => (CompanyManager::getCompanyByAdmin())->id
        ]);
        $this->managerBy('new', $assistance);
        $assistance->save();
        return $assistance;
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function edit($id, $data)
    {
        $assistance = Assistance::findOrFail($id);
        $entry = trim(preg_replace("/\([^)]+\)/", "", $data['datetimeEntry']));
        $exit = trim(preg_replace("/\([^)]+\)/", "", $data['datetimeExit']));

        if ($assistance) {
            $assistance->datetimeEntry = Carbon::parse($entry);
            $assistance->datetimeExit = Carbon::parse($exit);
            $assistance->shop_id = $data['shop']['id'];
            $assistance->user_id = $data['user']['id'];
            $assistance->company_id = (CompanyManager::getCompanyByAdmin())->id;
            $this->managerBy('edit', $assistance);
            $assistance->save();
            return $assistance;
        }
    }

    /**
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public function delete($id)
    {
        $assistance = Assistance::findOrFail($id);
        $this->managerBy('new', $assistance);
        return $assistance->delete();
    }
}
