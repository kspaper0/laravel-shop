<?php

namespace App\Admin\Controllers;

use App\Models\Product;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class ProductsController extends Controller
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
            ->header('Product List')
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
            ->header('Edit Product')
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
            ->header('Add Products')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Product);

        $grid->id('ID')->sortable();
        $grid->title('Title');
        $grid->on_sale('On sale')->display(function ($value) {
            return $value ? 'Yes' : 'No'; 
        });
        $grid->price('Price');
        $grid->rating('Rating');
        $grid->sold_count('Sold');
        $grid->review_count('Review');
        
        $grid->actions(function ($actions) {
            $actions->disableView();
            $actions->disableDelete();
        });

        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed   $id
     * @return Show
     */
    // protected function detail($id)
    // {
    //     $show = new Show(Product::findOrFail($id));

    //     $show->id('Id');
    //     $show->title('Title');
    //     $show->description('Description');
    //     $show->image('Image');
    //     $show->on_sale('On sale');
    //     $show->rating('Rating');
    //     $show->sold_count('Sold count');
    //     $show->review_count('Review count');
    //     $show->price('Price');
    //     $show->created_at('Created at');
    //     $show->updated_at('Updated at');

    //     return $show;
    // }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Product);

        // 创建一个输入框，第一个参数 title 是模型的字段名，第二个参数是该字段描述
        $form->text('title', 'Title')->rules('required');
        $form->image('image', 'Image')->rules('required|image');
        // 创建一个富文本编辑器
        $form->editor('description', 'Description')->rules('required');
        // 创建一组单选框
        $form->radio('on_sale', 'On Sale')->options(['1' => 'Yes', '0'=> 'No'])->default('0');
        // 直接添加一对多的关联模型
        $form->hasMany('skus', 'SKU List', function (Form\NestedForm $form) {
            $form->text('title', 'SKU Title')->rules('required');
            $form->text('description', 'SKU Description')->rules('required');
            $form->text('price', 'Price')->rules('required|numeric|min:0.01');
            $form->text('stock', 'Stock')->rules('required|integer|min:0');
        });

        // 定义事件回调，当模型即将保存时会触发这个回调
        $form->saving(function (Form $form) {
            $form->model()->price = collect($form->input('skus'))->where(Form::REMOVE_FLAG_NAME, 0)->min('price') ?: 0;
            });

        return $form;
    }
}