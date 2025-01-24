<div class="min-h-screen bg-gray-100">
    @include('layouts.navigation')

    <div class="flex">

            <sidebar
                :choosenEmailAccount='{!! session()->exists('chosen_email_account') ?? false !!}'
                :incomingEmailsCount='@js($incomingEmailsCount??'')'
                :outgoingEmailsCount='@js($outgoingEmailsCount??'')'
                :canSeeExtraUrls='@js(
                    (
                        in_array(
                            Auth::user()->email,
                            explode(',',env('ADMIN_USER_EMAIL'))
                        )
                    )
                    ??''
                )'
            />

        <!-- Page Content -->
        <main class="w-full overflow-x-auto">
            @if (isset($header) && $header->isNotEmpty())
                <!-- Page Heading -->
                <header class="bg-white shadow">
                    <div class=" mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif


            {{ $slot }}

        </main>
    </div>

    <sendemail
        :accountId="@js(session()->get('chosen_email_account',''))"
        :templates="@js($templates)"
        :email-data="@js($emailData??[])"
        :send-to='{!! isset($emailToReply) ? collect([$emailToReply])->toJson() : '' !!}'
        :openModal="@js($openModal??'')"
        :canBeReplied="@js($canBeReplied??'')"
     />


</div>
