<script>


export default {
    data() {
        return {
            textNotes: {
                'checkImapConnection': 'Test Imap',
                'checkImapConnectionProcess': 'Process ...',
                'imapError': 'IMAP Account Id Is Empty',
            },
            message: '',
            messageType: '',
            loading: false,
            validAccount: false,
        }
    },
    props: {
        accountId: {
            type: String,
            require: true,
        },
        validAccount: {
            type: String,
            require: true
        }
    },
    mounted() {
        this.validAccount = this.$props.validAccount
    },
    computed: {
        buttonText() {
            return this.loading ? this.textNotes.checkImapConnectionProcess : this.textNotes.checkImapConnection;
        },
        validationClassNames() {
            return this.validAccount == 1 ? 'valid' : 'invalid';
        },
    },
    methods: {
        changeValidationValue(validation) {
            this.validAccount = validation
        },
        async checkImap() {

            if (typeof this.accountId == 'undefined')
            {
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
                    this.changeValidationValue(res.data.validAccount ?? false)
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
            class="px-3 py-2 bg-sky-200 hover:bg-sky-300 text-blue rounded-md font-semibold ml-3 ">
        {{ buttonText }}
        <span class="cycle" :class=" validationClassNames "></span>
    </button>
</template>

<style scoped>
.valid{
    background-color: green;
}
.invalid{
    background-color: red;
}
.cycle{
    float: right;
    margin-top: 3px;
    margin-left: 10px;
    border-radius: 10px;
    height: 15px;
    width: 15px;
    display: block;

}
</style>
