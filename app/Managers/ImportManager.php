<?php


namespace App\Managers;


use App\Articles;
use App\ArticlesComposite;
use App\ArticlesShops;
use App\Shop;
use App\User;
use App\Variant;
use Exception;

class ImportManager extends BaseManager
{

    /**
     * @var CategoryManager
     */
    private $categoryManager;

    /**
     * @var VariantManager
     */
    private $variantManager;

    /**
     * ImportManager constructor.
     * @param  CategoryManager  $categoryManager
     * @param  VariantManager  $variantManager
     */
    public function __construct(CategoryManager $categoryManager, VariantManager $variantManager)
    {
        $this->categoryManager = $categoryManager;
        $this->variantManager = $variantManager;
    }


    /**
     * @param $file
     * @throws Exception
     */
    public function importData($file)
    {
        $fileName = time().'.'.$file->getClientOriginalExtension();
        $file->move(public_path('upload'), $fileName);
        $csv = file_get_contents((public_path('upload/').$fileName));
        //        $csv = file_get_contents((public_path('upload/') . '1606006301.csv'));
        $csv = str_replace(',variable,', ',,', $csv);
        $array = array_map("str_getcsv", explode("\n", $csv));
        $company = CompanyManager::getCompanyByAdmin();
        $shopsInfo = $this->insertShopsFromImportFile(explode(',', json_encode($array[0]), 18)[17], $company);
        $composite = [];
        $variants = [];
        $v = ['', '', ''];
        $parent = null;
        foreach ($array as $key => $value) {
            if ($key > 0 && count($value) > 1) {
                $basicData = explode(',', json_encode($value, JSON_UNESCAPED_UNICODE));
                $jsonInfo = explode(',', (json_encode(str_replace(['"', ']'], '', $value))), 18)[17];
                if ($basicData[2] !== '""') {
                    $parent = $this->createArticleFromImportFile($basicData, $shopsInfo, '', $basicData[2], $jsonInfo,
                        $company);
                }
                if ($basicData[6] !== '""') {
                    $this->createArticleFromImportFile($basicData, $shopsInfo, $parent->id, $basicData[6], $jsonInfo,
                        $company);
                    if (str_replace('"', '', $basicData[5]) !== '') {
                        $v[0] = str_replace('"', '', $basicData[5]);
                        $variants[$v[0]]['article'] = $parent->id;
                        $variants[$v[0]]['values'] = [];
                    }
                    $variants[$v[0]]['values'] = array_merge($variants[$v[0]]['values'],
                        [str_replace('"', '', $basicData[6])]);
                }
                if ($basicData[8] !== '""') {
                    $this->createArticleFromImportFile($basicData, $shopsInfo, $parent->id, $basicData[8], $jsonInfo,
                        $company);
                    if (str_replace('"', '', $basicData[7]) !== '') {
                        $v[1] = str_replace('"', '', $basicData[7]);
                        $variants[$v[1]]['article'] = $parent->id;
                        $variants[$v[1]]['values'] = [];
                    }
                    $variants[$v[1]]['values'] = array_merge($variants[$v[1]]['values'],
                        [str_replace('"', '', $basicData[8])]);
                }
                if ($basicData[10] !== '""') {
                    $this->createArticleFromImportFile($basicData, $shopsInfo, $parent->id, $basicData[10], $jsonInfo,
                        $company);
                    if (str_replace('"', '', $basicData[9]) !== '') {
                        $variants['article'] = $parent->id;
                        $v[2] = str_replace('"', '', $basicData[9]);
                        $variants[$v[2]]['article'] = $parent->id;
                        $variants[$v[2]]['values'] = [];
                    }
                    $variants[$v[2]]['values'] = array_merge($variants[$v[2]]['values'],
                        [str_replace('"', '', $basicData[10])]);
                }
                if (str_replace(['"', '['], '', $basicData[0]) === '') {
                    $composite[] = [
                        'parent_id' => $parent->id,
                        'children_ref' => str_replace('"', '', $basicData[14]),
                        'cant' => str_replace('"', '', $basicData[15]),
                    ];
                }
            }
        }
        $this->createCompositeFromImportFile($composite, $company);
        $this->createVariantsFromImportFile($variants);
    }

