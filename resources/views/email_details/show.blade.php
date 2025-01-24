<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <a href="{{ URL::previous() }}">{{ __('Back') }}</a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $email->subject }}
            </h2>
        </div>
    </x-slot>



    <x-splade-data  default="{{ $email }}">
    <div class="container mx-auto py-3 px-5">
        <div class="max-w-7xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 sm:p-6">
                <div class="flex items-center justify-between mb-5">
                    <div v-show="data.subject">
                        <p class="text-sm text-gray-600" v-show="data.from">
                            {{ __('Subject') }}:
                            <b class="text-gray-600 font-bold"  v-text="data.subject"></b>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600" v-show="data.from">{{ __('From') }}:  <b v-text="data.from" /></p>
                        <p class="text-sm text-gray-600" v-show="data.to">{{ __('To') }}:  <b v-text="data.to" /></p>
                        <p class="text-sm text-gray-600" v-show="data.cc">{{ __('Cc') }}:  <b v-text="data.cc" /></p>
                        <p class="text-sm text-gray-600" v-show="data.email_date">{{ __('Email Date') }}:  <b v-text="data.email_date" /></p>
                    </div>
                </div>

                <div class="py-6" v-html="data.body"></div>

                <div v-if="data.has_attachment" class="text-left">
                    <x-splade-toggle >

                        <button v-else @click.prevent="toggle" v-show="!toggled">{{ __('Show attached files') }}</button>
                        <x-splade-transition show="toggled">
                            <div v-if="toggled">
                                @include('email_details.attachment', ['images' => $images,'files' => $files])
                                <button @click.prevent="setToggle(false)">{{ __('Hide attached files') }}</button>
                            </div>
                        </x-splade-transition>
                    </x-splade-toggle>
                </div>

                <p class="text-sm text-gray-600 float-right" v-show="data.seen_at">
                    {{ __('Seen') }}:
                    <b class="text-gray-600 font-bold"  v-text="data.seen_at"></b>
                </p>

            </div>
        </div>
    </div>
    </x-splade-data>


{{--    <Counter v-slot="{ count, increase,email }" :email="@js($email)">--}}


{{--        <button @click="increase" v-text="count" />--}}

{{--        <div class="container mx-auto py-3 px-5">--}}
{{--            <div class="max-w-7xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">--}}
{{--                <div class="p-4 sm:p-6">--}}
{{--                    <div class="flex items-center justify-between mb-5">--}}
{{--                        <div v-show="email.subject">--}}
{{--                            <p class="text-sm text-gray-600" v-show="email.from">--}}
{{--                                {{ __('Subject') }}:--}}
{{--                                <b class="text-gray-600 font-bold"  v-text="email.subject"></b>--}}
{{--                            </p>--}}
{{--                        </div>--}}
{{--                        <div>--}}
{{--                            <p class="text-sm text-gray-600" v-show="email.from">{{ __('From') }}:  <b v-text="email.from" /></p>--}}
{{--                            <p class="text-sm text-gray-600" v-show="email.to">{{ __('To') }}:  <b v-text="email.to" /></p>--}}
{{--                            <p class="text-sm text-gray-600" v-show="email.cc">{{ __('Cc') }}:  <b v-text="email.cc" /></p>--}}
{{--                            <p class="text-sm text-gray-600" v-show="email.email_date">{{ __('Email Date') }}:  <b v-text="email.email_date" /></p>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <div v-html="email.body"></div>--}}


{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </Counter>--}}



{{--    <div id="accordion-example">--}}
{{--        <h2 id="accordion-example-heading-1">--}}
{{--            <button type="button" class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-b-0 border-gray-200 rounded-t-xl focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800" aria-expanded="true" aria-controls="accordion-example-body-1">--}}
{{--                <span>What is Flowbite?</span>--}}
{{--                <svg data-accordion-icon class="w-6 h-6 rotate-180 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>--}}
{{--            </button>--}}
{{--        </h2>--}}
{{--        <div id="accordion-example-body-1" class="hidden" aria-labelledby="accordion-example-heading-1">--}}
{{--            <div class="p-5 border border-b-0 border-gray-200 dark:border-gray-700 dark:bg-gray-900">--}}
{{--                <p class="mb-2 text-gray-500 dark:text-gray-400">Flowbite is an open-source library of interactive components built on top of Tailwind CSS including buttons, dropdowns, modals, navbars, and more.</p>--}}
{{--                <p class="text-gray-500 dark:text-gray-400">Check out this guide to learn how to <a href="/docs/getting-started/introduction/" class="text-blue-600 dark:text-blue-500 hover:underline">get started</a> and start developing websites even faster with components on top of Tailwind CSS.</p>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <h2 id="accordion-example-heading-2">--}}
{{--            <button type="button" class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-b-0 border-gray-200 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800" aria-expanded="false" aria-controls="accordion-example-body-2">--}}
{{--                <span>Is there a Figma file available?</span>--}}
{{--                <svg data-accordion-icon class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>--}}
{{--            </button>--}}
{{--        </h2>--}}
{{--        <div id="accordion-example-body-2" class="hidden" aria-labelledby="accordion-example-heading-2">--}}
{{--            <div class="p-5 border border-b-0 border-gray-200 dark:border-gray-700">--}}
{{--                <p class="mb-2 text-gray-500 dark:text-gray-400">Flowbite is first conceptualized and designed using the Figma software so everything you see in the library has a design equivalent in our Figma file.</p>--}}
{{--                <p class="text-gray-500 dark:text-gray-400">Check out the <a href="https://flowbite.com/figma/" class="text-blue-600 dark:text-blue-500 hover:underline">Figma design system</a> based on the utility classes from Tailwind CSS and components from Flowbite.</p>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <h2 id="accordion-example-heading-3">--}}
{{--            <button type="button" class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-gray-200 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800" aria-expanded="false" aria-controls="accordion-example-body-3">--}}
{{--                <span>What are the differences between Flowbite and Tailwind UI?</span>--}}
{{--                <svg data-accordion-icon class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>--}}
{{--            </button>--}}
{{--        </h2>--}}
{{--        <div id="accordion-example-body-3" class="hidden" aria-labelledby="accordion-example-heading-3">--}}
{{--            <div class="p-5 border border-t-0 border-gray-200 dark:border-gray-700">--}}
{{--                <p class="mb-2 text-gray-500 dark:text-gray-400">The main difference is that the core components from Flowbite are open source under the MIT license, whereas Tailwind UI is a paid product. Another difference is that Flowbite relies on smaller and standalone components, whereas Tailwind UI offers sections of pages.</p>--}}
{{--                <p class="mb-2 text-gray-500 dark:text-gray-400">However, we actually recommend using both Flowbite, Flowbite Pro, and even Tailwind UI as there is no technical reason stopping you from using the best of two worlds.</p>--}}
{{--                <p class="mb-2 text-gray-500 dark:text-gray-400">Learn more about these technologies:</p>--}}
{{--                <ul class="ps-5 text-gray-500 list-disc dark:text-gray-400">--}}
{{--                    <li><a href="https://flowbite.com/pro/" class="text-blue-600 dark:text-blue-500 hover:underline">Flowbite Pro</a></li>--}}
{{--                    <li><a href="https://tailwindui.com/" rel="nofollow" class="text-blue-600 dark:text-blue-500 hover:underline">Tailwind UI</a></li>--}}
{{--                </ul>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}


</x-app-layout>



