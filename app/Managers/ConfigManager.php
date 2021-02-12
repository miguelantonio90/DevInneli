<?php

namespace App\Managers;

use App\ArticleImage;
use App\OnlineConfig;
use App\Payment;
use Exception;

class ConfigManager extends BaseManager
{

    /**
     * @return mixed
     */
    public static function findAllByCompany()
    {

        if (auth()->user()['isAdmin'] === 1) {
            $configs = OnlineConfig::latest()
                ->with('company')
                ->get();
        } else {
            $company = CompanyManager::getCompanyByAdmin();
            $configs = OnlineConfig::latest()
                ->where('company_id', '=', $company->id)
                ->with('shop')
                ->get();
        }
        foreach ($configs as $key=>$config){
            $config['images']= ArticleImage::latest()->where('shop_id', '=',$config['shop_id'])->get();
        }
        return $configs;
    }

    /**
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function new($data)
    {
        $shop = OnlineConfig::create([
            'company_id' => $data['company_id'],
            'shop_id'=> $data['shop']['id'],
            'template'=> $data['template']['name']
        ]);
        foreach ($data['images'] as $k=>$image){
            $articleImage = ArticleImage::create([
                'name' => $image['name'],
                'path' => $image['path'],
                'default' => $image['default'],
                ]);
            $articleImage->shop_id = $data['shop']['id'];
            $articleImage->save();
        }
        $this->managerBy('new', $shop);
        return $shop;
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function edit($id, $data)
    {
        $payment = Payment::findOrFail($id);
        if (isset($data['name'])) {
            $payment->name = $data['name'];
        }
        if (isset($data['method'])) {
            $payment->method = $data['method'];
        }
        $this->managerBy('edit', $payment);
        $payment->save();
        return $payment;
    }

    /**
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public function delete($id)
    {
        $payment = Payment::findOrFail($id);
        $this->managerBy('delete', $payment);
        return $payment->delete();
    }

}
