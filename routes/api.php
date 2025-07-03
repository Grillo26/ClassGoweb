<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BillingDetailController;
use App\Http\Controllers\Api\TaxonomiesController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\CertificationController;
use App\Http\Controllers\Api\EducationController;
use App\Http\Controllers\Api\ExperienceController;
use App\Http\Controllers\Api\AccountSettingController;
use App\Http\Controllers\Api\FavouriteTutorController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\OptionBuilderController;
use App\Http\Controllers\Api\IdentityController;
use App\Http\Controllers\Api\CartController;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\PayoutController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\TutorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SubjectSlotController;
use App\Http\Controllers\Api\AlianzaController;
use App\Http\Controllers\Api\SubjectController;
use App\Http\Controllers\Api\ReviewController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login',                                            [AuthController::class,'login']);
Route::post('register',                                         [AuthController::class,'register']);
Route::post('forget-password',                                  [AuthController::class,'resetEmailPassword']);
Route::get('recommended-tutors',                                [TutorController::class,'getRecommendedTutors']);
Route::get('find-tutors',                                       [TutorController::class,'findTutots']);
Route::get('verified-tutors',                                   [TutorController::class, 'getVerifiedTutorsWithSubjects']);
Route::get('tutor/{slug}',                                      [TutorController::class,'getTutorDetail']);
Route::get('students-list',                                     [StudentController::class,'userList']);
Route::get('student-reviews/{id}',                              [StudentController::class,'getStudentReviews']);
Route::get('tutor-available-slots',                             [TutorController::class,'getTutorAvailableSlots']);
Route::get('slot-detail/{id}',                                  [TutorController::class,'slotDetail']);

Route::apiResource('tutor-education',                           EducationController::class)->only(['show','store','update','destroy']);
Route::apiResource('tutor-experience',                          ExperienceController::class)->only(['show','store','update','destroy']);
Route::apiResource('tutor-certification',                       CertificationController::class)->only(['show','store','destroy']);

Route::get('countries',                                     [TaxonomiesController::class,'getCountries']);
Route::get('languages',                                     [TaxonomiesController::class,'getLanguages']);
Route::get('states',                                        [TaxonomiesController::class,'getStates']);
Route::get('subject-slots', [SubjectSlotController::class, 'getUserSubjectSlots']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('upcoming-bookings',                             [BookingController::class,'getUpComingBooking']);
    Route::post('tutor-certification/{id}',                     [CertificationController::class,'update']);
    Route::post('reset-password',                               [AuthController::class,'resetPassword']);
    Route::post('update-password/{id}',                         [AccountSettingController::class,'updatePassword']);
    Route::post('timezone/{id}',                                [AccountSettingController::class,'updateTimezone']);
    Route::get('timezone/{id}',                                 [AccountSettingController::class,'getTimezone']);
    Route::post('send-message/{recipientId}',                   [StudentController::class,'sendMessage']);
    Route::get('resend-email',                                  [AuthController::class,'resendEmail']);
    Route::post('logout',                                       [AuthController::class,'logout']);
    Route::apiResource('favourite-tutors',                      FavouriteTutorController::class)->only('index', 'update');
    Route::post('profile-settings/{id}',                        [ProfileController::class,'updateProfile']);
    Route::get('profile-settings/{id}',                         [ProfileController::class,'getProfile']);

    Route::apiResource('identity-verification',                 IdentityController::class)->only(['show','destroy','store']);
    Route::get('invoices',                                      [InvoiceController::class,'getInvoices']);
    Route::apiResource('billing-detail',                        BillingDetailController::class)->only(['show', 'update','store']);
    
    Route::get('tutor-payouts/{id}',                            [PayoutController::class,'getPayoutHistory']);
    Route::get('my-earning/{id}',                               [PayoutController::class,'getEarning']);
    Route::get('earning-detail',                                [PayoutController::class,'getEarningDetail']);
    Route::post('user-withdrawal',                              [PayoutController::class,'userWithdrawal']);
    Route::get('payout-status',                                 [PayoutController::class,'getPayoutStatus']);
    Route::post('payout-status',                                [PayoutController::class,'updateStatus']);
    Route::post('payout-method',                                [PayoutController::class,'addPayoutMethod']);
    Route::Delete('payout-method',                              [PayoutController::class,'removePayoutMethod']);
    Route::apiResource('booking-cart',                          CartController::class);
    Route::post('checkout',                                     [CheckoutController::class,'addCheckoutDetails']);

    Route::get('reviews', [ReviewController::class, 'index']);
    Route::get('reviews/received', [ReviewController::class, 'getReceivedReviews']);
    Route::get('reviews/given', [ReviewController::class, 'getUserReviews']);
    Route::post('reviews', [ReviewController::class, 'store']);
    Route::get('reviews/{id}', [ReviewController::class, 'show']);
    Route::put('reviews/{id}', [ReviewController::class, 'update']);
    Route::delete('reviews/{id}', [ReviewController::class, 'destroy']);
    Route::get('reviews/stats/{userId}', [ReviewController::class, 'getStats']);

    Route::post('update-fcm-token', [AuthController::class, 'updateFcmToken']);

    // Ruta para obtener el tiempo disponible del tutor
    
});

// Ruta para obtener el tiempo disponible del tutor (pública)
Route::get('tutor/{id}/available-slots', [\App\Http\Controllers\Api\SubjectSlotController::class, 'getTutorAvailableSlots']);

// Ruta para obtener las tutorías del usuario autenticado (pública temporalmente)
Route::get('user/bookings', [\App\Http\Controllers\Api\BookingController::class, 'getUpComingBooking']);

Route::get('country-states',                                    [TutorController::class,'getStates']);
Route::get('subject-groups',                                   [BookingController::class,'getSubjectGroups']);
Route::get('subjects',                                         [BookingController::class,'getSubjects']);

Route::get('settings',                                         [OptionBuilderController::class, 'getOpSettings']);
Route::get('alianzas',                                          [AlianzaController::class, 'index']);
Route::get('all-subjects', [SubjectController::class, 'index']);
Route::get('verified-tutors-photos', [\App\Http\Controllers\Api\TutorController::class, 'getVerifiedTutorsPhotos']);

Route::get('reviews', [ReviewController::class, 'index']);
Route::get('reviews/received', [ReviewController::class, 'getReceivedReviews']);
Route::get('reviews/given', [ReviewController::class, 'getUserReviews']);
Route::post('reviews', [ReviewController::class, 'store']);
Route::get('reviews/{id}', [ReviewController::class, 'show']);
Route::put('reviews/{id}', [ReviewController::class, 'update']);
Route::delete('reviews/{id}', [ReviewController::class, 'destroy']);
Route::get('reviews/stats/{userId}', [ReviewController::class, 'getStats']);

// Ruta para obtener las tutorías de un usuario por su id (pública)
Route::get('user/{id}/bookings', [\App\Http\Controllers\Api\BookingController::class, 'getUserBookingsById']);

// Ruta para registrar una nueva tutoría (slot_booking)
Route::post('slot-bookings', [\App\Http\Controllers\Api\BookingController::class, 'storeSlotBooking']);

// Ruta para registrar un nuevo payment_slot_booking (renombrada para prueba)
Route::post('test-payment-upload', [\App\Http\Controllers\Api\BookingController::class, 'storePaymentSlotBooking']);



Route::fallback(function () {
    return response()->json([
        'message' => __('general.api_url_not_found'),
    ], Response::HTTP_NOT_FOUND);
});
