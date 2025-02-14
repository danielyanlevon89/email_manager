<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <a href="{{ URL::previous() }}">{{ __('Back') }}</a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Link') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-splade-form :default="$link" method="PUT" :action="route('links.update',$link->id)" class="mx-auto p-4 bg-white">
                <x-splade-input name="url"  :label="__('Url')" />

                <x-splade-checkbox name="is_active" false-value="0" class="mt-2" :label="__('Is Active')" />

                <x-splade-submit class="mt-4"  :label="__('Save')"/>
            </x-splade-form>
        </div>
    </div>
</x-app-layout>
