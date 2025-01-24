<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Templates') }}
            </h2>
            <Link href="{{route('templates.create')}}"
                  class="px-4 py-2 bg-indigo-400 hover:bg-indigo-600 text-white rounded-md">
            {{ __('Add New Template') }}
            </Link>
        </div>
    </x-slot>

    <div class="py-3 px-3">
        <div>
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 py-16  bg-white border-b border-gray-200">
                    <x-splade-table :for="$templates">
                        <x-slot:empty-state>
                            <p class="text-center">{{ __('Templates is empty') }}</p>
                            </x-slot>

                            @cell('action',$template)
                            <div class="text-right">
                                <Link href="{{ route('templates.edit',$template->id) }}"
                                      class="px-3 py-2 bg-green-400 hover:bg-green-600 text-white rounded-md font-semibold">{{ __('Edit') }}
                                </Link>
                                <Link
                                        confirm="{{ __('Delete Template') }}"
                                        confirm-text="{{ __('Are you sure?') }}"
                                        confirm-button="{{ __('Yes') }}"
                                        cancel-button="{{ __('Canceel') }}"
                                        href="{{ route('templates.destroy',$template->id) }}" method="DELETE"
                                        preserve-scroll
                                        class="px-3 py-2 bg-red-400 hover:bg-red-600 text-white rounded-md ml-3 font-semibold">
                                {{ __('Delete') }}
                                </Link>
                            </div>
                            @endcell
                    </x-splade-table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
