<?php

use App\Http\Controllers\Admin\TutorController;
use App\Http\Controllers\Auth\SocialController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\GoogleController;


use App\Http\Controllers\HomeController;
use App\Http\Controllers\PromocionesController;
use App\Http\Controllers\Impersonate;
use App\Http\Controllers\OpenAiController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\ExportImageController; // Added ExportImageController
use App\Livewire\Frontend\BlogDetails;
use App\Livewire\Frontend\Blogs;
use App\Livewire\Frontend\Checkout;
use App\Livewire\Frontend\ThankYou;
use App\Livewire\Pages\Common\Bookings\UserBooking;
use App\Livewire\Pages\Common\Dispute\Dispute;
use App\Livewire\Pages\Common\Dispute\ManageDispute;
use App\Livewire\Pages\Common\ProfileSettings\AccountSettings;
use App\Livewire\Pages\Common\ProfileSettings\IdentityVerification;
use App\Livewire\Pages\Common\ProfileSettings\PersonalDetails;
use App\Livewire\Pages\Common\ProfileSettings\Resume;
use App\Livewire\Pages\Student\BillingDetail\BillingDetail;
use App\Livewire\Pages\Student\CertificateList;
use App\Livewire\Pages\Student\Favourite\Favourites;
use App\Livewire\Pages\Student\Invoices;
use App\Livewire\Pages\Student\RescheduleSession;

use App\Livewire\Pages\Tutor\ManageAccount\ManageAccount;
use App\Livewire\Pages\Tutor\ManageSessions\ManageSubjects;
use App\Livewire\Pages\Tutor\CompanyCourses\Courses;
use App\Http\Controllers\PaymentController;

use App\Livewire\Pages\Tutor\ManageSessions\MyCalendar;
use App\Livewire\Pages\Tutor\ManageSessions\SessionDetail;
use App\Livewire\Payouts;
use App\Http\Controllers\GoogleMeetController;
use App\Services\GoogleMeetService;
use Illuminate\Support\Facades\Route;

Route::get('/verify', function (\Illuminate\Http\Request $request) {
    $id = $request->query('id');
    $hash = $request->query('hash');
    $status = null;
    $message = null;
    $redirect = null;

    if ($id && $hash) {
        $user = \App\Models\User::find($id);
        if ($user && hash_equals($hash, sha1($user->email))) {
            if (!$user->email_verified_at) {
                $user->email_verified_at = now();
                $user->save();
                $status = 'success';
                $message = 'Correo verificado correctamente.';
            } else {
                $status = 'info';
                $message = 'El correo ya estaba verificado.';
            }
            // Redirección según el rol
            if ($user->hasRole('tutor')) {
                $redirect = url('/tutor/dashboard');
            } elseif ($user->hasRole('student')) {
                $redirect = url('/student/bookings');
            }
        } else {
            $status = 'error';
            $message = 'El enlace de verificación no es válido.';
        }
    } else {
        $status = 'error';
        $message = 'Parámetros inválidos.';
    }

    return view('verify', [
        'status' => $status,
        'message' => $message,
        'redirect' => $redirect,
        'id' => $id,
        'hash' => $hash,
    ]);
});
Route::get('/prueba', function () {
    return '¡Ruta de prueba funcionando!';
});
;


//OJO -------> Debe de estar dentro del grupo de rutas para el rol TUTOR
//Route::get('{slug}/ficha/{id}', [ExportImageController::class, 'exportFicha'])->name('tutor.ficha');
Route::get('/tutor/ficha/{slug}/{id}', [ExportImageController::class, 'index'])->name('tutor.ficha');
Route::get('/tutor/ficha-img/{slug}/{id}', [ExportImageController::class, 'exportFicha'])->name('tutor.ficha.img');
Route::get('/tutor/ficha-download/{slug}/{id}', [ExportImageController::class, 'downloadFicha'])->name('tutor.ficha.download');



Route::get('auth/{provider}', [SocialController::class, 'redirect'])->name('social.redirect');
Route::get('auth/{provider}/callback', [SocialController::class, 'callback'])->name('social.callback');

Route::get('/pay-qr/{orderId}', [PaymentController::class, 'showQR'])->name('pay-qr');

