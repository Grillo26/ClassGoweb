@extends('layouts.frontend-app')
@section('content')
<div class="splide am-banner-slider" id="hero-slider">
  <div class="splide__track">
    <ul class="splide__list">
      <li class="splide__slide">
        <div class="am-hero-container">
          <div class="am-hero-background">
            <img src="{{ asset('demo-content/home-v8/banner-bgimg.png') }}" alt="Image Slice" class="image slice slice1">
            <img src="{{ asset('demo-content/home-v8/banner-bgimg.png') }}" alt="Image Slice" class="image slice slice2">
            <img src="{{ asset('demo-content/home-v8/banner-bgimg.png') }}" alt="Image Slice" class="image slice slice3">
          </div>
          <!-- <div class="am-vertical-line-left"></div>
          <div class="am-vertical-line-right"></div>
          <div class="am-highlight-bar"></div> -->
          <div class="am-banner-content">
            <div class="am-hero-content">
              <div class="am-yellow-bar"></div>
              <h1 class="am-hero-title"> <span>Unleash Your</span> <span>Potential with</span> <span>Top Tutors</span></h1>
            </div>
            <div class="am-hero-description-wrapper">
              <p class="am-hero-description">Achieve your goals with personalized tutoring from top experts, tailored to your learning needs. Connect with dedicated tutors committed to helping you succeed.</p>
              <div class="am-partner-logos">
                <img src="{{ asset('demo-content/home-v8/jira-software.png') }}" alt="Partner logo 1" class="am-partner-logo am-partner-logo-1">
                <img src="{{ asset('demo-content/home-v8/dribbble.png') }}" alt="Partner logo 2" class="am-partner-logo am-partner-logo-2">
                <img src="{{ asset('demo-content/home-v8/livechat.png') }}" alt="Partner logo 3" class="am-partner-logo am-partner-logo-3">
                <img src="{{ asset('demo-content/home-v8/dropbox.png') }}" alt="Partner logo 4" class="am-partner-logo am-partner-logo-4">
              </div>
            </div>
            <div class="am-hero-footer">
              <div class="am-slide-counter">
                <span class="am-current-slide">01</span>
                <span class="am-total-slides">/ 03</span>
              </div>
            </div>
          </div>
        </div>
      </li>
      <li class="splide__slide">
        <div class="am-hero-container">
          <div class="am-hero-background">
            <video width="" height="" class="image slice slice1" autoplay>
              <source src="{{ asset('demo-content/home-v8/banner-bg.mp4') }}">
            </video>
            <video width="" height="" class="image slice slice2" autoplay>
              <source src="{{ asset('demo-content/home-v8/banner-bg.mp4') }}">
            </video>
            <video width="" height="" class="image slice slice3" autoplay>
              <source src="{{ asset('demo-content/home-v8/banner-bg.mp4') }}">
            </video>
            <!-- <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/fbe889398cd440a7a82b115f2f42e9ae15c3fde50650ce67d3f05f7d9b8aa356?placeholderIfAbsent=true&apiKey=a7d6445dca254bd481af90408dc7f57d" alt="Image Slice" class="image slice slice1">
            <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/fbe889398cd440a7a82b115f2f42e9ae15c3fde50650ce67d3f05f7d9b8aa356?placeholderIfAbsent=true&apiKey=a7d6445dca254bd481af90408dc7f57d" alt="Image Slice" class="image slice slice2">
            <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/fbe889398cd440a7a82b115f2f42e9ae15c3fde50650ce67d3f05f7d9b8aa356?placeholderIfAbsent=true&apiKey=a7d6445dca254bd481af90408dc7f57d" alt="Image Slice" class="image slice slice3"> -->
          </div>
          <div class="am-banner-content">
            <div class="am-hero-content">
              <div class="am-yellow-bar"></div>
              <h1 class="am-hero-title"> <span>Unleash Your</span> <span>Potential with</span> <span>Top Tutors</span></h1>
            </div>
            <div class="am-hero-description-wrapper">
              <p class="am-hero-description">Achieve your goals with personalized tutoring from top experts, tailored to your learning needs. Connect with dedicated tutors committed to helping you succeed.</p>
              <div class="am-partner-logos">
                <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/4d1bc427a1643a9c1bacf0b113d471fda3cb7c42899493266f88f7e24bbd665b?placeholderIfAbsent=true&apiKey=a7d6445dca254bd481af90408dc7f57d" alt="Partner logo 1" class="am-partner-logo am-partner-logo-1">
                <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/ff9f4e0af1109d78f6735531e1bc288401dcf63045e3da3a6d1e4b2f1485934d" alt="Partner logo 2" class="am-partner-logo am-partner-logo-2">
                <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/5f982cdc8ad1ee201a8be700ce2f1a3e96549cef47c0b6a80a48ebae477cac2c" alt="Partner logo 3" class="am-partner-logo am-partner-logo-3">
                <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/b272268fab6d5420bb57a0ee32b0b2384e97636cd28f04eb5cab59dc6df3f103" alt="Partner logo 4" class="am-partner-logo am-partner-logo-4">
              </div>
            </div>
            <div class="am-hero-footer">
              <div class="am-slide-counter">
                <span class="am-current-slide">02</span>
                <span class="am-total-slides">/ 03</span>
              </div>
            </div>
          </div>
        </div>
      </li>
      <li class="splide__slide">
        <div class="am-hero-container">
          <div class="am-hero-background">
            <img src="{{ asset('demo-content/home-v8/banner-bgimg.png') }}" alt="Image Slice" class="image slice slice1">
            <img src="{{ asset('demo-content/home-v8/banner-bgimg.png') }}" alt="Image Slice" class="image slice slice2">
            <img src="{{ asset('demo-content/home-v8/banner-bgimg.png') }}" alt="Image Slice" class="image slice slice3">
          </div>
          <!-- <div class="am-vertical-line-left"></div>
          <div class="am-vertical-line-right"></div>
          <div class="am-highlight-bar"></div> -->
          <div class="am-banner-content">
            <div class="am-hero-content">
              <div class="am-yellow-bar"></div>
              <h1 class="am-hero-title"> <span>Unleash Your</span> <span>Potential with</span> <span>Top Tutors</span></h1>
            </div>
            <div class="am-hero-description-wrapper">
              <p class="am-hero-description">Achieve your goals with personalized tutoring from top experts, tailored to your learning needs. Connect with dedicated tutors committed to helping you succeed.</p>
              <div class="am-partner-logos">
                <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/4d1bc427a1643a9c1bacf0b113d471fda3cb7c42899493266f88f7e24bbd665b?placeholderIfAbsent=true&apiKey=a7d6445dca254bd481af90408dc7f57d" alt="Partner logo 1" class="am-partner-logo am-partner-logo-1">
                <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/ff9f4e0af1109d78f6735531e1bc288401dcf63045e3da3a6d1e4b2f1485934d" alt="Partner logo 2" class="am-partner-logo am-partner-logo-2">
                <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/5f982cdc8ad1ee201a8be700ce2f1a3e96549cef47c0b6a80a48ebae477cac2c" alt="Partner logo 3" class="am-partner-logo am-partner-logo-3">
                <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/b272268fab6d5420bb57a0ee32b0b2384e97636cd28f04eb5cab59dc6df3f103" alt="Partner logo 4" class="am-partner-logo am-partner-logo-4">
              </div>
            </div>
            <div class="am-hero-footer">
              <div class="am-slide-counter">
                <span class="am-current-slide">03</span>
                <span class="am-total-slides">/ 03</span>
              </div>
            </div>
          </div>
        </div>
      </li>
    </ul>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const splide = new Splide('#hero-slider', {
    type: 'fade', 
    rewind: true,
    arrows: true,
    autoplay: true,
    interval: 5000,
    pagination: false,
  });

  splide.on('moved', (newIndex) => {
    // Get all slide elements
    const slides = document.querySelectorAll('.splide__slide');

    // Remove animation classes from all slides
    slides.forEach((slide) => {
      slide.classList.remove('animate-slices', 'animate-text');
    });

    // Add animation classes to the current slide
    const activeSlide = slides[newIndex];
    activeSlide.classList.add('animate-slices', 'animate-text');
  });

  // Initial animation on the first slide
  document.querySelector('.splide__slide').classList.add('animate-slices', 'animate-text');

  // Mount Splide
  splide.mount();
});
</script>

@endsection