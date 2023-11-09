<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Category') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-splade-form :default="$category" method="PUT" :action="route('categories.update',$category->id)" class="max-w-md mx-auto p-4 bg-white">
                <x-splade-input name="name"  :label="__('Name')" />

                <x-splade-input name="slug" :label="__('Slug')" />

                <x-splade-submit class="mt-4"/>
            </x-splade-form>
        </div>
    </div>
</x-app-layout>
