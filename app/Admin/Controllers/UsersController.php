<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class UsersController extends Controller
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
            ->header('User List')
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
    // public function edit($id, Content $content)
    // {
    //     return $content
    //         ->header('Edit')
    //         ->description('description')
    //         ->body($this->form()->edit($id));
    // }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    // public function create(Content $content)
    // {
    //     return $content
    //         ->header('Create')
    //         ->description('description')
    //         ->body($this->form());
    // }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User);

        // 创建一个列名为 ID 的列，内容是用户的 id 字段，并且可以在前端页面点击排序
        $grid->id('ID')->sortable();
        $grid->name('Name');
        $grid->email('Email');
        $grid->email_verified('Email verified')->display(function ($value) {
            return $value ? 'Yes' : 'No';
        });
        //由于 email_verified 字段在数据库中是 0 和 1，直接展示出来并不直观
        //display() 方法接受一个匿名函数作为参数
        //在展示时会把对应字段值当成参数传给匿名函数
        //把匿名函数的返回值作为页面输出的内容
        $grid->created_at('Registered at');
        // 不在页面显示 `新建` 按钮，因为我们不需要在后台新建用户
        $grid->disableCreateButton();
        $grid->disableActions();
        $grid->actions(function ($actions) {
            $actions->disableView();
            // 不在每一行后面展示删除按钮
            $actions->disableDelete();
            // 不在每一行后面展示编辑按钮
            $actions->disableEdit();
        });
        $grid->tools(function ($tools) {
            // 禁用批量删除按钮
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
    //     $show = new Show(User::findOrFail($id));

    //     $show->id('Id');
    //     $show->name('Name');
    //     $show->email('Email');
    //     $show->password('Password');
    //     $show->remember_token('Remember token');
    //     $show->email_verified('Email verified');
    //     $show->created_at('Created at');
    //     $show->updated_at('Updated at');

    //     return $show;
    // }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    // protected function form()
    // {
    //     $form = new Form(new User);

    //     $form->text('name', 'Name');
    //     $form->email('email', 'Email');
    //     $form->password('password', 'Password');
    //     $form->text('remember_token', 'Remember token');
    //     $form->switch('email_verified', 'Email verified');

    //     return $form;
    // }
}
