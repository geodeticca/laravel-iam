<?php

namespace Geodeticca\Iam\Commands;

use Illuminate\Console\Command;

class Generate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'iam:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates user and accunt controllers.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $stubsDir = __DIR__ . DIRECTORY_SEPARATOR . 'stubs';

        // controllers
        $controllersIamDir = app_path('Http/Controllers/Iam');
        if (!is_dir($controllersIamDir)) {
            mkdir($controllersIamDir, 0755, true);
        }

        $accountController = $controllersIamDir . '/AccountController.php';
        if (!file_exists($accountController)) {
            file_put_contents(
                $accountController,
                file_get_contents($stubsDir . '/controller/AccountController.stub')
            );
        }

        $controllersAuthDir = app_path('Http/Controllers/Auth');
        if (!is_dir($controllersAuthDir)) {
            mkdir($controllersAuthDir, 0755, true);
        }

        $accountController = $controllersAuthDir . '/LoginController.php';
        if (!file_exists($accountController)) {
            file_put_contents(
                $accountController,
                file_get_contents($stubsDir . '/controller/LoginController.stub')
            );
        }

        // models
        $modelDir = app_path('Model/Account');
        if (!is_dir($modelDir)) {
            mkdir($modelDir, 0755, true);
        }

        $user = $modelDir . '/Account.php';
        if (!file_exists($user)) {
            file_put_contents(
                $user,
                file_get_contents($stubsDir . '/model/Account/Account.stub')
            );
        }
    }
}
