<?php

namespace App\Managers;

use App\Articles;
use App\Box;
use App\OpenCloseBox;
use App\Sale;
use Exception;
use Illuminate\Support\Facades\DB;

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
    public function openClose($data): OpenCloseBox
    {
        return $data['box']['state'] !== 'open' ? $this->createOpenClose($data) : $this->editOpenClose($data);
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
            'box_id' => $data['box']['id'],
            'open_to' => $data['open_to']['id'],
            'open_money' => $data['open_money'],
        ]);
        $openClose['company_id'] = $company->id;
        $this->managerBy('new', $openClose);
        $openClose->save();
        $box = Box::latest()->where('id', '=', $data['box']['id'])->get()[0];
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
    public function editOpenClose($data): OpenCloseBox
    {
        $openClose = OpenCloseBox::findOrFail($data['id']);
        $openClose->close_money = $data['close_money'];
        $this->managerBy('edit', $openClose);
        $openClose->save();
        $box = Box::latest()->where('id', '=', $data['box']['id'])->get()[0];
        $box->state = 'close';
        $box->save();
        return $openClose;
    }

    /**
     * @param $boxId
     * @return OpenCloseBox
     */
    public function getOpenClose($boxId): OpenCloseBox
    {
        $openClose = OpenCloseBox::latest()
            ->where('id', '=', Box::findOrFail($boxId)->open_id)
            ->with('box')
            ->with('openTo')
            ->get()[0];
        $sales = Sale::latest()
            ->where('created_at', '>=', $openClose->created_at)
            ->where('state', '=', 'accepted')
            ->with('box')
            ->with('pay_sales')
            ->with('articles_shops')
            ->with('taxes')
            ->with('discounts')
            ->with('refounds')
            ->orderBy('created_at', 'ASC')
            ->get();
        $managerSale = new SaleManager();
        $openClose['sales'] = $managerSale->filterSale($sales);
        $openClose['payments'] = PaymentManager::findAllByCompany();
        foreach ($openClose['sales'] as $k=>$sale){
            foreach ($sale['pay_sales'] as $p=>$pay){
                foreach ($openClose['payments'] as $ps=>$payment){
                    if($pay['payment_id'] === $payment['id']){
                        $payment['total']+= round($pay['cant'],2);
                    }else{
                        $payment['total']+= round(0,2);
                    }
                }
            }
        }

        $refunds = DB::table('refunds')
            ->whereDate('refunds.created_at', '>=', $openClose->created_at)
            ->where('refunds.box_id', '=', $openClose->box_id)
            ->get();
        $openClose['totalRefunds'] = 0.00;
        foreach ($refunds as $r=>$refund){
            $openClose['totalRefunds'] += $refund['cant'];
        }
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
