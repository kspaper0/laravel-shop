<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Moontoast\Math\BigNumber;

class InstallmentItem extends Model
{
    const REFUND_STATUS_PENDING = 'pending';
    const REFUND_STATUS_PROCESSING = 'processing';
    const REFUND_STATUS_SUCCESS = 'success';
    const REFUND_STATUS_FAILED = 'failed';

    public static $refundStatusMap = [
        self::REFUND_STATUS_PENDING    => 'Pending',
        self::REFUND_STATUS_PROCESSING => 'Refunding',
        self::REFUND_STATUS_SUCCESS    => 'Succeed',
        self::REFUND_STATUS_FAILED     => 'Failed',
    ];

    protected $fillable = [
        'sequence',
        'base',
        'fee',
        'fine',
        'due_date',
        'paid_at',
        'payment_method',
        'payment_no',
        'refund_status',
    ];
    protected $dates = ['due_date', 'paid_at'];

    public function installment()
    {
        return $this->belongsTo(Installment::class);
    }

    // 创建一个访问器，返回当前还款计划需还款的总金额
    // public function getTotalAttribute()
    // {
    //     // 小数点计算需要用 bcmath 扩展提供的函数
    //     $total = bcadd($this->base, $this->fee, 2);
    //     if (!is_null($this->fine)) {
    //         $total = bcadd($total, $this->fine, 2);
    //     }

    //     return $total;
    // }

    /*
    public function getTotalAttribute()
    {
        $total = (new BigNumber($this->base, 2))->add($this->fee);
        if (!is_null($this->fine)) {
            $total->add($this->fine);
        }

        return $total->getValue();
    }
    */

    public function getTotalAttribute()
    {
        $total = big_number($this->base)->add($this->fee);
        if (!is_null($this->fine)) {
            $total->add($this->fine);
        }

        return $total->getValue();
    }

    // 创建一个访问器，返回当前还款计划是否已经逾期
    public function getIsOverdueAttribute()
    {
        return Carbon::now()->gt($this->due_date);
    }
}
