<?php

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
        	[
        		'name'		=>	'Mobile Accessories',
        		'children' 	=>	[
        			['name' =>	'Phone Cases'],
        			['name' =>	'Protectors'],
        			['name' =>	'Chargers '],
        			[
        				'name' 		=>	'Earphones',
        				'children'	=>	[
        					['name' =>	'Blue Tooth Earphones'],
        					['name' =>	'Wired Control Earphones'],
        				],
        			],
        		],
        	],
        	[
        		'name'		=>	'PC Components',
        		'children'	=>	[
        			['name'	=>	'Monitors'],
        			[
        				'name'		=>	'Graphic Card',
        				'children'	=>	[
        					['name'	=>	'Dedicated'],
        					['name'	=>	'Integrated'],
        				],
        			],
        			['name'	=>	'PC Memories'],
        			['name'	=> 	'CPU '],
        			['name'	=>	'Mother Board'],
        			['name'	=>	'Hard Drive'],
        		],
        	],
        	[
        		'name'		=>	'Computer Type',
        		'children'	=>	[
        			['name'	=>	'Laptops'],
        			['name'	=>	'Desktops'],
        			['name'	=>	'Tablets'],
        			['name'	=>	'All-in-Ones'],
        			['name'	=>	'Servers'],
        		],
        	],
        ];

        foreach ($categories as $data) {
            $this->createCategory($data);
        }
    }

    protected function createCategory($data, $parent = null)
    {
        // 创建一个新的类目对象
        $category = new Category(['name' => $data['name']]);
        // 如果有 children 字段则代表这是一个父类目
        $category->is_directory = isset($data['children']);
        // 如果有传入 $parent 参数，代表有父类目
        if (!is_null($parent)) {
            $category->parent()->associate($parent);
        }
        //  保存到数据库
        $category->save();
        // 如果有 children 字段并且 children 字段是一个数组
        if (isset($data['children']) && is_array($data['children'])) {
            // 遍历 children 字段
            foreach ($data['children'] as $child) {
                // 递归调用 createCategory 方法，第二个参数即为刚刚创建的类目
                $this->createCategory($child, $category);
            }
        }
    }
}
