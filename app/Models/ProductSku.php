<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\InternalException;

class ProductSku extends Model
{
    protected $fillable = ['title', 'description', 'price', 'stock'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function decreaseStock($amount)
    {
        if ($amount < 0) {
            throw new InternalException('The dealed amount is less than 0');
        }

        return $this->newQuery()->where('id', $this->id)->where('stock', '>=', $amount)->decrement('stock', $amount);

        // $this->newQuery() 方法来获取数据库的查询构造器
        // ORM 查询构造器的写操作只会返回 true 或者 false
        // 数据库查询构造器的写操作则会返回影响的行数

    }

    public function addStock($amount)
    {
        if ($amount < 0) {
            throw new InternalException('The dealed amount is less than 0');
        }
        $this->increment('stock', $amount);
    }
}
