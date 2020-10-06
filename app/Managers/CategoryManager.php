<?php


namespace App\Managers;


use App\Category;
use Illuminate\Support\Facades\DB;

class CategoryManager
{

    public function findAllByCompany()
    {
        $categories = [];
        if (auth()->user()['isAdmin'] === 1) {
            $categories = Category::latest()
                ->with('company')
                ->get();
        } else {
            $company_id = self::getCompanyByAdmin();
            $categories = Category::latest()
                ->where('company_id', '=', $company_id)
                ->get();
        }
        return $categories;
    }


    /**
     * Find Company Id using admin authenticate
     * @return string
     */
    public static function getCompanyByAdmin(): string
    {
        return DB::table('users')
            ->select('company_id')
            ->where('users.id', '=', auth()->id())
            ->get()[0]->company_id;
    }

    public function new($data)
    {
        $category = Category::create([
            'company_id' => $data['company_id'],
            'name' => $data['name'],
        ]);
        if (isset($data['color'])){
            $category->color =$data['color'];
            $category->save();
        }
        return $category;
    }

    public function edit($id, $data)
    {
        $category = Category::findOrFail($id);
        if (isset($data['name'])) $category->name = $data['name'];
        if (isset($data['color'])) $category->color = $data['color'];
        $category->save();
        return $category;
    }

    public function delete($id)
    {
        return Category::findOrFail($id)->delete();
    }

}
