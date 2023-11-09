<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Posts') }}
            </h2>
            <Link href="{{route('posts.create')}}"
                  class="px-4 py-2 bg-indigo-400 hover:bg-indigo-600 text-white rounded-md">
            {{ __('New Post') }}
            </Link>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-splade-table :for="$posts">
                        <x-slot:empty-state>
                            <p class="text-center">{{ __('Posts is empty') }}</p>
                            </x-slot>

                            @cell('action',$post)
                            <Link href="{{ route('posts.edit',$post->id) }}"
                                  class="text-green-600 hover:text-gray-400 font-semibold">{{ __('Edit') }}
                            </Link>
                            <Link
                                    confirm="{{ __('Delete Post') }}"
                                    confirm-text="{{ __('Are you sure?') }}"
                                    confirm-button="{{ __('Yes') }}"
                                    cancel-button="{{ __('Canceel') }}"
                                    href="{{ route('posts.destroy',$post->id) }}" method="DELETE" preserve-scroll
                                    class="text-red-600 hover:text-red-400 ml-3 font-semibold">
                            {{ __('Delete') }}
                            </Link>
                            @endcell

                    </x-splade-table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
