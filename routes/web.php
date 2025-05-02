<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\EvaluationPeriodController;
use App\Http\Controllers\NotificationController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $userCount = auth()->user()->isSuperAdmin() || auth()->user()->isAdmin() 
            ? \App\Models\User::count() 
            : 0;
            
        $activeEvaluations = auth()->user()->isPYD()
            ? auth()->user()->pydEvaluations()->count()
            : (auth()->user()->isPPP()
                ? auth()->user()->pppEvaluations()->count()
                : (auth()->user()->isPPK()
                    ? auth()->user()->ppkEvaluations()->count()
                    : \App\Models\Evaluation::count()));
                    
        $unreadNotifications = auth()->user()->notifications()->where('is_read', false)->count();
        
        $pendingEvaluations = auth()->user()->isPYD()
            ? auth()->user()->pydEvaluations()
                ->whereIn('status', ['draf', 'ppp_submit'])
                ->get()
            : collect();

        return view('dashboard', compact('userCount', 'activeEvaluations', 'unreadNotifications', 'pendingEvaluations'));
    })->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User management
    Route::resource('users', UserController::class)->except(['show']);

    // Evaluations
    Route::resource('evaluations', EvaluationController::class);
    Route::post('/evaluations/{evaluation}/submit/{section}', [EvaluationController::class, 'submitSection'])
        ->name('evaluations.submit');
    Route::post('/evaluations/{evaluation}/marks', [EvaluationController::class, 'saveMarks'])
        ->name('evaluations.marks.save');

    // Evaluations Period
    Route::resource('evaluation-periods', EvaluationPeriodController::class)->except(['show']);
    Route::get('/evaluation-periods/{evaluationPeriod}/mass-assign', [EvaluationPeriodController::class, 'massAssign'])
        ->name('evaluation-periods.mass-assign');
    Route::post('/evaluation-periods/{evaluationPeriod}/mass-assign', [EvaluationPeriodController::class, 'storeMassAssign'])
        ->name('evaluation-periods.store-mass-assign');
    Route::post('/evaluation-periods/{evaluationPeriod}/toggle-active', [EvaluationPeriodController::class, 'toggleActive'])
        ->name('evaluation-periods.toggle-active');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::put('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::put('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.read.all');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

});

require __DIR__.'/auth.php';