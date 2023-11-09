<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Post') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-splade-form :default="$post" method="PUT" :action="route('posts.update',$post->id)" class="max-w-md mx-auto p-4 bg-white">

                <x-splade-select name="category_id" :options="$categories" :label="__('Category')" />

                <x-splade-input name="title" :label="__('Title')" />

                <x-splade-input name="slug" :label="__('Slug')" />

                <x-splade-textarea name="description" autosize :label="__('Description')" />

                <x-splade-submit class="mt-4"/>
            </x-splade-form>
        </div>
    </div>
</x-app-layout>
