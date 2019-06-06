<?php
namespace App\Http\Requests;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductSku;
use Illuminate\Validation\Rule;

class SeckillOrderRequest extends Request
{
    public function rules()
    {
        return [
            // 'address_id' => [
            //     'required',
            //     Rule::exists('user_addresses', 'id')->where('user_id', $this->user()->id)
            // ],

            // 将原本的 address_id 删除
            'address.province'      => 'required',
            'address.city'          => 'required',
            'address.district'      => 'required',
            'address.address'       => 'required',
            'address.zip'           => 'required',
            'address.contact_name'  => 'required',
            'address.contact_phone' => 'required',
            
            'sku_id'     => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!$sku = ProductSku::find($value)) {
                        return $fail('The product is not existed');
                    }
                    if ($sku->product->type !== Product::TYPE_SECKILL) {
                        return $fail('The product is not a Seckill Product');
                    }
                    if ($sku->product->seckill->is_before_start) {
                        return $fail('The Seckill Product has not started yet');
                    }
                    if ($sku->product->seckill->is_after_end) {
                        return $fail('The Seckill Product has ended already');
                    }
                    if (!$sku->product->on_sale) {
                        return $fail('The product is not on sale');
                    }
                    if ($sku->stock < 1) {
                        return $fail('The product has sold out');
                    }

                    if ($order = Order::query()
                        // 筛选出当前用户的订单
                        ->where('user_id', $this->user()->id)
                        ->whereHas('items', function ($query) use ($value) {
                            // 筛选出包含当前 SKU 的订单
                            $query->where('product_sku_id', $value);
                        })
                        ->where(function ($query) {
                            // 已支付的订单
                            $query->whereNotNull('paid_at')
                                // 或者未关闭的订单
                                ->orWhere('closed', false);
                        })
                        ->first()) {
                        if ($order->paid_at) {
                            return $fail('You have paid already');
                        }

                        return $fail('You have placed order, please  arrange your payment');
                    }
                },
            ],
        ];
    }
}