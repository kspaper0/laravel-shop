<?php

namespace App\Http\Requests;

use App\Models\ProductSku;

class AddCartRequest extends Request
{
    public function rules()
    {
        return [
            'sku_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!$sku = ProductSku::find($value)) {
                        $fail('The product is not existed');
                        return;
                    }
                    if (!$sku->product->on_sale) {
                        $fail('The product is not on sale');
                        return;
                    }
                    if ($sku->stock === 0) {
                        $fail('The product has sold out');
                        return;
                    }
                    if ($this->input('amount') > 0 && $sku->stock < $this->input('amount')) {
                        $fail('The stock is not enough');
                        return;
                    }
                },
            ],
            'amount' => ['required', 'integer', 'min:1'],
        ];
    }

    public function attributes()
    {
        return [
            'amount' => 'Product Amount'
        ];
    }

    public function messages()
    {
        return [
            'sku_id.required' => 'Please choose a product'
        ];
    }
}