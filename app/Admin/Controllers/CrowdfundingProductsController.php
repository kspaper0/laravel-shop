<?php

namespace App\Admin\Controllers;

use App\Models\Product;
use App\Models\CrowdfundingProduct;
use Encore\Admin\Form;
use Encore\Admin\Grid;

class CrowdfundingProductsController extends CommonProductsController
{
    // 移除 HasResourceActions
    public function getProductType()
    {
        return Product::TYPE_CROWDFUNDING;
    }
    // 移除 `index()` / `create()` / `edit()` 这三个方法
    
    protected function customGrid(Grid $grid)
    {
        
        $grid->id('ID')->sortable();
        $grid->title('Title');
        $grid->on_sale('On Sale')->display(function ($value) {
            return $value ? 'Yes' : 'No';
        });
        $grid->price('Price');

        // 展示众筹相关字段
        $grid->column('crowdfunding.target_amount', 'Targeted Amount');
        $grid->column('crowdfunding.end_at', 'End at');
        $grid->column('crowdfunding.total_amount', 'Current Amount');
        $grid->column('crowdfunding.status', ' Status')->display(function ($value) {
            return CrowdfundingProduct::$statusMap[$value];
        });

    }

    protected function customForm(Form $form)
    {

        $form->text('crowdfunding.target_amount', 'Targeted Amount')->rules('required|numeric|min:0.01');
        $form->datetime('crowdfunding.end_at', 'End at')->rules('required|date');
    }
}
