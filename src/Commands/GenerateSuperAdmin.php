<?php
namespace Zekini\CrudGenerator\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Config\Repository as Config;
use Illuminate\Validation\Factory as ValidatorFactory;
use Zekini\CrudGenerator\Traits\ColumnTrait;

class GenerateSuperAdmin extends Command
{

    use ColumnTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:superuser {email?} {password?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Create the superadmin account";

    /**
     * @var ValidatorFactory
     */
    protected $validatorFactory;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @param ValidatorFactory $validatorFactory
     * @param Config $config
     */
    public function __construct(ValidatorFactory $validatorFactory, Config $config)
    {
        parent::__construct();

        $this->validatorFactory = $validatorFactory;
        $this->config = $config;
    }

    /**
     * Create super admin account.
     *
     * @return void
     */
    public function handle()
    {
        $this->info("Let's create a superadmin account!");

        if(! method_exists(User::class, 'assignRole')) {
            $this->error(' Your user model should use the Spatie HasRoles trait');
            return;
        }


        $email = $this->setEmail();
        $password = $this->setPassword();
        
        $names = $this->getNames();
        $userAttr = [
            'email' => $email,
            'email_verified_at'=> true,
            'password'=> Hash::make($password)
        ];
        $user = User::create(array_merge($names, $userAttr));

        $role = $this->config->get('zekini-admin.defaults.role');

        $user->assignRole($role);

        $this->info("Your account has been created");
    }

    protected function tableExists($tableName): bool
    {
        if (!Schema::hasTable($tableName)) {
            $this->error('Migration table for users not found. Run php artisan migrate before running livewire-crud-generator:superadmin to create an admin');
            return false;
        }

        return true;
    }

    protected function getNames():array
    {
        if (Schema::hasColumn('users', 'first_name')) {
            return [
                'first_name'=> 'Admin',
                'last_name'=> 'Admin'
            ];
        }

        return ['name'=> 'Admin'];
    }

    /**
     * Prompt user to enter email and validate it.
     *
     * @return string $email
     */
    private function setEmail()
    {
        if (filled($email = $this->argument('email'))) {
            return $email;
        }
        $email = $this->ask('Enter an email');
        if ($this->validateEmail($email)) {
            return $email;
        } else {
            $this->error("Your email is not valid");
            return $this->setEmail();
        }
    }

    /**
     * Prompt user to enter password, confirm and validate it.
     *
     * @return string $password
     */
    private function setPassword()
    {
        if (filled($password = $this->argument('password'))) {
            return $password;
        }
        $password = $this->secret('Enter a password');
        if ($this->validatePassword($password)) {
            $confirmPassword = $this->secret('Confirm the password');
            if ($password === $confirmPassword) {
                return $password;
            } else {
                $this->error('Password does not match the confirm password');
                return $this->setPassword();
            }
        } else {
            $this->error("Your password is not valid, at least 6 characters");
            return $this->setPassword();
        }
    }

    /**
     * Determine if the email address given valid.
     *
     * @param  string  $email
     * @return boolean
     */
    private function validateEmail($email)
    {
        return $this->validatorFactory->make(['email' => $email], [
            'email' => 'required|email|max:255|unique:users',
        ])->passes();
    }

    /**
     * Determine if the password given valid.
     *
     * @param  string  $password
     * @return boolean
     */
    private function validatePassword($password)
    {
        return $this->validatorFactory->make(['password' => $password], [
            'password' => 'required|min:6',
        ])->passes();
    }
}