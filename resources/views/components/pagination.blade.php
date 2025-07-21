@props(['pagination'])
<div>
    {{ $pagination->onEachSide(5)->links() }}
</div>