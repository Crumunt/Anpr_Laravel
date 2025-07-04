@props(['activeTab'])
<div id="form-tabs" role="tablist" aria-orientation="horizontal" class="h-9 items-center justify-center rounded-lg bg-muted p-1 text-muted-foreground grid grid-cols-4 mb-8 transition-all duration-300" tabindex="0" style="outline: none;">
    <x-forms.tab-button
        :active="$activeTab === 'personal'" 
        icon="user"
        label="Personal Info"
        data-step="1"
        id="tab-personal"
    />
    <x-forms.tab-button
        :active="$activeTab === 'vehicle'" 
        icon="car"
        label="Vehicle Info"
        data-step="2"
        id="tab-vehicle"
    />
    <x-forms.tab-button
        :active="$activeTab === 'documents'" 
        icon="file-check"
        label="Documents"
       data-step="3"
        id="tab-documents"
    />
    <x-forms.tab-button
        :active="$activeTab === 'review'" 
        icon="arrow-right"
        label="Review"
        data-step="4"
        id="tab-review"
    />
</div>