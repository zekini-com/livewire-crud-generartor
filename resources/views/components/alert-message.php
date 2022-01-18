
<div x-data="{ shown: false, message: ''}"
    x-init="@this.on('showAlert', (msg) => {
        shown = true; 
        message = msg;
        setTimeout(() => { 
            shown = false 
            }, 3000); 
            })"
    x-show.transition.opacity.out.duration.1500ms="shown"
    style="display: none;"
    class="rounded-r-md bg-green-100 p-4 border-l-4 border-green-400 mb-3 absolute right-0">
        <span class="text-gray-700 text-sm" x-text="message"></span>
</div>

