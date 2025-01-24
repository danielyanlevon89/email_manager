<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <a href="{{ URL::previous() }}">{{ __('Back') }}</a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Template') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-splade-form :default="$template" method="PUT" :action="route('templates.update',$template->id)" class="mx-auto p-4 bg-white">
                <x-splade-input name="name"  :label="__('Name')" />

                <x-splade-wysiwyg name="text" :label="__('Text')" />

                <x-splade-submit class="mt-4"  :label="__('Save')"/>
            </x-splade-form>
        </div>
    </div>
</x-app-layout>
