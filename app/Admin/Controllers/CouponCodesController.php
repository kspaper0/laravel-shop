<?php

namespace App\Admin\Controllers;

use \App\Models\CouponCode;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class CouponCodesController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('Coupon list')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed   $id
     * @param Content $content
     * @return Content
     */
    // public function show($id, Content $content)
    // {
    //     return $content
    //         ->header('Detail')
    //         ->description('description')
    //         ->body($this->detail($id));
    // }

    /**
     * Edit interface.
     *
     * @param mixed   $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit Coupon')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Publish Coupon')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CouponCode);
        
        // 默认按创建时间倒序排序
        $grid->model()->orderBy('created_at', 'desc');
        $grid->id('ID')->sortable();
        $grid->name('Name');
        $grid->code('Code');
        $grid->description('Description');
        $grid->column('usage', 'Usage')->display(function ($value) {
            return "{$this->used} / {$this->total}";
        });

        // $grid->type('Type')->display(function($value) {
        //     return CouponCode::$typeMap[$value];
        // });
        // $grid->value('Value')->display(function($value) {
        //     return $this->type === CouponCode::TYPE_FIXED ? '$'.$value : $value.'%';
        // });
        // $grid->min_amount('Min Amount');
        // $grid->total('Total');
        // $grid->used('Used');
        $grid->enabled('Enabled')->display(function($value) {
            return $value ? 'Yes' : 'No';
        });
        $grid->created_at('Created at');

        $grid->actions(function ($actions) {
            $actions->disableView();
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed   $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(CouponCode::findOrFail($id));

        $show->id('Id');
        $show->name('Name');
        $show->code('Code');
        $show->type('Type');
        $show->value('Value');
        $show->total('Total');
        $show->used('Used');
        $show->min_amount('Min amount');
        $show->not_before('Not before');
        $show->not_after('Not after');
        $show->enabled('Enabled');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new CouponCode);

        $form->display('id', 'ID');
        $form->text('name', 'Name')->rules('required');
        $form->text('code', 'Code')->rules(function($form) {
            // 如果 $form->model()->id 不为空，代表是编辑操作
            if ($id = $form->model()->id) {
                return 'nullable|unique:coupon_codes,code,'.$id.',id';
                // code 在 couple_codes 这个表中是 唯一的
                // 可以保留原来的值 code
                // 在这个模型的id 是否为当前要更改的 id
                // 或者 验证唯一性时忽略 coupon_codes 表中字段名为id
                // 值为$id的值的那条 code 记录.
                // unique:table,column,except,idColumn
                // 验证字段的唯一性，并且排除id为本身的记录
            } else {
                return 'nullable|unique:coupon_codes';
            }
        });
        $form->radio('type', 'Type')->options(CouponCode::$typeMap)->rules('required');
        $form->text('value', 'Value')->rules(function ($form) {
            if ($form->model()->type === CouponCode::TYPE_PERCENT) {
                // 如果选择了百分比折扣类型，那么折扣范围只能是 1 ~ 99
                return 'required|numeric|between:1,99';
            } else {
                // 否则只要大等于 0.01 即可
                return 'required|numeric|min:0.01';
            }
        });
        $form->text('total', 'Total')->rules('required|numeric|min:0');
        $form->text('min_amount', 'Min Amount')->rules('required|numeric|min:0');

        $form->datetime('not_before', 'Begin at');
        $form->datetime('not_after', 'End at');
        // $form->datetime('not_after', 'End at')->rules(function ($form) {
        //     if(isset($form->not_before)) {
        //         return 'after_or_equal:not_before';
        //     } 
        // });

        $form->radio('enabled', 'Enable')->options(['1' => 'Yes', '0' => 'No']);

        $form->saving(function (Form $form) {
            if (!$form->code) {
                $form->code = CouponCode::findAvailableCode();
            }
        });
        //$form->datetime('not_before', 'Not before')->default(date('Y-m-d H:i:s'));
        //$form->datetime('not_after', 'Not after')->default(date('Y-m-d H:i:s'));
        //$form->switch('enabled', 'Enabled');

        return $form;
    }
}
