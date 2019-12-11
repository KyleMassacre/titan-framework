<?php
Route::group([
    'namespace' => 'PbbgIo\TitanFramework\Http\Controllers',
    'middleware' => [
        'web'
    ]
], function () {

    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

// Password Confirmation Routes...

    Route::get('password/confirm', 'Auth\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
    Route::post('password/confirm', 'Auth\ConfirmPasswordController@confirm');

// Email Verification Routes...

    Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
    Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify');
    Route::post('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');

    Route::get('/', function () {
        return redirect()->route('home');
    });
    Route::get('/home', 'HomeController@index')->name('home');

    Route::group([
        'prefix' => 'admin',
        'namespace' => 'Admin',
        'middleware' => ['auth', 'permission:admin', 'update_last_move'],
    ], function () {
        Route::get('/', 'HomeController@index')
            ->name('admin.home');

        Route::get('/extensions', 'ExtensionController@index')
            ->name('admin.extensions.index');

        Route::get('/extensions/{slug}', 'ExtensionController@showMarketplacePage')
            ->name('admin.extensions.show');

        Route::get('/extensions/{slug}/manage', 'ExtensionController@manage')
            ->name('admin.extensions.manage');

        Route::get('/extensions/{slug}/install', 'ExtensionController@install')
            ->name('admin.extensions.install');

        Route::get('/extensions/{slug}/uninstall', 'ExtensionController@uninstall')
            ->name('admin.extensions.uninstall');

        Route::get('/users/datatable', 'UserController@datatable')
            ->name('admin.users.datatable');


        Route::post('/cronjobs/run', 'CronjobController@run')
            ->name('admin.cronjobs.run');

        Route::resource('cronjobs', 'CronjobController')->names([
            'index' => 'admin.cronjobs.index',
            'create' => 'admin.cronjobs.create',
            'store' => 'admin.cronjobs.store',
            'update' => 'admin.cronjobs.update',
            'edit' => 'admin.cronjobs.edit',
            'delete' => 'admin.cronjobs.delete',
            'destroy' => 'admin.cronjobs.destroy',
        ]);

        Route::resource('users', 'UserController')->names([
            'index' => 'admin.users.index',
            'create' => 'admin.users.create',
            'store' => 'admin.users.store',
            'update' => 'admin.users.update',
            'edit' => 'admin.users.edit',
            'delete' => 'admin.users.delete',
            'destroy' => 'admin.users.destroy',
        ]);

        Route::resource('groups', 'GroupController')->names([
            'index' => 'admin.groups.index',
            'create' => 'admin.groups.create',
            'store' => 'admin.groups.store',
            'update' => 'admin.groups.update',
            'edit' => 'admin.groups.edit',
            'delete' => 'admin.groups.delete',
            'destroy' => 'admin.groups.destroy',
        ]);

        Route::get('/settings', 'SettingController@index')
            ->name('admin.settings.index');

        Route::get('/settings/{setting}', 'SettingController@edit')
            ->name('admin.settings.edit');

        Route::post('/settings/{setting}', 'SettingController@update')
            ->name('admin.settings.update');

        Route::post('/search', 'SearchController@index')
            ->name('admin.search');

        Route::resource('menus', 'MenuController')->names([
            'index' => 'admin.menu.index',
            'create' => 'admin.menu.create',
            'store' => 'admin.menu.store',
            'update' => 'admin.menu.update',
            'edit' => 'admin.menu.edit',
            'delete' => 'admin.menu.delete',
            'destroy' => 'admin.menu.destroy',
        ]);


        Route::put('menu/items/{menu}/sort', 'MenuController@sort')
            ->name('admin.menu.sort');

        Route::post('menu/items/{menu}/item', 'MenuController@addItem')
            ->name('admin.menu.add');

        Route::delete('menu/items', 'MenuController@deleteItem')
            ->name('admin.menu.item.delete');

    });

});
