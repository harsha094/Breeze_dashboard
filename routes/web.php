<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Http\Controllers\Profile\AvatarController;
use App\Http\Controllers\TicketController;
use Laravel\Socialite\Facades\Socialite;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
    //get data 
    // $user= DB::select('select * from users');
    //$user= DB::insert('insert into users(name, email, password) values(?,?,?)',['Shourya','shourya1@gmail.com','12345678']);
    // $user= DB::table('users')->where('name', 'Harsha')->value('email');
    // $user = User::all();
            // ->select('name', 'email as user_email')
            // ->get();
    // dd($user);
    
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/Profile/avatar',[AvatarController::class, 'update'])->name('profile.avatar');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Route::get('/openai', function(){
//     $result = OpenAI::completions()->create([
//         'model' => 'text-davinci-003',
//         'prompt' => 'PHP is',
//     ]);
//     echo $result['choices'][0]['text'];
// });
Route::get('/auth/redirect', function () {
    return Socialite::driver('github')->redirect();
});

Route::get('/auth/callback', function () {
    $user = Socialite::driver('github')->user();
    dd($user);
    // $user->token
}); 
Route::middleware('auth')->group(function(){
    Route::resource('/ticket', TicketController::class); // resource route
    // Route::get('/ticket/create', [TicketController::class, 'create'])->name('ticket.create');
    // Route::post('/ticket/create', [TicketController::class, 'store'])->name('ticket.store');
});

Route::get('/testMail', function () {
    \Mail::raw('hello world', function($message) {
        $message->subject('Testing email')->to('test@example.org');
     });
}); 
Route::get('/send-mail', [ProfileController::class, 'sendMail']);
// Route::get('/send-mail', [TicketController::class, 'update']);
