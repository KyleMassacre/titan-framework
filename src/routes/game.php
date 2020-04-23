<?php

Route::get('/', function() {
    return redirect()->route('game.home');
});

Route::group([
    'prefix' => 'game',
    'middleware' => ['auth', 'game']
], function () {
    Route::get('/', 'GameHomeController@index')->name('game.home');
    Route::get('banned', 'BanController@index')->name('userban.userbanned');
});
