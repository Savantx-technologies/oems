<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'OEMS') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Fira+Code:wght@400;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        html {
            font-size: 15px;
        }
        body {
            font-family: 'Inter', 'Fira Code', monospace, sans-serif;
            background-image: radial-gradient(circle at 20% 30%, #e0e7ff 0, transparent 70%), radial-gradient(circle at 80% 90%, #ede9fe 10%, transparent 75%);
            min-height: 100vh;
        }
        .animate-blob {
            animation: blob 8s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(20px, -40px) scale(1.17); }
            66% { transform: translate(-16px, 22px) scale(0.92); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .reveal {
            opacity: 0;
            transform: translateY(30px) scale(0.98);
            transition: opacity 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94), transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            will-change: opacity, transform;
        }
        .reveal.is-visible {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
        .glass {
            background: rgba(255,255,255,0.73);
            box-shadow: 0 8px 32px 0 rgba(31,38,135,0.14);
            backdrop-filter: blur(7px);
        }
        .gradient-text {
            background: linear-gradient(90deg, #6366f1 0%, #a21caf 75%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .cta-btn {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .cta-btn:hover {
            transform: translateY(-1px) scale(1.01) rotate(-1deg);
            box-shadow: 0 5px 18px 0 rgba(99,102,241,0.12), 0 1px 2px 0 rgba(99,102,241,0.08);
        }
        /* Reduce bg accent opacity and blur a bit for subtlety */
        .bg-accent-blur {
            opacity: 0.15 !important;
            filter: blur(80px) !important;
        }
    </style>
</head>
<body class="antialiased bg-gray-50">
    <!-- Navbar -->
    <nav class="glass border-b border-gray-100 fixed w-full z-50 top-0 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-14 items-center">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold shadow-md">
                        <i class="bi bi-mortarboard-fill text-base"></i>
                    </div>
                    <span class="font-bold text-lg text-gray-800 tracking-tight gradient-text">OEMS</span>
                </div>
                <div class="hidden md:flex items-center space-x-6">
                    <a href="#features" class="text-xs font-medium text-gray-600 hover:text-indigo-600 transition">Features</a>
                    <a href="#why" class="text-xs font-medium text-gray-600 hover:text-indigo-600 transition">Why OEMS</a>
                    <a href="#contact" class="text-xs font-medium text-gray-600 hover:text-indigo-600 transition">Contact</a>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('student.login') }}" class="text-xs font-semibold text-indigo-600 border border-indigo-100 hover:bg-indigo-50 transition px-3 py-1.5 rounded-lg">Student Login</a>
                    <a href="{{ route('admin.login') }}" class="text-xs font-semibold bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-1.5 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition cta-btn shadow-md">Admin Login</a>
                </div>
            </div>
        </div>
    </nav>
    <!-- Hero Section -->
    <div class="relative pt-24 pb-16 lg:pt-36 lg:pb-28 overflow-hidden">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <span class="reveal inline-block py-1 px-4 rounded-full bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-700 text-[0.72rem] font-semibold uppercase tracking-wider mb-4 border border-indigo-200 shadow" style="font-weight:500;">
                🚀 Next Generation Assessment
            </span>
            <h1 class="reveal text-3xl md:text-5xl font-bold text-gray-900 mb-4 leading-tight gradient-text" style="transition-delay: 100ms;font-weight:800;">
                Secure. Intelligent.<br />
                <span class="gradient-text">Examination Platform</span>
            </h1>
            <p class="reveal mt-3 max-w-xl mx-auto text-base text-gray-600 mb-9 font-normal" style="transition-delay: 200ms;">
                Power your institution with a robust online exam system.<br class="hidden md:block" />
                <span class="text-indigo-900/90">Live proctoring, analytics, seamless experience.</span>
            </p>
            <div class="reveal flex flex-col sm:flex-row justify-center gap-2" style="transition-delay: 300ms;">
                <a href="{{ route('student.login') }}" class="cta-btn px-7 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold rounded-lg hover:from-indigo-700 hover:to-purple-700 transition flex items-center justify-center gap-2 drop-shadow text-base shadow-indigo-200/30">
                    <i class="bi bi-person-circle text-lg"></i>
                    Student Portal
                </a>
                <a href="{{ route('admin.login') }}" class="cta-btn px-7 py-2.5 bg-white text-indigo-700 border border-indigo-200 font-bold rounded-lg hover:bg-indigo-50 transition flex items-center justify-center text-base shadow">
                    <i class="bi bi-shield-lock text-lg"></i>
                    Admin Portal
                </a>
            </div>
        </div>
        <!-- Decorative blobs -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full z-0 pointer-events-none">
            <div class="absolute top-20 left-6 w-72 h-72 bg-purple-200 rounded-full mix-blend-multiply bg-accent-blur animate-blob"></div>
            <div class="absolute top-8 right-8 w-72 h-72 bg-indigo-200 rounded-full mix-blend-multiply bg-accent-blur animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-10 left-1/2 w-72 h-72 bg-pink-200 rounded-full mix-blend-multiply bg-accent-blur animate-blob animation-delay-4000"></div>
        </div>
        <svg class="absolute bottom-0 left-0 w-full pointer-events-none" height="60" fill="none" viewBox="0 0 1440 60" preserveAspectRatio="none">
            <path fill="#ede9fe" d="M0 39c102 20.5 207 29.5 315 8C423 9 552-17.5 662 1c110 20.5 208 48.5 353 11C1160-14.5 1317 27 1440 59V0H0z"/>
        </svg>
    </div>
    <!-- Features Section -->
    <div id="features" class="py-18 bg-white border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 reveal relative z-10">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <h2 class="text-xs text-indigo-600 font-bold tracking-widest uppercase">Key Features</h2>
                <p class="mt-1 text-2xl font-bold text-gray-900 gradient-text">Empower Smarter Exams</p>
                <p class="mt-3 text-base text-gray-600">Manage the entire examination journey with modern tools.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-7">
                <!-- Card 1 -->
                <div class="reveal bg-gradient-to-tr from-blue-50 to-indigo-50 rounded-2xl p-6 shadow border border-blue-100 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 group" style="transition-delay: 150ms;">
                    <div class="w-12 h-12 bg-gradient-to-tr from-blue-600/90 to-indigo-400/90 rounded-lg flex items-center justify-center text-white text-xl mb-5 group-hover:shadow-lg transition">
                        <i class="bi bi-camera-video"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2 group-hover:text-indigo-800 transition">AI-Powered Proctoring</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Real-time webcam, mic & screen supervision with smart alerts.
                    </p>
                </div>
                <!-- Card 2 -->
                <div class="reveal bg-gradient-to-tr from-indigo-50 to-purple-50 rounded-2xl p-6 shadow border border-indigo-100 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 group" style="transition-delay: 300ms;">
                    <div class="w-12 h-12 bg-gradient-to-tr from-indigo-600/90 to-purple-400/90 rounded-lg flex items-center justify-center text-white text-xl mb-5 group-hover:shadow-lg transition">
                        <i class="bi bi-collection"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2 group-hover:text-indigo-800 transition">Powerful Question Bank</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Organize & tag questions. Import/export and randomization made simple.
                    </p>
                </div>
                <!-- Card 3 -->
                <div class="reveal bg-gradient-to-tr from-green-50 to-emerald-50 rounded-2xl p-6 shadow border border-green-100 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 group" style="transition-delay: 450ms;">
                    <div class="w-12 h-12 bg-gradient-to-tr from-green-600/90 to-emerald-400/90 rounded-lg flex items-center justify-center text-white text-xl mb-5 group-hover:shadow-lg transition">
                        <i class="bi bi-lightning-charge"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2 group-hover:text-emerald-800 transition">Instant Results</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Auto-scoring and instant analytics. Fast, actionable feedback.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- Why Choose Us Section -->
    <div id="why" class="py-18 bg-gradient-to-b from-white to-indigo-50/55">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 reveal relative z-10">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <h2 class="text-xs text-purple-700 font-bold tracking-widest uppercase">Why OEMS</h2>
                <p class="mt-1 text-2xl font-bold text-gray-900 gradient-text">Designed for Modern Institutions</p>
                <p class="mt-3 text-base text-gray-600">Reliable, secure & future-ready: better exams for all.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-7 text-center">
                <!-- Item 1 -->
                <div class="reveal bg-white/95 border border-indigo-100 shadow rounded-xl px-7 py-8 flex flex-col items-center gap-3 hover:-translate-y-0.5 hover:shadow-lg transition" style="transition-delay: 150ms;">
                    <div class="w-14 h-14 mx-auto bg-gradient-to-tr from-indigo-600/90 to-purple-400/80 rounded-full flex items-center justify-center text-white text-2xl mb-3 shadow">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 mb-1 gradient-text">Unmatched Security</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        End-to-end encryption, permissions, and secure protocols.
                    </p>
                </div>
                <!-- Item 2 -->
                <div class="reveal bg-white/95 border border-indigo-100 shadow rounded-xl px-7 py-8 flex flex-col items-center gap-3 hover:-translate-y-0.5 hover:shadow-lg transition" style="transition-delay: 300ms;">
                    <div class="w-14 h-14 mx-auto bg-gradient-to-tr from-indigo-500/90 to-indigo-200/80 rounded-full flex items-center justify-center text-white text-2xl mb-3 shadow">
                        <i class="bi bi-hdd-stack"></i>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 mb-1 gradient-text">Scalable Infrastructure</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Serve 10 or 10,000. Cloud-based, rapid, reliable.
                    </p>
                </div>
                <!-- Item 3 -->
                <div class="reveal bg-white/95 border border-indigo-100 shadow rounded-xl px-7 py-8 flex flex-col items-center gap-3 hover:-translate-y-0.5 hover:shadow-lg transition" style="transition-delay: 450ms;">
                    <div class="w-14 h-14 mx-auto bg-gradient-to-tr from-purple-500/90 to-pink-400/80 rounded-full flex items-center justify-center text-white text-2xl mb-3 shadow">
                        <i class="bi bi-headset"></i>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 mb-1 gradient-text">24/7 Support</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Our expert team delivers practical, rapid support, always.
                    </p>
                </div>
            </div>
        </div>
        <svg class="absolute -bottom-1 left-0 w-full pointer-events-none" height="36" fill="none" viewBox="0 0 1440 36" preserveAspectRatio="none">
            <path fill="#fff" d="M0 14c102 10.5 207 15.5 315 5C423 6 552-4.5 662 4c110 10.5 208 25.5 353 7C1160-4.5 1317 12 1440 35V0H0z"/>
        </svg>
    </div>
    <!-- Call To Action Section -->
    <div id="contact" class="bg-indigo-50/55 relative">
        <div class="max-w-2xl mx-auto text-center py-12 px-4 sm:py-16 sm:px-8 lg:px-10 reveal relative z-10">
            <h2 class="text-2xl font-bold text-gray-900 mb-3 gradient-text">
                Ready to Transform Your Assessments?
            </h2>
            <p class="mt-2 text-base leading-7 text-gray-600 font-normal">
                Connect for a demo or discussion to see how OEMS can benefit you.
            </p>
            <a href="#" class="cta-btn mt-7 w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 rounded-lg shadow text-base font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 transition group">
                <span>Request a Demo</span>
                <i class="bi bi-calendar-check ms-2 text-lg group-hover:scale-110 transition"></i>
            </a>
        </div>
        <div class="absolute inset-0 z-0 pointer-events-none">
            <div class="absolute left-10 top-10 w-56 h-56 bg-indigo-200 rounded-full bg-accent-blur animate-blob"></div>
            <div class="absolute right-10 -bottom-4 w-48 h-48 bg-purple-200 rounded-full bg-accent-blur animate-blob animation-delay-2000"></div>
        </div>
    </div>
    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-12 border-t border-gray-800 reveal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <!-- Brand -->
                <div class="col-span-1 md:col-span-1">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold shadow">
                            <i class="bi bi-mortarboard-fill text-base"></i>
                        </div>
                        <span class="font-bold text-white text-lg tracking-tight">OEMS</span>
                    </div>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Empowering institutions with secure, intelligent, and scalable examination solutions.
                    </p>
                </div>

                <!-- Links -->
                <div>
                    <h3 class="text-white font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#features" class="hover:text-indigo-400 transition">Features</a></li>
                        <li><a href="#why" class="hover:text-indigo-400 transition">Why OEMS</a></li>
                        <li><a href="#contact" class="hover:text-indigo-400 transition">Contact</a></li>
                        <li><a href="{{ route('student.login') }}" class="hover:text-indigo-400 transition">Student Login</a></li>
                        <li><a href="{{ route('admin.login') }}" class="hover:text-indigo-400 transition">Admin Login</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="text-white font-semibold mb-4">Contact Us</h3>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start gap-3">
                            <i class="bi bi-geo-alt text-indigo-500 mt-0.5"></i>
                            <span>123 Education Lane,<br>Tech City, TC 90210</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="bi bi-envelope text-indigo-500"></i>
                            <a href="mailto:support@oems.com" class="hover:text-white transition">support@oems.com</a>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="bi bi-telephone text-indigo-500"></i>
                            <a href="tel:+1234567890" class="hover:text-white transition">+1 (234) 567-890</a>
                        </li>
                    </ul>
                </div>

                <!-- Social -->
                <div>
                    <h3 class="text-white font-semibold mb-4">Follow Us</h3>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition">
                            <i class="bi bi-twitter-x"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition">
                            <i class="bi bi-linkedin"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition">
                            <i class="bi bi-instagram"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer bottom, copyright centered on left side -->
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-center md:justify-start items-center gap-4 text-sm">
                <div class="text-center md:text-left w-full">
                    &copy; 2026 <span class="text-indigo-400 font-semibold">OEMS</span>. All rights reserved.
                </div>
                <div class="flex gap-6">
                    <a href="#" class="hover:text-white transition">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const revealElements = document.querySelectorAll('.reveal');
            const observer = new window.IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12 });
            revealElements.forEach(element => {
                observer.observe(element);
            });
        });
    </script>
</body>
</html>
