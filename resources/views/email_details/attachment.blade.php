
<div class="container mx-auto p-4">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach ($images as $image)
        <div class="grid gap-4">
            <div>
                <img
                    class="h-auto max-w-full rounded-lg"
                    src="{{ asset('storage/app/'.$image) }}"
                    alt=""
                />
            </div>
        </div>
        @endforeach
            @foreach ($files as $file)
                <div class="grid gap-4">
                    <a target="_blank" href="{{ asset('storage/app/'.$file )}}"
                          class="px-4 py-2 bg-indigo-400 hover:bg-indigo-600 text-white rounded-md">
                        {{ __('Preview') }}
                    </a>
                </div>
            @endforeach
    </div>
</div>
