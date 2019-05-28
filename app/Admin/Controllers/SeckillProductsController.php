<?php
namespace App\Admin\Controllers;

use App\Models\Product;
use Encore\Admin\Form;
use Encore\Admin\Grid;

class SeckillProductsController extends CommonProductsController
{
    public function getProductType()
    {
        return Product::TYPE_SECKILL;
    }

    protected function customGrid(Grid $grid)
    {
        $grid->id('ID')->sortable();
        $grid->title('Title');
        $grid->on_sale('On Sale')->display(function ($value) {
            return $value ? 'Yes' : 'No';
        });
        $grid->price('Price');
        $grid->column('seckill.start_at', 'Start at');
        $grid->column('seckill.end_at', 'End at');
        $grid->sold_count('Sold');
    }

    protected function customForm(Form $form)
    {
        // 秒杀相关字段
        $form->datetime('seckill.start_at', 'Start at')->rules('required|date');
        $form->datetime('seckill.end_at', 'End at')->rules('required|date');
    }
}