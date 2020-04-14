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
        $controllerDir = app_path('Http/Controllers/Iam');
        if (!is_dir($controllerDir)) {
            mkdir($controllerDir, 0755, true);
        }

        $accountController = $controllerDir . '/AccountController.php';
        if (!file_exists($accountController)) {
            file_put_contents(
                $accountController,
                file_get_contents($stubsDir . '/controller/AccountController.stub')
            );
        }

        // models
        $modelDir = app_path('Model/User');
        if (!is_dir($modelDir)) {
            mkdir($modelDir, 0755, true);
        }

        $user = $modelDir . '/User.php';
        if (!file_exists($user)) {
            file_put_contents(
                $user,
                file_get_contents($stubsDir . '/model/User/User.stub')
            );
        }

        $userBase = $modelDir . '/UserBase.php';
        if (!file_exists($userBase)) {
            file_put_contents(
                $userBase,
                file_get_contents($stubsDir . '/model/User/UserBase.stub')
            );
        }

        $userRole = $modelDir . '/UserRole.php';
        if (!file_exists($userRole)) {
            file_put_contents(
                $userRole,
                file_get_contents($stubsDir . '/model/User/UserRole.stub')
            );
        }

        // policies
        $policiesDir = app_path('Policies');
        if (!is_dir($policiesDir)) {
            mkdir($policiesDir, 0755, true);
        }

        $userPolicy = $policiesDir . '/UserPolicy.php';
        if (!file_exists($userPolicy)) {
            file_put_contents(
                $userPolicy,
                file_get_contents($stubsDir . '/policies/UserPolicy.stub')
            );
        }
    }
}
