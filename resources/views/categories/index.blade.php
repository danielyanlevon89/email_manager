<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Categories') }}
            </h2>
            <Link href="{{route('categories.create')}}"
                  class="px-4 py-2 bg-indigo-400 hover:bg-indigo-600 text-white rounded-md">
            {{ __('New Category') }}
            </Link>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-splade-table :for="$categories">
                        <x-slot:empty-state>
                            <p class="text-center">{{ __('Categories is empty') }}</p>
                            </x-slot>

                            @cell('action',$category)
                            <Link href="{{ route('categories.edit',$category->id) }}"
                                  class="text-green-600 hover:text-gray-400 font-semibold">{{ __('Edit') }}
                            </Link>
                            <Link
                                    confirm="{{ __('Delete Category') }}"
                                    confirm-text="{{ __('Are you sure?') }}"
                                    confirm-button="{{ __('Yes') }}"
                                    cancel-button="{{ __('Canceel') }}"
                                    href="{{ route('categories.destroy',$category->id) }}" method="DELETE"
                                    preserve-scroll
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
