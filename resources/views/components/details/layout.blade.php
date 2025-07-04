@props([
    'title' => 'Detail View',
    'type' => 'applicant',
    'id' => '1'
])

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ $title }}</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body class="bg-gray-50">
  <div class="container mx-auto py-6 px-4 max-w-8xl">
    {{ $breadcrumb ?? '' }}
    {{ $header ?? '' }}
    
    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Left Column - Main Information -->
      <div class="lg:col-span-2 space-y-6">
        {{ $mainContent ?? '' }}
      </div>
      
      <!-- Right Column - Status, Documents, Activity -->
      <div class="space-y-6">
        {{ $sideContent ?? '' }}
      </div>
    </div>
  </div>
  
  {{ $modals ?? '' }}
</body>
</html>