<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OEMS - Online Examination Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @keyframes fadeInUp {
            from { opacity:0; transform:translateY(40px); }
            to { opacity:1; transform:translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 1s ease forwards;
        }

        @keyframes float {
            0%,100% { transform: translateY(0); }
            50% { transform: translateY(-12px); }
        }
        .animate-float {
            animation: float 4s ease-in-out infinite;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">

<!-- ================= NAVBAR ================= -->
<header class="fixed w-full bg-white shadow z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
        <h1 class="text-xl font-bold text-indigo-600">
            OEMS
        </h1>

        <div class="flex items-center gap-6 text-sm font-medium">
            <a href="#features" class="hover:text-indigo-600 transition">Features</a>
            <a href="#about" class="hover:text-indigo-600 transition">About</a>
            <a href="{{ route('admin.login') }}"
               class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                Login
            </a>
        </div>
    </div>
</header>

{{-- <div class="h-20"></div> --}}

<!-- ================= HERO ================= -->
<section class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white">

    <div class="max-w-7xl mx-auto px-6 py-24 grid md:grid-cols-2 gap-12 items-center">

        <div class="space-y-6 animate-fade-in-up">
            <h1 class="text-4xl md:text-5xl font-bold leading-tight">
                Smart Online Examination & School Management System
            </h1>

            <p class="text-lg text-indigo-100">
                Conduct exams, manage students, monitor performance,
                and secure your institution digitally.
            </p>

            <div class="flex gap-4">
                <a href="#"
                   class="px-6 py-3 bg-white text-indigo-600 font-semibold rounded-lg shadow hover:shadow-lg transition">
                    Get Started
                </a>

                <a href="#features"
                   class="px-6 py-3 border border-white rounded-lg hover:bg-white hover:text-indigo-600 transition">
                    Explore Features
                </a>
            </div>
        </div>

        <div class="animate-float">
            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135755.png"
                 class="w-full max-w-md mx-auto">
        </div>

    </div>

</section>

<!-- ================= FEATURES ================= -->
<section id="features" class="py-20 bg-gray-50">

    <div class="max-w-7xl mx-auto px-6 text-center">

        <h2 class="text-3xl font-bold mb-4">
            Powerful Features
        </h2>

        <p class="text-gray-500 mb-12">
            Everything you need to manage exams efficiently.
        </p>

        <div class="grid md:grid-cols-3 gap-8">

            <div class="bg-white p-8 rounded-xl shadow hover:shadow-xl transition transform hover:-translate-y-2">
                <h3 class="text-xl font-semibold mb-3">
                    Online Exam System
                </h3>
                <p class="text-gray-500">
                    Create, schedule, auto-evaluate and publish results instantly.
                </p>
            </div>

            <div class="bg-white p-8 rounded-xl shadow hover:shadow-xl transition transform hover:-translate-y-2">
                <h3 class="text-xl font-semibold mb-3">
                    Student Management
                </h3>
                <p class="text-gray-500">
                    Manage attendance, performance and records.
                </p>
            </div>

            <div class="bg-white p-8 rounded-xl shadow hover:shadow-xl transition transform hover:-translate-y-2">
                <h3 class="text-xl font-semibold mb-3">
                    Advanced Security
                </h3>
                <p class="text-gray-500">
                    Multi-device login tracking & remote logout control.
                </p>
            </div>

        </div>

    </div>

</section>

<!-- ================= STATS ================= -->
<section class="py-16 bg-indigo-600 text-white">

    <div class="max-w-6xl mx-auto grid md:grid-cols-4 gap-8 text-center">

        <div>
            <h3 class="text-4xl font-bold counter" data-target="5000">0</h3>
            <p class="mt-2 text-indigo-200">Students</p>
        </div>

        <div>
            <h3 class="text-4xl font-bold counter" data-target="300">0</h3>
            <p class="mt-2 text-indigo-200">Exams Conducted</p>
        </div>

        <div>
            <h3 class="text-4xl font-bold counter" data-target="50">0</h3>
            <p class="mt-2 text-indigo-200">Institutions</p>
        </div>

        <div>
            <h3 class="text-4xl font-bold counter" data-target="99">0</h3>
            <p class="mt-2 text-indigo-200">% Success Rate</p>
        </div>

    </div>

</section>

<!-- ================= CTA ================= -->
<section class="py-20 bg-gray-900 text-white text-center">

    <h2 class="text-3xl font-bold mb-4">
        Ready to Transform Your Examination System?
    </h2>

    <p class="text-gray-400 mb-6">
        Start managing exams smarter today.
    </p>

    <a href="{{ route('admin.login') }}"
       class="px-8 py-3 bg-indigo-600 rounded-lg font-semibold hover:bg-indigo-700 transition">
        Login Now
    </a>

</section>

<!-- ================= FOOTER ================= -->
<footer class="bg-gray-100 py-8 text-center text-sm text-gray-500">
    © {{ date('Y') }} OEMS. All rights reserved.
</footer>

<!-- ================= COUNTER SCRIPT ================= -->
<script>
document.querySelectorAll('.counter').forEach(counter => {
    const target = +counter.getAttribute('data-target');
    const update = () => {
        const current = +counter.innerText;
        const increment = target / 100;

        if(current < target){
            counter.innerText = Math.ceil(current + increment);
            setTimeout(update, 20);
        } else {
            counter.innerText = target;
        }
    };
    update();
});
</script>

</body>
</html>
