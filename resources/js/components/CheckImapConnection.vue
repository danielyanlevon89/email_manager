<script>


export default {
    data() {
        return {
            textNotes: {
                'checkImapConnection': 'Test Imap Connection',
                'checkImapConnectionProcess': 'Connection Process ...',
                'imapError': 'IMAP Account Id Is Empty',
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
            return this.loading ? this.textNotes.checkImapConnectionProcess : this.textNotes.checkImapConnection;
        },
    },
    methods: {
        async checkImap() {

            if (typeof this.accountId == 'undefined') {
                this.message = this.textNotes.imapError;
                this.messageType = 'error'
                return false;
            }

            this.message = "";
            this.loading = true

            await axios.get("check_imap_connection/" + this.accountId)
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

    <button @click="checkImap()" :disabled="loading"
            class="px-3 py-2 bg-blue-400 hover:bg-blue-600 text-white rounded-md font-semibold ml-3 ">
        {{ buttonText }}
    </button>
</template>

<style scoped>

</style>