    /**
     * @param $shopData
     * @param $company
     * @return array
     * @throws Exception
     */
    public function insertShopsFromImportFile($shopData, $company): array
    {

        $dataShops = explode(',', json_encode(str_replace('"', '', $shopData)));
        $shops = [];
        $country = ['id' => cache()->get('userPin')['country']];
        foreach ($dataShops as $key => $value) {
            if (array_key_exists(1, explode('[', $value))) {
                $shopName = str_replace([']', '"'], '', explode('[', $value)[1]);
                $exist = $this->findShopByName($shopName);
                $data = ['shopName' => $shopName, 'country' => $country];
                $shop = count($exist) === 0 ? Shop::createFirst($data, $company) : $exist[0];
                $shop->created_by = cache()->get('userPin')['id'];
                User::latest()
                    ->where('id', '=', cache()->get('userPin')['id'])
                    ->get()[0]->shops()->saveMany([$shop]);
                $shops[] = $shop->id;
            }
        }
        return $shops;
    }

    /**
     * @param $basicData
     * @param $shopsInfo
     * @param $parentId
     * @param $pos
     * @param $jsonInfo
     * @param $company
     * @return Articles
     * @throws Exception
     */
    public function createArticleFromImportFile($basicData, $shopsInfo, $parentId, $pos, $jsonInfo, $company): Articles
    {
        $categoryId = $this->getCategoryIdFromImportFile(str_replace('"', '', $basicData[3]));
        $newArticle = new Articles();
        $newArticle->ref = str_replace('"', '', $basicData[1]);
        $newArticle->name = str_replace('"', '', $pos);
        if (isset($categoryId)) {
            $newArticle->category_id = $categoryId;
        }
        if ($parentId !== '') {
            $newArticle->parent_id = $parentId;
        }
        $newArticle->company_id = $company->id;
        $newArticle->um = json_encode('{"name": "u", "group": "unit", "presentation": "u"}');
        $newArticle->track_inventory = str_replace('"', '', $basicData[16]) === 'Y';
        $newArticle->price = str_replace('"', '', $basicData[11]) ?: 0.00;
        $newArticle->cost = str_replace('"', '', $basicData[12]) ?: 0.00;
        $newArticle->barCode = str_replace('"', '', $basicData[13]);
        $newArticle->color = '#1FBC9C';
        $newArticle->save();
        $this->insertArticleShopFromImportFile($newArticle, $shopsInfo, $jsonInfo);
        return $newArticle;
    }

    /**
     * @param $categoryName
     * @return mixed|null
     * @throws Exception
     */
    public function getCategoryIdFromImportFile($categoryName)
    {
        $categoryId = null;
        if (isset($categoryName) && $categoryName !== '') {
            $exist = $this->findCategoryByName($categoryName);
            $categoryId = count($exist) === 0 ? $this->categoryManager->new(['name' => $categoryName])['id'] : $exist[0]['id'];
        }
        return $categoryId;
    }

    /**
     * @param $article
     * @param $shopsInfo
     * @param $jsonInfo
     * @throws Exception
     */
    public function insertArticleShopFromImportFile($article, $shopsInfo, $jsonInfo)
    {
        $shopData = explode(',', json_encode(str_replace(['"', 'variable'], '', $jsonInfo)));
        for ($i = 0, $iMax = count($shopData); $i < $iMax; $i += 3) {
            if (str_replace(['"', ']', '['], '', $shopData[$i]) === 'Y' || $shopData[$i] === '"Y') {
                $price = $shopData[$i + 1] ?: 0;
                if ($price === 'variable') {
                    $price = 0;
                }
                $data = [
                    'shop_id' => $shopsInfo[$i / 3], 'price' => $price, 'stock' => $shopData[$i + 2],
                    'under_inventory' => $shopData[$i + 3]
                ];
                $artShop = ArticlesShops::create([
                    'article_id' => $article->id,
                    'shop_id' => $data['shop_id'],
                    'price' => $data['price'],
                    'stock' => $data['stock'] !== "'variable'" ?: 0,
                    'under_inventory' => $data['under_inventory'] !== "'variable'" ?: 0
                ]);
                $this->managerBy('new', $artShop);
            }
        }
    }

    /**
     * @param $composites
     * @param $company
     */
    public function createCompositeFromImportFile($composites, $company)
    {
        foreach ($composites as $key => $value) {
            $composite = Articles::latest()
                ->where('company_id', '=', $company->id)
                ->where('ref', '=', $value['children_ref'])
                ->get()[0];
            ArticlesComposite::create([
                'article_id' => $value['parent_id'],
                'composite_id' => $composite->id,
                'cant' => $value['cant'] ?: 0,
                'price' => $composite->price ?: 0,
            ]);
        }
    }

    /**
     * @param $variants
     * @throws Exception
     */
    public function createVariantsFromImportFile($variants)
    {
        foreach ($variants as $key => $variant) {
            $v = Variant::create([
                'article_id' => $variant['article'],
                'name' => $key,
                'value' => json_encode($variant['values'])
            ]);
            $this->managerBy('new', $v);
        }
    }
}
