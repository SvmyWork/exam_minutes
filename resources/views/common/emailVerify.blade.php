<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.3s ease-out',
                        'pulse-success': 'pulseSuccess 2s ease-in-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(10px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                        pulseSuccess: {
                            '0%, 100%': { transform: 'scale(1)' },
                            '50%': { transform: 'scale(1.05)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .otp-input:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .success-checkmark {
            stroke-dasharray: 100;
            stroke-dashoffset: 100;
            animation: checkmark 0.6s ease-in-out 0.3s forwards;
        }
        @keyframes checkmark {
            to {
                stroke-dashoffset: 0;
            }
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Main Verification Card -->
        <div id="verificationCard" class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 animate-fade-in">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="mx-auto w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Verify Your Email</h1>
                <p class="text-gray-600">We've sent a 6-digit verification code to</p>
                <p class="text-blue-600 font-semibold" id="emailDisplay">{{ $email }}</p>
            </div>

            <!-- OTP Input Form -->
            <form id="otpForm" class="space-y-6">
                <div>
                    <label for="otpInput" class="block text-sm font-medium text-gray-700 mb-3 text-center">
                        Enter verification code
                    </label>
                    <div class="flex justify-center">
                        <input
                            type="text"
                            id="otpInput"
                            maxlength="6"
                            placeholder="000000"
                            class="otp-input w-48 h-14 text-center text-2xl font-mono tracking-widest border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none transition-colors duration-200"
                            autocomplete="off"
                        >
                    </div>
                    <div id="otpError" class="mt-2 text-sm text-red-600 text-center hidden animate-slide-up">
                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        Invalid verification code. Please try again.
                    </div>
                </div>

                <!-- Verify Button -->
                <button
                    type="submit"
                    id="verifyBtn"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-200"
                >
                    <span id="verifyBtnText">Verify Email</span>
                    <svg id="verifyLoader" class="hidden animate-spin w-5 h-5 inline ml-2" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </form>

            <!-- Resend Section -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600 mb-3">Didn't receive the code?</p>
                <button
                    id="resendBtn"
                    disabled
                    class="text-blue-600 hover:text-blue-800 font-semibold text-sm transition-colors duration-200 disabled:text-gray-400 disabled:cursor-not-allowed"
                >
                    <span id="resendText">Resend code in <span id="countdown">2:00</span></span>
                </button>
            </div>
        </div>

        <!-- Success Card -->
        <div id="successCard" class="hidden bg-white rounded-2xl shadow-xl border border-gray-100 p-8 animate-fade-in">
            <div class="text-center">
                <!-- Success Icon -->
                <div class="mx-auto w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mb-6 animate-pulse-success">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path class="success-checkmark" stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                
                <h2 class="text-2xl font-bold text-gray-900 mb-3">Email Verified!</h2>
                <p class="text-gray-600 mb-6">Your email has been successfully verified.</p>
                
                <button
                    onclick="nextPage()"
                    class="bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-green-200"
                >
                    Continue
                </button>
            </div>
        </div>
    </div>

    <script>
        // Application state
        let timerInterval;
        let timeLeft = 120; // 2 minutes in seconds
        const CORRECT_OTP = "123456"; // Simulated correct OTP
        const email = @json($email);
        const userType = @json($user_type);
        const pageCode = @json($page_code);

        // DOM elements
        const otpForm = document.getElementById('otpForm');
        const otpInput = document.getElementById('otpInput');
        const otpError = document.getElementById('otpError');
        const verifyBtn = document.getElementById('verifyBtn');
        const verifyBtnText = document.getElementById('verifyBtnText');
        const verifyLoader = document.getElementById('verifyLoader');
        const resendBtn = document.getElementById('resendBtn');
        const resendText = document.getElementById('resendText');
        const countdown = document.getElementById('countdown');
        const verificationCard = document.getElementById('verificationCard');
        const successCard = document.getElementById('successCard');

        // Initialize the application
        function init() {
            startResendTimer();
            setupEventListeners();
            updateEmailDisplay();
            sendEmailVerification(email, userType);
        }

        async function sendEmailVerification(email, userType) {
            try {
                const response = await fetch('/api/send-verification-code', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        email: email,
                        user_type: userType
                    })
                });

                let data;
                try {
                    data = await response.json();
                } catch {
                    throw new Error('Invalid server response');
                }

                if (response.ok) {
                    console.log('Success:', data.message);
                } else {
                    console.error('Error:', data.message || 'Something went wrong');
                    alert(data.message || 'Something went wrong');
                    window.location.href = '{{ route($user_type . '.login') }}';
                }
            } catch (error) {
                console.error('Request failed:', error);
                alert('Unable to send verification email');
            }
        }

        // Setup event listeners
        function setupEventListeners() {
            // OTP form submission
            otpForm.addEventListener('submit', handleVerification);

            // OTP input validation
            otpInput.addEventListener('input', handleOTPInput);
            otpInput.addEventListener('keypress', handleKeyPress);

            // Resend button
            resendBtn.addEventListener('click', handleResend);
        }

        // Handle OTP input formatting and validation
        function handleOTPInput(e) {
            // Only allow numbers
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
            
            // Hide error when user starts typing
            if (otpError.classList.contains('block')) {
                hideError();
            }

            // Auto-submit when 6 digits are entered
            if (e.target.value.length === 6) {
                setTimeout(() => {
                    handleVerification(e);
                }, 300);
            }
        }

        // Handle key press events
        function handleKeyPress(e) {
            // Allow only numbers, backspace, delete, and navigation keys
            if (!/[0-9]/.test(e.key) && !['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab'].includes(e.key)) {
                e.preventDefault();
            }
        }

        async function handleVerification(e) {
            e.preventDefault();

            const otpValue = otpInput.value.trim();

            // Validate OTP length
            if (otpValue.length !== 6) {
                showError('Please enter a 6-digit verification code.');
                return;
            }

            // Show loading state
            showLoading();

            try {
                const response = await fetch('../api/verify-otp', { // Change to your Laravel route
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        email: email,
                        user_type: userType,
                        otp: otpValue
                    })
                });

                const data = await response.json();
                console.log(data);

                if (data.status === 'success') {
                    showSuccess();
                } else {
                    showError(data.message || 'Invalid verification code. Please try again.');
                    hideLoading();
                }

            } catch (error) {
                console.error('Error verifying OTP:', error);
                showError('Something went wrong. Please try again.');
                hideLoading();
            }
        }


        // Show loading state
        function showLoading() {
            verifyBtn.disabled = true;
            verifyBtnText.textContent = 'Verifying...';
            verifyLoader.classList.remove('hidden');
            otpInput.disabled = true;
        }

        // Hide loading state
        function hideLoading() {
            verifyBtn.disabled = false;
            verifyBtnText.textContent = 'Verify Email';
            verifyLoader.classList.add('hidden');
            otpInput.disabled = false;
            otpInput.focus();
        }

        // Show error message
        function showError(message) {
            otpError.textContent = message;
            otpError.classList.remove('hidden');
            otpInput.classList.add('border-red-500', 'focus:border-red-500');
            otpInput.classList.remove('border-gray-300', 'focus:border-blue-500');
            
            // Shake animation
            otpInput.style.animation = 'none';
            setTimeout(() => {
                otpInput.style.animation = 'shake 0.5s ease-in-out';
            }, 10);
        }

        // Hide error message
        function hideError() {
            otpError.classList.add('hidden');
            otpInput.classList.remove('border-red-500', 'focus:border-red-500');
            otpInput.classList.add('border-gray-300', 'focus:border-blue-500');
        }

        // Show success state
        function showSuccess() {
            verificationCard.style.display = 'none';
            successCard.classList.remove('hidden');
        }

        // Start the resend timer
        function startResendTimer() {
            timeLeft = 120; // Reset to 2 minutes
            resendBtn.disabled = true;
            
            updateTimerDisplay();
            
            timerInterval = setInterval(() => {
                timeLeft--;
                updateTimerDisplay();
                
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    enableResendButton();
                }
            }, 1000);
        }

        // Update timer display
        function updateTimerDisplay() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            const formattedTime = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            countdown.textContent = formattedTime;
        }

        // Enable resend button
        function enableResendButton() {
            resendBtn.disabled = false;
            resendText.innerHTML = 'Resend code';
            resendBtn.classList.add('hover:text-blue-800');
        }

        // Handle resend button click
        function handleResend() {
            // Simulate sending new OTP
            otpInput.value = '';
            hideError();
            startResendTimer();
            sendEmailVerification(email, userType);
            
            // Show feedback
            const originalText = resendText.innerHTML;
            resendText.innerHTML = 'Code sent! Check your email';

            setTimeout(() => {
                if (timeLeft > 0) {
                    resendText.innerHTML = `Resend code in <span id="countdown">${countdown.textContent}</span>`;
                }
            }, 2000);
        }

        function nextPage() {
            if (pageCode == 'R') {
                
                const otpValue = otpInput.value.trim();
                const secret_code = pageCode + "_" + otpValue;

                window.location.href = '{{ route("common.resetPassword") }}?secret_code='+secret_code+'&email='+email+'&user_type='+userType;
            }else{
                window.location.href = '{{ route($user_type . '.login') }}';
            }
        }


        // Update email display (you can modify this to show actual email)
        function updateEmailDisplay() {
            // In a real application, this would come from user data or URL parameters
            const email = @json($email); // safer for strings
            document.getElementById('emailDisplay').textContent = email;
        }

        // Add shake animation keyframes
        const style = document.createElement('style');
        style.textContent = `
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-5px); }
                75% { transform: translateX(5px); }
            }
        `;
        document.head.appendChild(style);

        // Initialize the application when DOM is loaded
        document.addEventListener('DOMContentLoaded', init);

        // Focus on OTP input when page loads
        window.addEventListener('load', () => {
            otpInput.focus();
        });
    </script>
</body>
</html>
