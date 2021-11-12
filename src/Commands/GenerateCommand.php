<?php

namespace Tengs\IdeHelper\Commands;

use Illuminate\Console\Command;

class GenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tengs:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '快速创建代码';


    public function handle()
    {
        $type = $this->choice('选择类型?', ['Home', 'Admin'],
            'Home', $maxAttempts = null, $allowMultipleSelections = false);
        if ($type == 'Home') {
            $modes = $this->choice('要生成的模板?', ['控制器+服务+请求验证+筛选过滤+授权', '控制器+服务+请求验证+筛选过滤', '控制器+服务+筛选过滤', '控制器'],
                '控制器+服务+请求验证+筛选过滤+授权', $maxAttempts = null, $allowMultipleSelections = false);
        } else {
            $modes = $this->choice('要生成的模板?', ['控制器+服务+请求验证+筛选过滤', '控制器+服务+筛选过滤', '控制器'],
                '控制器+服务+请求验证+筛选过滤', $maxAttempts = null, $allowMultipleSelections = false);
        }
        $generateConfig = [
            '控制器' => [
                'namespace' => 'Http/Controllers',
                'name' => 'Controller'
            ],
            '服务' => [
                'namespace' => 'Services',
                'name' => 'Service'
            ],
            '请求验证' => [
                'namespace' => 'Http/Requests',
                'name' => 'Request'
            ],
            '筛选过滤' => [
                'namespace' => 'ModelFilters',
                'name' => 'Filter'
            ],
            '授权' => [
                'namespace' => 'Policies',
                'name' => 'Policy'
            ],
        ];

        $name = $this->ask('输入控制器名称?');
        $upperName = ucfirst($name);
        $lowerName = lcfirst($name);
        foreach (explode('+', $modes) as $mode) {
            $sendType = !in_array($mode, ['服务', '授权']) ? $type : '';
            $this->generate($upperName, $lowerName, $generateConfig[$mode], $sendType);
        }
    }

    protected function generate($upperName, $lowerName, $modeArr, $type = '')
    {
        if ($type) {
            $filename = app_path($modeArr['namespace'] . '/' . $type . '/' . $upperName . $modeArr['name'] . '.php');
        } else {
            $filename = app_path($modeArr['namespace'] . '/' . $upperName . $modeArr['name'] . '.php');
        }

        if (!file_exists($filename)) {
            $filePath = __DIR__ . '/../Template/' . $type . $modeArr['name'] . '.stub';
            $fileString = file_get_contents($filePath);
            $replaceArr = [
                '{{$upperName}}' => $upperName,
                '{{$lowerName}}' => $lowerName,
            ];
            $fileString = str_replace(array_keys($replaceArr), array_values($replaceArr), $fileString);
            file_put_contents($filename, $fileString);
        }
    }
}
