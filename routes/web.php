<?php

use App\Http\Controllers\TestController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

Route::resource('test', TestController::class);

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/game', function () {
    return view('game');
})->middleware(['auth', 'verified'])->name('game');

Route::get('/guest', function () {
    $user = \App\Models\User::where('name', 'Guest')->orderBy('id')->first();
    \Illuminate\Support\Facades\Auth::login($user);

    return redirect(\App\Providers\RouteServiceProvider::HOME);
})->name('guest');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::namespace('App\Http\Controllers\Game')->group(function () {

        Route::get('/events', 'EventController@getCells')->name('get-events');
        Route::get('/movetocell/{id}', 'MapController@moveToCell')->name('move-to-cell');
        Route::get('/transfertocell/{id}', 'MapController@transferToCell')->name('transfer-to-cell');

        Route::get('/leftsideequipment', 'InventoryController@getLeftSideEquipment')->name('left-side-equipment');
        Route::get('/rightsideequipment', 'InventoryController@getRightSideEquipment')->name('right-side-equipment');

        Route::get('/inventory/{type}', 'InventoryController@getInventory')->name('get-inventory');

        Route::get('/blueprints/{category}/{subcategory}', 'BlueprintController@getBlueprints')->name('get-blueprints');

        Route::post('/ondropinventory', 'InventoryController@onDropInventory')->name('on-drop-inventory');
        Route::post('/ondropequipment', 'InventoryController@onDropEquipment')->name('on-drop-equipment');
        Route::post('/drop-in-basket', 'InventoryController@removeItem')->name('drop-in-basket');

        Route::post('/buildings/create', 'BuildingController@create')->name('building-create');
        Route::delete('/buildings/destroy/{cell_id}', 'BuildingController@destroy')->name('building-destroy');

        Route::get('/map/global-cells', 'MapController@getGlobalCells')->name('get-global-cells');
        Route::get('/map/cells', 'MapController@getCells')->name('get-cells');

        Route::get('/skills', 'SkillController@getSkills')->name('get-skills');
        Route::get('/skills/names', 'SkillController@getSkillNames')->name('get-skills-names');
        Route::get('/skill/{skill_id}/learn', 'SkillController@learnSkill')->name('learn-skill');

        Route::get('/hero', 'HeroController@getHero')->name('get-hero');
        Route::get('/cell/{cell_id}/take-item', 'ItemController@takeItemFromCell')->name('take-item-from-cell');

        Route::get('/hero/stats', 'HeroController@getHeroStats')->name('get-hero-stats');
        Route::get('/hero/{hero_id}/defence/{area}', 'BattleController@setHeroDefence')->name('set-hero-defence');

        Route::get('/question/{question_id}', 'DialogueController@getQuestion')->name('question');
        Route::get('/answer/{answer_id}/select', 'DialogueController@selectAnswer')->name('select-answer');

        Route::get('/quests', 'QuestController@getQuests')->name('get-quests');
        Route::get('/quest/{quest_id}', 'QuestController@getQuest')->name('get-quest');
        Route::get('/talents', 'TalentController@getTalents')->name('get-talents');

        Route::get('/cell/{cell_id}/attack/{area}', 'BattleController@attack')->name('attack');
        Route::get('/battle', 'BattleController@getBattle')->name('get-battle');

        Route::get('/units/{unit_id}', 'UnitController@getUnit')->name('get-unit');
        Route::post('/cell/{cell_id}/notes', 'NoteController@createOnCell');
        Route::get('/notes/{note_id}', 'NoteController@getNote');
        Route::delete('/cell/{cell_id}/notes/{note_id}', 'NoteController@deleteNoteFromCell');
    });
});

require __DIR__ . '/auth.php';
