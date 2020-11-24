<?php


namespace App\Managers;


use App\Articles;
use App\ArticlesShops;
use App\Shop;
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
     * @param CategoryManager $categoryManager
     * @param VariantManager $variantManager
     */
    public function __construct(CategoryManager $categoryManager, VariantManager $variantManager)
    {
        $this->categoryManager = $categoryManager;
        $this->variantManager = $variantManager;
    }


    public function importData($file)
    {
        $csv = file_get_contents((public_path('upload/') . '1606006301.csv'));
//        $fileName = time() . '.' . $file->getClientOriginalExtension();
//        $file->move(public_path('upload'), $fileName);
//        $csv = file_get_contents((public_path('upload/') . $fileName));
        $csv = str_replace(',variable,', '', $csv);
        $array = array_map("str_getcsv", explode("\n", $csv));
        $company = CompanyManager::getCompanyByAdmin();
        $shopsInfo = $this->insertShopsFromImportFile(explode(',', json_encode($array[0]), 18)[17], $company);
        $parent =null;
        foreach ($array as $key => $value) {
            if ($key > 0 && count($value) > 1) {
                $basicData = explode(',', json_encode($value, JSON_UNESCAPED_UNICODE));
                $jsonInfo = explode(',', (json_encode(str_replace(['"', ']', 'variable'], '', $value))), 18)[17];
                if (!$basicData[2] !== null)
                    $parent = $this->createArticleFromImportFile($basicData, $shopsInfo, '', 2, $jsonInfo);
                if (!isset($basicData[6]))
                    $this->createArticleFromImportFile($basicData, $shopsInfo, $parent->id, 6, $jsonInfo);
                if (!isset($basicData[8]))
                    $this->createArticleFromImportFile($basicData, $shopsInfo, $parent->id, 8, $jsonInfo);
                if (!isset($basicData[10]))
                    $this->createArticleFromImportFile($basicData, $shopsInfo, $parent->id, 10, $jsonInfo);
            }
        }
    }

    /**
     * @param $basicData
     * @param $shopsInfo
     * @param $parentId
     * @param $pos
     * @return Articles
     * @throws Exception
     */
    public function createArticleFromImportFile($basicData, $shopsInfo, $parentId, $pos, $jsonInfo): Articles
    {

        $categoryId = $this->getCategoryIdFromImportFile(str_replace('"', '', $basicData[3]));
        $newArticle = new Articles();
        $newArticle->ref = str_replace('"', '', $basicData[1]);
        $newArticle->name = str_replace('"', '', $basicData[$pos]);
        if (isset($categoryId))
            $newArticle->category_id = $categoryId;
        if ($parentId !== '')
            $newArticle->parent_id = $parentId;
        $newArticle->unit = str_replace('"', '', $basicData[4]) === 'Y';
        $newArticle->track_inventory = str_replace('"', '', $basicData[16]) === 'Y';
        $newArticle->price = str_replace('"', '', $basicData[11]) ?: 0.00;
        $newArticle->cost = str_replace('"', '', $basicData[12]) ?: 0.00;
        $newArticle->barCode = str_replace('"', '', $basicData[13]);
        $newArticle->save();
        $this->insertArticleShopFromImportFile($newArticle, $shopsInfo, $jsonInfo);
        return $newArticle;
    }

    public function insertArticleShopFromImportFile($article, $shopsInfo, $jsonInfo)
    {
        $shopData = explode(',', json_encode(str_replace(['"', 'variable'], '', $jsonInfo)));
        for ($i = 0, $iMax = count($shopData); $i < $iMax; $i += 3) {
            if (str_replace(['"', ']', '['], '', $shopData[$i]) === 'Y' || $shopData[$i] === '"Y') {
                $price = $shopData[$i + 1] ?: 0;
                if ($price === 'variable') {
                    $price = 0;
                }
                $data = ['shop_id' => $shopsInfo[$i / 3], 'price' => $price, 'stock' => $shopData[$i + 2], 'under_inventory' => $shopData[$i + 3]];
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
            $shopName = str_replace([']', '"'], '', explode('[', $value)[1]);
            $exist = $this->findShopByName($shopName);
            $data = ['shopName' => $shopName, 'country' => $country];
            $shops [] = count($exist) === 0 ? Shop::createFirst($data, $company)->id : $exist[0]->id;
        }
        return $shops;

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
}
