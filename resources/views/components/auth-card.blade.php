<div class="am-main-login">
    <div class="am-auth-page">
        <div class="am-login-left">
            {{ $logo }} 
            <div class="am-login-left_title">
                <h2>{{ __('auth.login_left_h2') }}</h2>
                <span>{{ __('auth.login_left_span') }}</span>
            </div>
        </div>
    </div>
    <div class="am-login-right">
        {{ $formHeader }}
        {{ $slot }}
    </div>
</div>
