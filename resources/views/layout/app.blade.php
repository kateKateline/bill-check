<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <title>HealthBillGuard - AI Hospital Transparency</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col">

    @include('partials.navbar')

    <main class="flex-1 overflow-x-hidden">
        @yield('content')
    </main>

    @include('partials.footer')

    <script>
        function simulateScan() {
            const uploadBox = document.getElementById('uploadBox');
            const loadingState = document.getElementById('loadingState');
            const resultState = document.getElementById('resultState');

            uploadBox.classList.add('opacity-50', 'pointer-events-none');
            loadingState.classList.remove('hidden');
            
            // Scroll smooth ke loading
            loadingState.scrollIntoView({ behavior: 'smooth', block: 'center' });

            setTimeout(() => {
                uploadBox.classList.add('hidden');
                loadingState.classList.add('hidden');
                resultState.classList.remove('hidden');
                resultState.scrollIntoView({ behavior: 'smooth' });
            }, 3000);
        }

        function toggleDetail(id) {
            const el = document.getElementById(id);
            el.classList.toggle('hidden');
        }
    </script>
</body>
</html>