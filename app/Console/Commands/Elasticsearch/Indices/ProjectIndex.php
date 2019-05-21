<?php

namespace App\Console\Commands\Elasticsearch\Indices;

use Illuminate\Support\Facades\Artisan;

class ProjectIndex
{
    public static function getAliasName()
    {
        return 'products';
    }

    public static function getProperties()
    {
        return [
            'type'          => ['type' => 'keyword'],
            'title'         => ['type' => 'text', 'analyzer' => 'english', 'search_analyzer' => 'ik_smart_synonym'],
            'long_title'    => ['type' => 'text', 'analyzer' => 'english', 'search_analyzer' => 'ik_smart_synonym'],
            'category_id'   => ['type' => 'integer'],
            'category'      => ['type' => 'keyword'],
            'category_path' => ['type' => 'keyword'],
            'description'   => ['type' => 'text', 'analyzer' => 'english'],
            'price'         => ['type' => 'scaled_float', 'scaling_factor' => 100],
            'on_sale'       => ['type' => 'boolean'],
            'rating'        => ['type' => 'float'],
            'sold_count'    => ['type' => 'integer'],
            'review_count'  => ['type' => 'integer'],
            'skus'          => [
                'type'       => 'nested',
                'properties' => [
                    'title'       => [
                        'type'            => 'text',
                        'analyzer'        => 'english',
                        'search_analyzer' => 'ik_smart_synonym',
                        'copy_to'         => 'skus_title',
                    ],
                    'description' => [
                        'type'     => 'text',
                        'analyzer' => 'english',
                        'copy_to'  => 'skus_description',
                    ],
                    'price'       => ['type' => 'scaled_float', 'scaling_factor' => 100],
                ],
            ],
            'properties'    => [
                'type'       => 'nested',
                'properties' => [
                    'name'         => ['type' => 'keyword'],
                    'value'        => ['type' => 'keyword', 'copy_to' => 'properties_value'],
                    'search_value' => ['type' => 'keyword'],
                ],
            ],
        ];
    }

    public static function getSettings()
    {
        return [
            'analysis' => [
                'analyzer' => [
                    'ik_smart_synonym' => [
                        'type'      => 'custom',
                        'tokenizer' => 'ik_smart',
                        'filter'    => ['synonym_filter'],
                    ],
                ],
                'filter'   => [
                    'synonym_filter' => [
                        'type'          => 'synonym',
                        'synonyms_path' => 'analysis/synonyms.txt',
                    ],
                ],
            ],
        ];
    }

    // 在 analysis 下的 filter 中定义了一个名为 synonym_filter 的 『同义词词语过滤器』
    // 指定同义词的字典路径为 analysis/synonyms.txt
    // 在 analyzer 下定义了一个名为 ik_smart_synonym 的 『自定义分析器』
    // ik_smart 作为 『分词器』
    // 上面定义的 synonym_filter 作为 『词语过滤器』

    public static function rebuild($indexName)
    {
        // 通过 Artisan 类的 call 方法可以直接调用命令
        // call 方法的第二个参数可以用数组的方式给命令传递参数
        Artisan::call('es:sync-products', ['--index' => $indexName]);
    }

    /*
    * getProperties() 里的字段：
    * 'search_analyzer' => 'ik_smart_synonym',
    * 以及 getSettings() 方法 
    * 是为了 测试 汉语同义词 的
    */
}