<?php

namespace app\command;

use app\util\Random;
use DateTimeZone;
use Dotenv\Dotenv;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class Setup extends Command
{
    protected static $defaultName = 'setup';
    protected static $defaultDescription = 'Setup PowerPanel';

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $env = [];

        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        $env['DB_HOST'] = $helper->ask($input, $output, new Question('输入数据库地址：'));
        $env['DB_PORT'] = $helper->ask($input, $output, new Question('输入数据库端口：'));
        $env['DB_NAME'] = $helper->ask($input, $output, new Question('输入数据库名称：'));
        $env['DB_USER'] = $helper->ask($input, $output, new Question('输入数据库用户：'));
        $env['DB_PASS'] = $helper->ask($input, $output, new Question('输入数据库密码：'));

        $tz = new Question('设置时区：');
        $tz->setAutocompleterValues(DateTimeZone::listIdentifiers(DateTimeZone::ALL));
        $env['TIMEZONE'] = $helper->ask($input, $output, $tz);

        $env['WORKER_COUNT'] = cpu_count();
        $env['SERVER_PORT'] = 8080;
        $env['APP_SALT'] = Random::String(16);

        $env = implode(PHP_EOL, array_map(fn (...$kv) => implode('=', $kv), array_keys($env), array_values($env)));

        file_put_contents(base_path() . '/.env', $env);

        return self::SUCCESS;
    }
}
