<?php

namespace App\Managers;

use App\Articles;
use App\Box;
use App\OpenCloseBox;
use Exception;

class BoxManager extends BaseManager
{
    /**
     * @return mixed
     */
    public static function findAllByCompany()
    {
        if (auth()->user()['isAdmin'] === 1) {
            $categories = Box::latest()
                ->with('company')
                ->get();
        } else {
            $company = CompanyManager::getCompanyByAdmin();
            $categories = Box::latest()
                ->where('company_id', '=', $company->id)
                ->with('shop')
                ->get();
        }

        return $categories;
    }

    /**
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function new($data)
    {
        $company = CompanyManager::getCompanyByAdmin();
        $box = Box::create([
            'name' => $data['name'],
            'shop_id' => $data['shop']['id']
        ]);
        if (isset($data['color'])) {
            $box->color = $data['color'];
        }
        $box['company_id'] = $company->id;
        $this->managerBy('new', $box);
        $box->save();
        return $box;
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function edit($id, $data)
    {
        $box = Box::findOrFail($id);
        if (isset($data['name'])) {
            $box->name = $data['name'];
        }
        if (isset($data['color'])) {
            $box->color = $data['color'];
        }
        $this->managerBy('edit', $box);
        $box->save();
        return $box;
    }

    /**
     * @param $data
     * @return OpenCloseBox
     * @throws Exception
     */
    public function openClose($data):OpenCloseBox
    {
        return $data['box']['state'] !== 'open'? $this->createOpenClose($data):$this->editOpenClose($data);
    }

    /**
     * @param $boxId
     * @return OpenCloseBox
     */
    public function getOpenClose($boxId):OpenCloseBox{
        return OpenCloseBox::latest()
            ->where('id','=',Box::findOrFail($boxId)->open_id)
            ->with('box')
            ->with('openTo')
            ->get()[0];
    }

    /**
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function createOpenClose($data)
    {
        $company = CompanyManager::getCompanyByAdmin();
        $openClose = OpenCloseBox::create([
            'box_id'=>$data['box']['id'],
            'open_to'=>$data['openTo']['id'],
            'open_money'=>$data['cashOpen'],
        ]);
        $openClose['company_id'] = $company->id;
        $this->managerBy('new', $openClose);
        $openClose->save();
        $box = Box::latest()->where('id','=', $data['box']['id'])->get()[0];
        $box->open_id = $openClose->id;
        $box->state = 'open';
        $box->save();
        return $openClose;
    }

    /**
     * @param $data
     * @return OpenCloseBox
     * @throws Exception
     */
    public function editOpenClose($data):OpenCloseBox
    {
        $openClose = OpenCloseBox::findOrFail($data['open_id']);
        $openClose->close_money = $data['cashClose'];
        $this->managerBy('edit', $openClose);
        $openClose->save();
        $box = Box::latest()->where('id','=', $data['box']['id'])->get()[0];
        $box->open_id = null;
        $box->state = 'close';
        $box->save();
        return $openClose;
    }

    /**
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public function delete($id)
    {
        $articles = Articles::latest()
            ->where('category_id', '=', $id)
            ->get();
        foreach ($articles as $k => $article) {
            $article->category_id = null;
            $article->save();
        }
        $box = Box::findOrFail($id);
        $this->managerBy('delete', $box);
        return $box->delete();
    }




}
