<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <a href="{{ URL::previous() }}">{{ __('Back') }}</a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Account') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-splade-form :default="$emailAccount" id="emailAccountForm" method="PUT" :action="route('email_accounts.update',$emailAccount->id)" class="mx-auto p-4 bg-white">

                <h1 class="text-center font-medium text-3xl">{{__('Account Name')}}</h1>
                <div class="mt-10 mb-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-2">
                        <x-splade-input name="email_address"  :label="__('Email Address')" />
                    </div>
                    <div class="sm:col-span-2">
                        <x-splade-input name="email_from"  :label="__('Email From')" />
                    </div>
                    <div class="sm:col-span-6">
                        <x-splade-defer
                            url=`/get_template/${form.templates}`
                            method="get"
                            watch-value="form.templates"
                            manual
                            @success="(response) => form.auto_reply = response.text"
                        >
                            <x-splade-wysiwyg name="auto_reply" v-model="form.auto_reply" id="auto_reply"  :label="__('Auto Reply Text')"  />
                        </x-splade-defer>


                    </div>
                    <div class="sm:col-span-4">
                        <x-splade-checkbox name="auto_reply_is_active" false-value="0" :label="__('Auto Reply Is Active')" />
                    </div>
                    <div class="sm:col-span-2">
                        <x-splade-select name="templates" :options="$templatesLabels" :placeholder="__('Choose Template')" option-label="name" option-label="id" :label="__('Choose Auto Reply Template')" />
                    </div>
                </div>

                <h1 class="text-center  font-medium text-3xl">{{__('IMAP Account')}}</h1>
                <div class="mt-10  mb-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-2">
                        <x-splade-input name="imap_host" :label="__('Imap Host')" />
                    </div>
                    <div class="sm:col-span-2">
                        <x-splade-input name="imap_port" type="number" :label="__('Imap Port')" />
                    </div>
                    <div class="sm:col-span-2">
                        <x-splade-select choices name="imap_encryption" :options="$encryption" :label="__('Imap Encryption')" />
                    </div>
                    <div class="sm:col-span-2">
                        <x-splade-input name="imap_username"  :label="__('Imap Username')" />
                    </div>
                    <div class="sm:col-span-2">
                        <x-splade-input name="imap_password"  type="password" :label="__('Imap Password')" />
                    </div>
                    <div class="sm:col-span-1">
                        <x-splade-input name="imap_scan_days_count" type="number" :label="__('Days For Scanning')" />
                    </div>
                </div>

                <h1 class="text-center  font-medium text-3xl">{{__('SMTP Account')}}</h1>
                <div class="mt-10  mb-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-2">
                        <x-splade-input name="smtp_host" :label="__('Smtp Host')" />
                    </div>
                    <div class="sm:col-span-2">
                        <x-splade-input name="smtp_port" type="number" :label="__('Smtp Port')" />
                    </div>
                    <div class="sm:col-span-2">
                        <x-splade-select choices name="smtp_encryption" :options="$encryption" :label="__('Smtp Encryption')" />
                    </div>
                    <div class="sm:col-span-2">
                        <x-splade-input name="smtp_username"  :label="__('Smtp Username')" />
                    </div>
                    <div class="sm:col-span-2">
                        <x-splade-input name="smtp_password"  type="password" :label="__('Smtp Password')" />
                    </div>
                    <div class="sm:col-span-1">
                        <x-splade-input name="smtp_send_email_count_in_minute" type="number" :label="__('Maximum Emails Count')" />
                    </div>
                    <div class="sm:col-span-2">
                        <x-splade-checkbox name="is_active" false-value="0" :label="__('Is Active')" />
                    </div>

                </div>

                <x-splade-submit class="mt-4"  :label="__('Save')"/>
            </x-splade-form>
        </div>
    </div>
</x-app-layout>


