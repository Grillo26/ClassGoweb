@extends('layouts.frontend-app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('modules/subscriptions/css/main.css') }}">
@endpush
@section('content')

<!-- =========== Banner Start =========== -->
<div class="am-learning am-subcription-banner"> 
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="am-learning_content">
                    <div class="am-learning_details">
                        <h1>
                            <span>Premium Fatures </span>
                            and tools for an enhanced eLearning experience.
                        </h1>
                        <p>Access advanced tools, personalized support, and exclusive features for a smoother eLearning experience.</p>
                        <div class="am-btns">       
                            <a href="#" class="am-primary-btn">
                                Student Packages
                            </a>  
                            <a href="#" class="am-primary-btn-white">
                                Tutor Packages
                            </a>
                        </div>
                    </div>
                    <div class="am-learning_video">
                        <figure class="am-learning_video_img">
                            <img src="{{asset('/demo-content/subscription/banner-img.png')}}" alt="banner image">
                        </figure>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- =========== Banner End =========== -->
 <!-- =========== Subscription Start =========== -->
 <div class="am-subscription">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="am-subscription_content">
                    <div class="am-steps_content_unlock">
                        <span>Premium Features Unlocked</span>  
                        <h3>Achieve More with Student Plans</h3>
                        <p>Upgrade to a plan that matches your learning goals!</p>
                    </div>
                    
                    <div class="row justify-content-center">
                        <!-- Basic Plan -->
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="am-pricing-card">
                                <div class="am-plan-header">
                                    <div class="am-plan-icon-wrapper">
                                        <div class="am-plan-icon">
                                            <i class="am-icon-book-1"></i>
                                        </div>
                                    </div>
                                    <h2 class="am-plan-title">Basic Learner Plan</h2>
                                    <p class="am-plan-description">
                                        Unlock premium, priority support and exclusive features with our Gold Pricing Plan
                                    </p>
                                    <div class="am-price-container">
                                        <span class="am-price-amount">$100.00</span>
                                        <span class="am-price-period">/ Year</span>
                                    </div>
                                </div>
                                <div class="am-features-section">
                                    <div class="am-features-container">
                                        <h3 class="am-features-title">Features</h3>
                                        <ul class="am-features-list">
                                            <li class="am-feature-item">
                                                <i class="am-icon-check-circle03"></i>
                                                <span class="am-feature-label">Admin share</span>
                                                <span class="am-feature-value">15%</span>
                                            </li>
                                            <li class="am-feature-item">
                                                <i class="am-icon-check-circle03"></i>
                                                <span class="am-feature-label">Maximum sessions can buy</span>
                                                <span class="am-feature-value">15</span>
                                            </li>
                                            <li class="am-feature-item">
                                                <i class="am-icon-check-circle03"></i>
                                                <span class="am-feature-label">Maximum courses can enroll</span>
                                                <span class="am-feature-value">20</span>
                                            </li>
                                            <li class="am-feature-item">
                                                <i class="am-icon-check-circle03"></i>
                                                <span class="am-feature-label">Revenue for sessions</span>
                                                <span class="am-feature-value">10%</span>
                                            </li>
                                            <li class="am-feature-item">
                                                <i class="am-icon-check-circle03"></i>
                                                <span class="am-feature-label">Revenue for courses</span>
                                                <span class="am-feature-value">20%</span>
                                            </li>
                                            <li class="am-feature-item">
                                                <i class="am-icon-check-circle03"></i>
                                                <span class="am-feature-label">Quota rollover</span>
                                                <span class="am-feature-value">No</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <button class="am-white-btn am-buy-button">Buy Now</button>
                                </div>
                            </div>
                        </div>

                        <!-- Pro Plan -->
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="am-pricing-card am-active">
                                <div class="am-plan-header">
                                    <div class="am-plan-icon-wrapper">
                                        <div class="am-plan-icon">
                                            <i class="am-icon-book"></i>
                                        </div>
                                    </div>
                                    <h2 class="am-plan-title">Pro Learner Plan</h2>
                                    <p class="am-plan-description">
                                        Unlock premium, priority support and exclusive features with our Gold Pricing Plan
                                    </p>
                                    <div class="am-price-container">
                                        <span class="am-price-amount">$200.00</span>
                                        <span class="am-price-period">/ Monthly</span>
                                    </div>
                                </div>
                                <div class="am-features-section">
                                    <div class="am-features-container">
                                        <h3 class="am-features-title">Features</h3>
                                        <ul class="am-features-list">
                                            <li class="am-feature-item">
                                                <i class="am-icon-check-circle03"></i>
                                                <span class="am-feature-label">Admin share</span>
                                                <span class="am-feature-value">15%</span>
                                            </li>
                                            <li class="am-feature-item">
                                                <i class="am-icon-check-circle03"></i>
                                                <span class="am-feature-label">Maximum sessions can buy</span>
                                                <span class="am-feature-value">15</span>
                                            </li>
                                            <li class="am-feature-item">
                                                <i class="am-icon-check-circle03"></i>
                                                <span class="am-feature-label">Maximum courses can enroll</span>
                                                <span class="am-feature-value">20</span>
                                            </li>
                                            <li class="am-feature-item">
                                                <i class="am-icon-check-circle03"></i>
                                                <span class="am-feature-label">Revenue for sessions</span>
                                                <span class="am-feature-value">10%</span>
                                            </li>
                                            <li class="am-feature-item">
                                                <i class="am-icon-check-circle03"></i>
                                                <span class="am-feature-label">Revenue for courses</span>
                                                <span class="am-feature-value">20%</span>
                                            </li>
                                            <li class="am-feature-item">
                                                <i class="am-icon-check-circle03"></i>
                                                <span class="am-feature-label">Quota rollover</span>
                                                <span class="am-feature-value">No</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <button class="am-white-btn am-buy-button">
                                        Active Subscription
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                            <path d="M3 9.75L6.75 13.5L15 4.5" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Elite Plan -->
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="am-pricing-card">
                            <div class="am-plan-header">
                                    <div class="am-plan-icon-wrapper">
                                        <div class="am-plan-icon">
                                            <i class="am-icon-shield-check"></i>
                                        </div>
                                    </div>
                                    <h2 class="am-plan-title">Elite Learner Plan</h2>
                                    <p class="am-plan-description">
                                        Unlock premium, priority support and exclusive features with our Gold Pricing Plan
                                    </p>
                                    <div class="am-price-container">
                                        <span class="am-price-amount">$300.00</span>
                                        <span class="am-price-period">/ Year</span>
                                    </div>
                                </div>
                                <div class="am-features-section">
                                    <div class="am-features-container">
                                        <h3 class="am-features-title">Features</h3>
                                        <ul class="am-features-list">
                                            <li class="am-feature-item">
                                                <i class="am-icon-check-circle03"></i>
                                                <span class="am-feature-label">Admin share</span>
                                                <span class="am-feature-value">15%</span>
                                            </li>
                                            <li class="am-feature-item">
                                                <i class="am-icon-check-circle03"></i>
                                                <span class="am-feature-label">Maximum sessions can buy</span>
                                                <span class="am-feature-value">15</span>
                                            </li>
                                            <li class="am-feature-item">
                                                <i class="am-icon-check-circle03"></i>
                                                <span class="am-feature-label">Maximum courses can enroll</span>
                                                <span class="am-feature-value">20</span>
                                            </li>
                                            <li class="am-feature-item">
                                                <i class="am-icon-check-circle03"></i>
                                                <span class="am-feature-label">Revenue for sessions</span>
                                                <span class="am-feature-value">10%</span>
                                            </li>
                                            <li class="am-feature-item">
                                                <i class="am-icon-check-circle03"></i>
                                                <span class="am-feature-label">Revenue for courses</span>
                                                <span class="am-feature-value">20%</span>
                                            </li>
                                            <li class="am-feature-item">
                                                <i class="am-icon-check-circle03"></i>
                                                <span class="am-feature-label">Quota rollover</span>
                                                <span class="am-feature-value">No</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <button class="am-white-btn am-buy-button">Buy Now</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="am-learning-insights">
                        <div class="am-insights-section">
                            <h3 class="am-section-title">Learning Insights</h3>
                            <div class="am-insights-content">
                                <div class="am-content-row">
                                    <div class="am-feature-item2">
                                        <i class="am-icon-check-circle06"></i>
                                        <p class="am-feature-text">Basic progress tracking.</p>
                                    </div>
                                    <div class="am-feature-item2">
                                        <i class="am-icon-check-circle06"></i>
                                        <p class="am-feature-text">Basic progress tracking.</p>
                                    </div>
                                    <div class="am-feature-item2">
                                        <i class="am-icon-check-circle06"></i>
                                        <p class="am-feature-text">Basic progress tracking.</p>
                                    </div>
                                    <div class="am-feature-item2">
                                        <i class="am-icon-check-circle06"></i>
                                        <p class="am-feature-text">Advanced learning analytics to monitor your development</p>
                                    </div>
                                    <div class="am-feature-item2">
                                        <i class="am-icon-multiply-02"></i>
                                        <p class="am-feature-text">Advanced learning analytics to monitor your development</p>
                                    </div>
                                    <div class="am-feature-item2">
                                        <i class="am-icon-multiply-02"></i>
                                        <p class="am-feature-text">Advanced learning analytics to monitor your development</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="am-support-section">
                            <h3 class="am-section-title">Support Availability</h3>
                            <div class="am-support-content">
                                <div class="am-content-row">
                                    <div class="am-feature-item2">
                                        <i class="am-icon-multiply-02"></i>
                                        <p class="am-feature-text">Standard customer support available</p>
                                    </div>
                                    <div class="am-feature-item2">
                                        <i class="am-icon-check-circle06"></i>
                                        <p class="am-feature-text">Standard customer support available</p>
                                    </div>
                                    <div class="am-feature-item2">
                                        <i class="am-icon-check-circle06"></i>
                                        <p class="am-feature-text">Standard customer support available</p>
                                    </div>
                                    <div class="am-feature-item2">
                                        <i class="am-icon-multiply-02"></i>
                                        <p class="am-feature-text">24/7 dedicated support for any immediate concerns.</p>
                                    </div>
                                    <div class="am-feature-item2">
                                        <i class="am-icon-check-circle06"></i>
                                        <p class="am-feature-text">24/7 dedicated support for any immediate concerns.</p>
                                    </div>
                                    <div class="am-feature-item2">
                                        <i class="am-icon-check-circle06"></i>
                                        <p class="am-feature-text">24/7 dedicated support for any immediate concerns.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="am-resources-section">
                            <h3 class="am-section-title">Resource Library</h3>
                            <div class="am-resources-content">
                                <div class="am-content-row">
                                    <div class="am-feature-item2">
                                        <i class="am-icon-check-circle06"></i>
                                        <p class="am-feature-text">Limited access to free resources and study materials.</p>
                                    </div>
                                    <div class="am-feature-item2">
                                        <i class="am-icon-check-circle06"></i>
                                        <p class="am-feature-text">Limited access to free resources and study materials.</p>
                                    </div>
                                    <div class="am-feature-item2">
                                        <i class="am-icon-check-circle06"></i>
                                        <p class="am-feature-text">Limited access to free resources and study materials.</p>
                                    </div>
                                    <div class="am-feature-item2">
                                        <i class="am-icon-check-circle06"></i>
                                        <p class="am-feature-text">Full access to exclusive guides, & practice materials.</p>
                                    </div>
                                    <div class="am-feature-item2">
                                        <i class="am-icon-check-circle06"></i>
                                        <p class="am-feature-text">24/7 dedicated support for any immediate concerns.</p>
                                    </div>
                                    <div class="am-feature-item2">
                                        <i class="am-icon-check-circle06"></i>
                                        <p class="am-feature-text">Full access to exclusive guides, & practice materials.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- =========== Subscription End =========== -->
<!-- =========== How it works Start =========== -->
<div class="am-marketplace am-how-itwork">
    <img class="am-shap-image" src="{{ asset('/demo-content/subscription/shap.png') }}" alt="shap image">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="am-explore-tutor">
                    <div class="am-steps_content_unlock">
                        <span>How it works</span>
                        <h3>Upgrade for a better eLearning experience.</h3>
                        <p>Upgrade your subscription to unlock advanced eLearning features and resources.</p>
                    </div>
                    <a class="am-btn" href="#">
                        Join as a Student
                    </a>
                </div>
                <div class="am-how-itwork-content row">
                    <div class="col-12 col-lg-4 col-md-6 mb-4">
                        <div class="am-steps_content_start_info">
                            <span>STEP 1</span>
                            <figure>
                                <img src="{{ asset('/demo-content/subscription/howitwork-img01.png') }}" alt="image">
                            </figure>
                            <div class="am-steps_content_start_info_redirect">
                                <h3>Choose Plans</h3>
                                <p>Explore a range of options designed to suit your needs and goals.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4 col-md-6 mb-4">
                        <div class="am-steps_content_start_info">
                            <span>STEP 2</span>
                            <figure>
                                <img src="{{ asset('/demo-content/subscription/howitwork-img02.png') }}" alt="image">
                            </figure>
                            <div class="am-steps_content_start_info_redirect">
                                <h3>Choose your payment method</h3>
                                <p>Select your preferred payment option for a seamless subscription experience.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4 col-md-6 mb-4">
                        <div class="am-steps_content_start_info">
                            <span>STEP 3</span>
                            <figure>
                                <img src="{{ asset('/demo-content/subscription/howitwork-img03.png') }}" alt="image">
                            </figure>
                            <div class="am-steps_content_start_info_redirect">
                                <h3>Join Live Calls with Zoom Integration</h3>
                                <p>Join live interactive sessions seamlessly with Zoom integration for a personalized learning.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- =========== How it works End =========== -->
<!-- =========== FAQ Star =========== -->
<div class="am-faqs">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="am-steps_content_unlock">
                    <span>Frequently Asked Questions</span>
                    <h3>Unlock Your eLearning Potential!</h3>
                    <p>Discover Common Solutions and Access Official Guidance</p>
                </div>
                <div class="am-faq-page">
                    <div class="am-faqtab-content">
                        <div class="accordion accordion-flush" id="accordionFlushExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingOne">
                                    <span>
                                        <i class="am-icon-file-02"></i>
                                    </span>
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                        How do I find a tutor?
                                    </button>
                                </h2>
                                <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        Use the search bar and filters on the "Find a Tutor" page to search for tutors by subject, availability, rating, and more.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingtwo">
                                    <span>
                                        <i class="am-icon-book-1"></i>
                                    </span>
                                    <button class="accordion-button collapsed show" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsetwo" aria-expanded="true" aria-controls="flush-collapsetwo">
                                        How do I book a session?
                                    </button>
                                </h2>
                                <div id="flush-collapsetwo" class="accordion-collapse collapse show" aria-labelledby="flush-headingtwo" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        Once you find a tutor, view their profile and select an available time slot that suits you. Click "Book Now" and follow the prompts to confirm your session.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingthree">
                                    <span>
                                        <i class="am-icon-lock-close"></i>
                                    </span>
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsethree" aria-expanded="false" aria-controls="flush-collapsethree">
                                        What steps should I take if I forget my password, and how long will it take to reset it?
                                    </button>
                                </h2>
                                <div id="flush-collapsethree" class="accordion-collapse collapse" aria-labelledby="flush-headingthree" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        Use the search bar and filters on the "Find a Tutor" page to search for tutors by subject, availability, rating, and more.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingfour">
                                    <span>
                                        <i class="am-icon-user-01"></i>
                                    </span>
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefour" aria-expanded="false" aria-controls="flush-collapsefour">
                                        What if I need to cancel or reschedule a session?
                                    </button>
                                </h2>
                                <div id="flush-collapsefour" class="accordion-collapse collapse" aria-labelledby="flush-headingfour" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        Use the search bar and filters on the "Find a Tutor" page to search for tutors by subject, availability, rating, and more.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingfive">
                                    <span>
                                        <i class="am-icon-file-02"></i>
                                    </span>
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-collapsefive">
                                        How do I pay for sessions?
                                    </button>
                                </h2>
                                <div id="flush-collapsefive" class="accordion-collapse collapse" aria-labelledby="flush-headingfive" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        Use the search bar and filters on the "Find a Tutor" page to search for tutors by subject, availability, rating, and more.
                                    </div>
                                </div>
                            </div>
                        </div>           
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- =========== FAQ End =========== -->

@endsection