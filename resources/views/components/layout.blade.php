<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>{{ $title ?? 'CLSU RFID Gate Pass Application' }}</title>
    {{ $head ?? '' }}
</head>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<body>
    <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <x-forms.page-header />

            <div class="w-full max-w-4xl mx-auto bg-white rounded-xl shadow-md overflow-hidden transition-all duration-500 hover:shadow-lg">
                <x-forms.form-header />
                <div class="p-6">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</body>

</html>