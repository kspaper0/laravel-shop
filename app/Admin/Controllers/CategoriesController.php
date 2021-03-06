<?php

namespace App\Admin\Controllers;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;

//use Encore\Admin\Show;

class CategoriesController extends Controller
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
            ->header('Category List')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
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
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit Categories')
            ->body($this->form(true)->edit($id));
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
            ->header('Create New Category')
            ->body($this->form(false));
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Category);

        $grid->id('Id')->sortable();
        $grid->name('Name');
        $grid->level('Level');
        $grid->is_directory('Directory')->display(function ($value) {
            return $value ? 'Yes' : 'No';
        });
        $grid->path('Path');
        $grid->actions(function ($actions) {
            // 不展示 Laravel-Admin 默认的查看按钮
            $actions->disableView();
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    // protected function detail($id)
    // {
    //     $show = new Show(Category::findOrFail($id));

    //     $show->id('Id');
    //     $show->name('Name');
    //     $show->parent_id('Parent id');
    //     $show->is_directory('Is directory');
    //     $show->level('Level');
    //     $show->path('Path');
    //     $show->created_at('Created at');
    //     $show->updated_at('Updated at');

    //     return $show;
    // }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form($isEditing = false)
    {
        // Laravel-Admin 1.5.19 之后的新写法，原写法也仍然可用
        $form = new Form(new Category);

        $form->text('name', 'Name')->rules('required');

        // 如果是编辑的情况
        if ($isEditing) {
            // 不允许用户修改『是否目录』和『父类目』字段的值
            // 用 display() 方法来展示值，with() 方法接受一个匿名函数，会把字段值传给匿名函数并把返回值展示出来
            $form->display('is_directory', 'Directory')->with(function ($value) {
                return $value ? 'Yes' :'No';
            });
            // 支持用符号 . 来展示关联关系的字段
            $form->display('parent.name', 'Parent Dir');
        } else {
            // 定义一个名为『是否目录』的单选框
           $form->radio('is_directory', 'Directory')
                ->options(['1' => 'Yes', '0' => 'No'])
                ->default('0')
                ->rules('required');

            // 定义一个名为父类目的下拉框
            $form->select('parent_id', 'Parent Dir')->ajax('/admin/api/categories');
            // 1. ->ajax(xxx) 代表下拉框的值通过 /admin/api/categories 接口搜索获取
        }

        return $form;
    }

    // 定义下拉框搜索接口
    public function apiIndex(Request $request)
    {
        // 2. Laravel-Admin 会把用户输入的值以 q 参数传给接口
        // 用户输入的值通过 q 参数获取
        $search = $request->input('q');
        $result = Category::query()
            ->where('is_directory', boolval($request->input('is_directory', true))) 
            // 由于这里选择的是父类目，因此需要限定 is_directory 为 true
            ->where('name', 'like', '%'.$search.'%')
            ->paginate();
        // 3. 这个接口需要返回的数据格式为分页格式

        // 把查询出来的结果重新组装成 Laravel-Admin 需要的格式
        $result->setCollection($result->getCollection()->map(function (Category $category) {
            return ['id' => $category->id, 'text' => $category->full_name];
        }));
        // getCollection 就是获取到这个分页里的数据集合
        // setCollection 就是替换分页的数据
        // 这个需要翻源码才能找到
        // 追踪源码可知在 Illuminate/Pagination/AbstractPaginator.php 中
        // 总结就是先转换成集合对象，然后调用集合的 map 方法对数据进行操作
        // 然后再重新转换回来分页对象

        return $result;
    }
}
