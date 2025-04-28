@extends('layouts/frontend-app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="am-banner-potential am-banner-content-seven">
                <img class="am-banner-img" src="{{asset('demo-content/home-v7/banner-img.jpg')}}" alt="company logo">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="am-banner-main">
                                <div class="am-banner-tutor">
                                    <h2>Unleash Your Potential with <span>Top Mentors</span></h2>
                                    <p>Reach your goals with tailored tutoring from top experts. Connect with dedicated tutors for your success.</p>
                                    <div class="am-explore-banner-button">
                                        <a href="#" class="am-explore-btn"> 
                                            Explore all tutors
                                        </a>
                                        <a href="#" class="am-explore-btn am-demo-btn">
                                            <i class="am-icon-play-filled"></i>
                                            See demo
                                        </a>
                                    </div>
                                </div>
                                <ul class="am-banner_companies am-banner-image">
                                    <li>
                                        <figure>
                                            <img src="{{asset('demo-content/home-v2/banner/jira-white-01.png')}}" alt="company logo">
                                        </figure>
                                    </li>
                                    <li>
                                        <figure>
                                            <img src="{{asset('demo-content/home-v2/banner/jira-white-02.png')}}" alt="company logo">
                                        </figure>
                                    </li>
                                    <li>
                                        <figure>
                                            <img src="{{asset('demo-content/home-v2/banner/jira-white-03.png')}}" alt="company logo">
                                        </figure>
                                    </li>
                                    <li>
                                        <figure>
                                            <img src="{{asset('demo-content/home-v2/banner/jira-white-04.png')}}" alt="company logo">
                                        </figure>
                                    </li>
                                    <li>
                                        <figure>
                                            <img src="{{asset('demo-content/home-v2/banner/jira-white-05.png')}}" alt="company logo">
                                        </figure>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/aos.min.js') }}"></script>
<script section='banner-v4'>
    document.addEventListener('DOMContentLoaded', (event) => {
        initVenobox()
    });
    AOS.init();
</script>
@endsection