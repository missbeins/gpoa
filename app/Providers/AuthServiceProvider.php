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

        Gate::define('is-officer', function($user){
            
            return $user->hasAnyRole('GPOA Admin');
            //return $user->hasAnyRoles(['GPOA Admin','User']);  any of the given roles matched will be allowed 
            
        });

        Gate::define('is-superadmin', function($user){
            
            return $user->hasAnyRole('Super Admin');
            
        });

        Gate::define('is-director', function($user){
            
            return $user->hasAnyRole('Director');
            
        });

        Gate::define('is-adviser', function($user){
            
            return $user->hasAnyRole('Adviser');
            
        });
    }
}
