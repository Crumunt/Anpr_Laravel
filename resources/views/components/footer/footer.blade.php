@props([ 
    'theme' => 'light'
])
<footer class="{{ $theme === 'dark' ? 'text-gray-400' : 'text-gray-600' }} border-t {{ $theme === 'dark' ? 'border-gray-800' : 'border-gray-200' }}">
    <div class="container mx-auto px-4 py-6">
        <p class="text-sm text-center">
            &copy; copyright 2025, University of Business
        </p>
    </div>
</footer>