@props(['documents' => []])

<div>
    <h4 class="font-medium text-green-700 mb-2">Documents</h4>
    <p class="text-sm">{{ count($documents) }} document(s) uploaded</p>
    
    @if(count($documents) > 0)
        <ul class="list-disc list-inside text-sm ml-2">
            @foreach($documents as $document)
                <li>{{ $document }}</li>
            @endforeach
        </ul>
    @else
        <p class="text-sm text-red-500">No documents uploaded</p>
    @endif
</div>