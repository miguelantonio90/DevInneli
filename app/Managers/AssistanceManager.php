<?php


namespace App\Managers;


use App\Assistance;

class AssistanceManager
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
     * @throws \Exception
     */
    public function new($data)
    {
        return Assistance::create([
            'datetimeEntry' => new \DateTime($data['datetimeEntry']),
            'datetimeExit' => new \DateTime($data['datetimeExit']),
            'totalHours' => $data['totalHours'],
            'shop_id' => $data['shop'],
            'user_id' => $data['user'],
        ]);
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function edit($id, $data)
    {
        $assistance = Assistance::findOrFail($id);
        if ($assistance) {
            $assistance->datetimeEntry = new \DateTime($data['datetimeEntry']);
            $assistance->datetimeExit = new \DateTime($data['datetimeExit']);
            $assistance->shop_id = $data['shop']['id'];
            $assistance->user_id = $data['user']['id'];
            $assistance->save();

            return $assistance;
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return Assistance::findOrFail($id)->delete();
    }
}
