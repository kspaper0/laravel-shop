<?php

namespace App\Admin\Controllers;

use App\Models\Product;
use Encore\Admin\Form;
use Encore\Admin\Grid;

class ProductsController extends CommonProductsController
{
    // 移除 ModelForm， 即 HasResourceActions
    public function getProductType()
    {
        return Product::TYPE_NORMAL;
    }
    // 移除 `index()` / `create()` / `edit()` 这三个方法

    protected function customGrid(Grid $grid)
    {

        $grid->model()->with(['category']);
        $grid->id('ID')->sortable();
        $grid->title('Title');
        // Laravel-Admin 支持用符号 . 来展示关联关系的字段
        $grid->column('category.name', 'Category');
        $grid->on_sale('On Sale')->display(function ($value) {
            return $value ? 'Yes' : 'No'; 
        });
        $grid->price('Price');
        $grid->rating('Rating');
        $grid->sold_count('Sold');
        $grid->review_count('Review');
        
    }

    protected function customForm(Form $form)
    {
        // 普通商品没有额外的字段，因此这里不需要写任何代码
    }

}
