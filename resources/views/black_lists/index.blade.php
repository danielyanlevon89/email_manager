<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Black List') }}
            </h2>
            <div>
                <import :import-type='"black_lists"' :import-title='"Import Black List"'  />
                <Link href="{{route('black_lists.create')}}"
                      class="px-4 py-2 bg-indigo-400 hover:bg-indigo-600 text-white rounded-md">
                {{ __('Add New Black List Item') }}
                </Link>
            </div>
        </div>
    </x-slot>

    <div class="py-3 px-3">
        <div>
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 py-16  bg-white border-b border-gray-200">
                    <x-splade-table :for="$blackLists">
                        <x-slot:empty-state>
                            <p class="text-center">{{ __('Black Lists is empty') }}</p>
                            </x-slot>

                            @cell('action',$blackList)
                            <div class="text-right">
                                <Link href="{{ route('black_lists.edit',$blackList->id) }}"
                                      class="px-3 py-2 bg-sky-200 hover:bg-sky-300 text-blue rounded-md font-semibold">{{ __('Edit') }}
                                </Link>
                                <Link
                                        confirm="{{ __('Delete Black List Item') }}"
                                        confirm-text="{{ __('Are you sure?') }}"
                                        confirm-button="{{ __('Yes') }}"
                                        cancel-button="{{ __('Canceel') }}"
                                        href="{{ route('black_lists.destroy',$blackList->id) }}" method="DELETE"
                                        preserve-scroll
                                        class="px-2 py-2 bg-red-400 hover:bg-red-600 text-white rounded-md ml-3 font-semibold">

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 inline-block">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>

                                </Link>
                            </div>
                            @endcell
                    </x-splade-table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
