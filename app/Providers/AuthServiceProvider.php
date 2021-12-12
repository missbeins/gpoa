<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('logged-in', function($user){

            return $user;

        });
        Gate::allows('is-officer', function($user){
            
            return $user->hasAnyRole('GPOA Admin');
            //return $user->hasAnyRoles(['GPOA Admin','User']);  any of the given roles matched will be allowed 
            
        });

        Gate::allows('is-superadmin', function($user){
            
            return $user->hasAnyRole('Super Admin');
            
        });

        Gate::allows('is-adviser', function($user){
            
            return $user->hasAnyRole('Adviser');
            
        });
        Gate::allows('is-student', function($user){
            
            return $user->hasAnyRole('User');
            
        });
    }
}
