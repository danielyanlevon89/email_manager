<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <a href="{{ URL::previous() }}">{{ __('Back') }}</a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('New Template') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-splade-form :action="route('templates.store')" class="mx-auto p-4 bg-white">
                <x-splade-input name="name" :label="__('Name')" />

                <x-splade-wysiwyg name="text" :label="__('Text')" />

                <p class="text-right mt-2">{{__('Keywords: ')}} {url} , {sender_name} , {sender_email} , {recipient_name} , {recipient_email} , {email_date}</p>

                <x-splade-submit class="mt-4" :label="__('Save')"/>
            </x-splade-form>
        </div>
    </div>
</x-app-layout>
