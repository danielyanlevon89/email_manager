<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <a href="{{ URL::previous() }}">{{ __('Back') }}</a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Common Settings') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-splade-form :default="$commonSetting" method="POST" :action="route('common_settings.store')" class="mx-auto p-4 bg-white">
                <x-splade-input name="auto_reply_delay" type="number"  :label="__('Auto reply Delay (Min)')" />

                <x-splade-submit class="mt-4"  :label="__('Save')" />
            </x-splade-form>
        </div>
    </div>
</x-app-layout>
