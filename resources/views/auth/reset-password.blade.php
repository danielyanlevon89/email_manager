<x-guest-layout>
    <x-auth-card>
        <x-splade-form :default="['token' => $request->route('token')]" action="{{ route('password.store') }}" class="space-y-4">
            <x-splade-input id="input_type" type="text" name="input_type" :label="__('Email/Username')" required autofocus />
            <x-splade-errors>
                <p class="text-red-600 text-sm mt-2 font-sans" v-if="errors.has('username')" v-text="errors.first('username')" />
                <p class="text-red-600 text-sm mt-2 font-sans" v-if="errors.has('email')" v-text="errors.first('email')" />
            </x-splade-errors>
            <x-splade-input id="password" type="password" name="password" :label="__('Password')" required />
            <x-splade-input id="password_confirmation" type="password" name="password_confirmation" :label="__('Confirm Password')" required />

            <div class="flex items-center justify-end">
                <x-splade-submit :label="__('Reset Password')" />
            </div>
        </x-splade-form>
    </x-auth-card>
</x-guest-layout>
