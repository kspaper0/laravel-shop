<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CouponCode extends Model
{
    // 用常量的方式定义支持的优惠券类型
    const TYPE_FIXED = 'fixed';
    const TYPE_PERCENT = 'percent';

    public static $typeMap = [
        self::TYPE_FIXED   => 'Money',
        self::TYPE_PERCENT => 'Discount',
    ];

    protected $fillable = [
        'name',
        'code',
        'type',
        'value',
        'total',
        'used',
        'min_amount',
        'not_before',
        'not_after',
        'enabled',
    ];
    protected $casts = [
        'enabled' => 'boolean',
    ];
    // 指明这两个字段是日期类型
    protected $dates = ['not_before', 'not_after'];

    protected $appends = ['description'];
    // 在对模型做序列化时, 比如在控制器中返回一个模型对象
    // 这里的控制器对象在 admin 里，这个模型就会被 JSON 序列化
    // 会把 $appends 中列出的访问器也序列化进来
    // 以优惠券模型为例，优惠券的数据库结构中原本没有 description 字段
    // 如果没有将 description 放入 $appends 属性
    // 那么控制器返回优惠券模型时，前端拿到的对象没有 description 字段

    public static function findAvailableCode($length = 16)
    {
        do {
            // 生成一个指定长度的随机字符串，并转成大写
            $code = strtoupper(Str::random($length));
        // 如果生成的码已存在就继续循环
        } while (self::query()->where('code', $code)->exists());

        return $code;
    }

    public function getDescriptionAttribute()
    {
        $str = '';

        if ($this->min_amount > 0) {
            $str = 'Spend $'.str_replace('.00', '', $this->min_amount);
        }
        if ($this->type === self::TYPE_PERCENT) {
            return $str.' Get '.str_replace('.00', '', $this->value).'% Discount';
        }

        return $str.' Minus $'.str_replace('.00', '', $this->value);
    }
}
