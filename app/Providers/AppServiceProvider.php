<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Topic;
use App\Moods;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootEloquentMorphs();
    }
    
    /**
     * 自定义多态关联的类型字段
     */
    private function bootEloquentMorphs()
    {
        Relation::morphMap([
        Topic::TABLE => Topic::class,
        Moods::TABLE => Moods::class,
    ]);
    }
}
