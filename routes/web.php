<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AssistanceRequestController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventTeamRegistrationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JudgingController;
use App\Http\Controllers\ManagedEventController;
use App\Http\Controllers\NotificationPreferenceController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\ProjectEntryController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TeamInvitationController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event:slug}', [EventController::class, 'show'])->name('events.show');
Route::get('/team-invitations/{token}', [TeamInvitationController::class, 'show'])->name('team-invitations.show');
Route::post('/team-invitations/{token}/accept', [TeamInvitationController::class, 'accept'])->name('team-invitations.accept');
Route::post('/team-invitations/{token}/decline', [TeamInvitationController::class, 'decline'])->name('team-invitations.decline');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');

    Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])
        ->whereIn('provider', ['github', 'google'])
        ->name('auth.social.redirect');

    Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])
        ->whereIn('provider', ['github', 'google'])
        ->name('auth.social.callback');
});

Route::middleware('auth')->group(function () {
    Route::get('/email/verify', fn () => view('auth.verify-email'))->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('/portal', PortalController::class)->name('portal');
    Route::resource('teams', TeamController::class)->except(['index']);
    Route::post('/teams/{team}/invitations', [TeamInvitationController::class, 'store'])->name('teams.invitations.store');
    Route::post('/events/{event}/teams', [EventTeamRegistrationController::class, 'store'])->name('events.teams.store');
    Route::post('/events/{event}/assistance-requests', [AssistanceRequestController::class, 'store'])->name('events.assistance-requests.store');
    Route::get('/teams/{team}/events/{event}/entries/create', [ProjectEntryController::class, 'create'])->name('entries.create');
    Route::post('/teams/{team}/events/{event}/entries', [ProjectEntryController::class, 'store'])->name('entries.store');
    Route::get('/entries/{entry}/edit', [ProjectEntryController::class, 'edit'])->name('entries.edit');
    Route::put('/entries/{entry}', [ProjectEntryController::class, 'update'])->name('entries.update');
    Route::post('/entries/{entry}/submit', [ProjectEntryController::class, 'submit'])->name('entries.submit');
    Route::get('/entries/{entry}/judge', [JudgingController::class, 'review'])->name('judging.review');
    Route::post('/entries/{entry}/scores', [JudgingController::class, 'score'])->name('judging.score');
    Route::get('/notification-preferences', [NotificationPreferenceController::class, 'edit'])->name('notifications.edit');
    Route::put('/notification-preferences', [NotificationPreferenceController::class, 'update'])->name('notifications.update');

    Route::prefix('manage')->name('manage.')->group(function () {
        Route::resource('events', ManagedEventController::class)->except(['destroy']);
        Route::post('/events/{event}/publish', [ManagedEventController::class, 'publish'])->name('events.publish');
        Route::post('/events/{event}/unpublish', [ManagedEventController::class, 'unpublish'])->name('events.unpublish');
        Route::post('/events/{event}/start', [ManagedEventController::class, 'start'])->name('events.start');
        Route::post('/events/{event}/end', [ManagedEventController::class, 'end'])->name('events.end');
        Route::get('/events/{event}/judging', [JudgingController::class, 'dashboard'])->name('events.judging');
        Route::post('/events/{event}/rubrics', [JudgingController::class, 'assignRubric'])->name('events.rubrics.store');
        Route::post('/events/{event}/judges', [JudgingController::class, 'assignJudge'])->name('events.judges.store');
        Route::post('/events/{event}/judging/finalize', [JudgingController::class, 'finalize'])->name('events.judging.finalize');
    });

    Route::patch('/assistance-requests/{assistanceRequest}', [AssistanceRequestController::class, 'update'])->name('assistance-requests.update');
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::patch('/admin/events/{event}', [AdminController::class, 'moderateEvent'])->name('admin.events.moderate');
    Route::patch('/admin/users/{user}', [AdminController::class, 'moderateUser'])->name('admin.users.moderate');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
