<x-guest-layout>
    <x-auth-card>
        <div class="mb-4 text-sm text-gray-600">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" />

        <x-splade-form action="{{ route('password.email') }}" class="space-y-4">
            <x-splade-input id="input_type" class="block mt-1 w-full" type="text" name="input_type" :label="__('Email/Username')" required autofocus />
            <x-splade-errors>
                <p class="text-red-600 text-sm mt-2 font-sans" v-if="errors.has('username')" v-text="errors.first('username')" />
                <p class="text-red-600 text-sm mt-2 font-sans" v-if="errors.has('email')" v-text="errors.first('email')" />
            </x-splade-errors>
            <div class="flex items-center justify-end">
                <x-splade-submit :label="__('Password Reset Link')" />
            </div>
        </x-splade-form>
    </x-auth-card>
</x-guest-layout>
