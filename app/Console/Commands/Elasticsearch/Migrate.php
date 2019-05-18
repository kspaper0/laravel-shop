<?php

namespace App\Console\Commands\Elasticsearch;

use Illuminate\Console\Command;

class Migrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'es:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Elasticsearch Migrating';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->es = app('es');

        // 索引类数组，先留空
        $indices = [Indices\ProjectIndex::class];
        // 遍历索引类数组
        foreach ($indices as $indexClass) {
            // 调用类数组的 getAliasName() 方法来获取索引别名
            $aliasName = $indexClass::getAliasName();

            $this->info('Handling Index => '.$aliasName);

            // 通过 exists 方法判断这个别名是否存在
            if (!$this->es->indices()->exists(['index' => $aliasName])) {
                $this->info('Index is not existed, Creating ... ');
                $this->createIndex($aliasName, $indexClass);
                $this->info('Created Successfully, Initiating Data ... ');
                $indexClass::rebuild($aliasName);
                $this->info('Done');
                continue;
            }

            // 如果索引已经存在，那么尝试更新索引，如果更新失败会抛出异常
            try {
                $this->info('Index has existed, Updating ... ');
                $this->updateIndex($aliasName, $indexClass);
            } catch (\Exception $e) {
                $this->warn('Updating Failed, Preparing to rebuild ... ');
                $this->reCreateIndex($aliasName, $indexClass);
            }

            $this->info('Updated Done');
        }
    }

    // 创建新索引
    protected function createIndex($aliasName, $indexClass)
    {
        // 调用 create() 方法创建索引
        $this->es->indices()->create([
            // 第一个版本的索引名后缀为 _0
            'index' => $aliasName.'_0',
            'body'  => [
                // 调用索引类的 getSettings() 方法获取索引设置
                'settings' => $indexClass::getSettings(),
                'mappings' => [
                    '_doc' => [
                        // 调用索引类的 getProperties() 方法获取索引字段
                        'properties' => $indexClass::getProperties(),
                    ],
                ],
                'aliases'  => [
                    // 同时创建别名
                    $aliasName => new \stdClass(),
                    // 这里用 stdClass 类的原因是:
                    // Elasticsearch 只接受一个空对象
                    // 如果我们用 $aliasName => []
                    // 则会在 JSON 编码时被转换成空数组
                    // Elasticsearch 接口会报错
                ],
            ],
        ]);
    }

    // 更新已有索引
    protected function updateIndex($aliasName, $indexClass)
    {
        // 暂时关闭索引
        $this->es->indices()->close(['index' => $aliasName]);
        // 更新索引设置
        $this->es->indices()->putSettings([
            'index' => $aliasName,
            'body'  => $indexClass::getSettings(),
        ]);
        // 更新索引字段
        $this->es->indices()->putMapping([
            'index' => $aliasName,
            'type'  => '_doc',
            'body'  => [
                '_doc' => [
                    'properties' => $indexClass::getProperties(),
                ],
            ],
        ]);
        // 重新打开索引
        $this->es->indices()->open(['index' => $aliasName]);
    }

    // 重建索引
    protected function reCreateIndex($aliasName, $indexClass)
    {

        // 获取索引信息，返回结构的 key 为索引名称，value 为别名
        $indexInfo     = $this->es->indices()->getAliases(['index' => $aliasName]);

        // 取出第一个 key 即为索引名称
        $indexName = array_keys($indexInfo)[0];

        // 用正则判断索引名称是否以 _数字 结尾
        if (!preg_match('~_(\d+)$~', $indexName, $m)) {
            $msg = 'Wrong Index Name: '.$indexName;
            $this->error($msg);
            throw new \Exception($msg);
        }

        // 新的索引名称
        $newIndexName = $aliasName.'_'.($m[1] + 1);

        $this->info('Creating New Index => '.$newIndexName);
        $this->es->indices()->create([
            'index' => $newIndexName,
            'body'  => [
                'settings' => $indexClass::getSettings(),
                'mappings' => [
                    '_doc' => [
                        'properties' => $indexClass::getProperties(),
                    ],
                ],
            ],
        ]);

        $this->info('Created Successfully, Reconstructing Data ... ');
        $indexClass::rebuild($newIndexName);

        $this->info('Done. Mounting to new Index ... ');
        $this->es->indices()->putAlias(['index' => $newIndexName, 'name' => $aliasName]);

        $this->info('Deleting old Index ... ');
        $this->es->indices()->delete(['index' => $indexName]);

        $this->info('Deleted Successfully');
    }
}
