<script>


export default {
    data() {
        return {
            textNotes: {
                'checkSmtpConnection': 'Test Smtp Connection',
                'checkSmtpConnectionProcess': 'Connection Process ...',
                'SmtpError': 'Smtp Account Id Is Empty',
            },
            message: '',
            messageType: '',
            loading: false,
        }
    },
    props: {
        accountId: {
            type: String,
            require: true,
        }
    },
    computed: {
        buttonText() {
            return this.loading ? this.textNotes.checkSmtpConnectionProcess : this.textNotes.checkSmtpConnection;
        },
    },
    methods: {
        async checkSmtp() {

            if (typeof this.accountId == 'undefined') {
                this.message = this.textNotes.SmtpError;
                this.messageType = 'error'
                return false;
            }
            this.message = "";
            this.loading = true


            await axios.get("check_smtp_connection/" + this.accountId)
                .then(res => {
                    this.message = res.data.message
                    this.messageType = res.data.type
                })
                .finally(() => this.loading = false);

            if (this.message != '') {
                this.$toast.open({
                    message: this.message,
                    type: this.messageType,
                });
            }

        }
    }
}
</script>
<template>

    <button @click="checkSmtp()" :disabled="loading"
            class="px-3 py-2 bg-yellow-400 hover:bg-yellow-600 text-white rounded-md font-semibold ml-3 ">
        {{ buttonText }}
    </button>
</template>

<style scoped>

</style>
