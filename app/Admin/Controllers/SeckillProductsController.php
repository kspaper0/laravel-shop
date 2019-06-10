<?php
namespace App\Admin\Controllers;

use App\Models\ProductSku;
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

        // 当商品表单保存完毕时触发
        $form->saved(function (Form $form) {
            $product = $form->model();
            // 商品重新加载秒杀字段
            $product->load(['seckill']);
            // 获取当前时间与秒杀结束时间的差值
            $diff = $product->seckill->end_at->getTimestamp() - time();
            // 遍历商品 SKU
            $product->skus->each(function (ProductSku $sku) use ($diff, $product) {
                // 如果秒杀商品是上架并且尚未到结束时间
                if ($product->on_sale && $diff > 0) {
                    // 将剩余库存写入到 Redis 中，并设置该值过期时间为秒杀截止时间
                    \Redis::setex('seckill_sku_'.$sku->id, $diff, $sku->stock);
                } else {
                    // 否则将该 SKU 的库存值从 Redis 中删除
                    \Redis::del('seckill_sku_'.$sku->id);
                }
            });
        });
    }
}