Route::get('/google/authenticate', [GoogleController::class, 'authenticate'])->name('google.authenticate');
Route::get('/auth/api/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

Route::get('auth/{provider}', [SocialController::class, 'redirect'])->name('social.redirect');


Route::middleware(['locale', 'maintenance'])->group(function () {
    Route::get('find-tutors', [SearchController::class, 'findTutors'])->name('find-tutors');
    Route::get('/blogs', Blogs::class)->name('blogs');
    Route::get('/blog/{slug}', BlogDetails::class)->name('blog-details');
    Route::view('/subscriptions-page', 'subscriptions-page');

    // <==== Grillo kkk ===>
    Route::get('/home', [HomeController::class, 'index']);
    Route::get('/nosotros', [HomeController::class, 'nosotros'])->name('nosotros');
    Route::view('/como-trabajamos', 'vistas.view.pages.trabajamos')->name('como-trabajamos');
    Route::view('/preguntas', 'vistas.view.pages.preguntas')->name('preguntas');

    Route::get('/promociones', [PromocionesController::class, 'index'])->name('promociones');
    // promociones vista ejemplo    
    Route::post('tutor/favourite', [SearchController::class, 'favouriteTutor'])->name('tutor.favourite');



    Route::middleware(['auth', 'verified', 'onlineUser'])->group(function () {
        Route::post('/openai/submit', [OpenAiController::class, 'submit'])->name('openai.submit');
        Route::post('favourite-tutor', [SearchController::class, 'favouriteTutor'])->name('favourite-tutor');
        Route::get('logout', [SiteController::class, 'logout'])->name('logout');
        Route::get('user/identity-confirmation/{id}', [PersonalDetails::class, 'confirmParentVerification'])->name('confirm-identity');
        
        Route::get('google/callback', [SiteController::class, 'getGoogleToken']);
        
        Route::middleware('student')->get('checkout', Checkout::class)->name('checkout');
        Route::middleware('student')->get('thank-you/{id}', ThankYou::class)->name('thank-you');
        Route::middleware('role:tutor')->prefix('tutor')->name('tutor.')->group(function () {
            Route::get('dashboard', ManageAccount::class)->name('dashboard');
            Route::get('payouts', Payouts::class)->name('payouts');
            Route::get('profile', fn() => redirect('tutor.profile.personal-details'))->name('profile');

            //Route::get('/descargar-ficha/{id}', [ExportImageController::class, 'exportFicha'])->name('ficha');


            Route::prefix('profile')->name('profile.')->group(function () {
                Route::get('personal-details', PersonalDetails::class)->name('personal-details');
                Route::get('account-settings', AccountSettings::class)->name('account-settings');
                Route::get('courses', Courses::class)->name('courses');
                Route::prefix('resume')->name('resume.')->group(function () {
                    Route::get('education', Resume::class)->name('education');
                    Route::get('experience', Resume::class)->name('experience');
                    Route::get('certificate', Resume::class)->name('certificate');
                });
                Route::get('identification', IdentityVerification::class)->name('identification');
            });
            Route::prefix('bookings')->name('bookings.')->group(function () {
                Route::get('manage-subjects', ManageSubjects::class)->name('subjects');
                Route::get('manage-sessions', MyCalendar::class)->name('manage-sessions');
                Route::get('session-detail/{date}', SessionDetail::class)->name('session-detail');
                Route::get('upcoming-bookings', UserBooking::class)->name('upcoming-bookings');
            });

            Route::get('invoices', Invoices::class)->name('invoices');
            Route::get('disputes', Dispute::class)->name('disputes');
            Route::get('manage-dispute/{id}', ManageDispute::class)->name('manage-dispute');
        });

        Route::middleware('student')->prefix('student')->name('student.')->group(function () {
            Route::get('profile', fn() => redirect('tutor.profile.personal-details'))->name('profile');
            Route::prefix('profile')->name('profile.')->group(function () {
                Route::get('personal-details', PersonalDetails::class)->name('personal-details');
                Route::get('account-settings', AccountSettings::class)->name('account-settings');
                Route::get('identification', IdentityVerification::class)->name('identification');
            });
            Route::get('bookings', UserBooking::class)->name('bookings');
            Route::get('invoices', Invoices::class)->name('invoices');
            Route::get('billing-detail', BillingDetail::class)->name('billing-detail');
            Route::get('favourites', Favourites::class)->name('favourites');
            Route::get('reschedule-session/{id}', RescheduleSession::class)->name('reschedule-session');
            Route::get('complete-booking/{id}', [SiteController::class, 'completeBooking'])->name('complete-booking');
            Route::get('certificates', CertificateList::class)->name('certificate-list');
            Route::get('disputes', Dispute::class)->name('disputes');
            Route::get('manage-dispute/{id}', ManageDispute::class)->name('manage-dispute');
        });
    });

    Route::post('/remove-cart', [SiteController::class, 'removeCart']);

    Route::get('tutor/{slug}', [SearchController::class, 'tutorDetail'])->name('tutor-detail');
    Route::get('{gateway}/process/payment', [SiteController::class, 'processPayment'])->name('payment.process');
    Route::get('checkout/cancel', fn() => redirect()->route('invoices')->with('payment_cancel', __('general.payment_cancelled_desc')))->name('checkout.cancel');
    Route::post('payfast/webhook', [SiteController::class, 'payfastWebhook'])->name('payfast.webhook');
    Route::post('payment/success', [SiteController::class, 'paymentSuccess'])->name('post.success');
    Route::get('payment/success', [SiteController::class, 'paymentSuccess'])->name('get.success');
    Route::post('switch-lang', [SiteController::class, 'switchLang'])->name('switch-lang');
    Route::post('switch-currency', [SiteController::class, 'switchCurrency'])->name('switch-currency');
    Route::get('exit-impersonate', [Impersonate::class, 'exitImpersonate'])->name('exit-impersonate');
    Route::get('pay/{id}', [SiteController::class, 'preparePayment'])->name('pay');
    require __DIR__ . '/auth.php';

    require __DIR__ . '/admin.php';
    require __DIR__ . '/optionbuilder.php';
    if (!request()->is('api/*')) {
        require __DIR__ . '/pagebuilder.php';
    }
});


