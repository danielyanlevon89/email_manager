<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Outgoing Emails') }}
            </h2>
        </div>
    </x-slot>

    <x-splade-script>
        window.quitIntervals(100000)
    </x-splade-script>

    <x-splade-rehydrate poll="20000">
        <div class="py-3 px-3">
            <div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 py-16  bg-white border-b border-gray-200">
                        <x-splade-table :for="$outgoing_emails">
                            <x-slot:empty-state>
                                <p class="text-center">{{ __('Outgoing Emails is empty') }}</p>
                            </x-slot>
                        </x-splade-table>
                    </div>
                </div>
            </div>
        </div>
    </x-splade-rehydrate>
</x-app-layout>



