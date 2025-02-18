<script>

import 'jodit/build/jodit.min.css'
import {JoditEditor} from 'jodit-vue'
import Vdropdown from './Vdropdown.vue'


export default {
    components: {
        JoditEditor,
        Vdropdown
    },
    data() {
        return {
            textNotes: {
                'SmtpError': 'Please Choose Account',
                'EmailTo': 'Email To',
                'EmailCc': 'Email Cc',
                'Content': 'Content',
                'Subject': 'Subject',
                'Choose_Template': 'Choose Template',
                'Upload_Files': 'Upload Files',
                'Send': 'Send',
                'EmailToEmpty': 'Email To Can not be empty',
                'SubjectEmpty': 'Subject Can not be empty',
                'ContentEmpty': 'Content Can not be empty',
                'NewMessage': 'New Message',
                'Reply': 'Reply',
                'Keywords': 'Use keywords to implement variables into template',
            },
            message: '',
            title: 'Send New Email',
            messageType: '',
            loading: false,
            showModal: false,
            dropdownTitle: 'Choose Template',
            bodyFormData: new FormData(),
            formData: {
                EmailCc: '',
                EmailTo: '',
                EmailMessageId: '',
                Content: '',
                Subject: '',
                Files: '',
            },

        }
    },
    props: {
        replyTo: {
            type: String,
            require: true,
        },
        emailData: {
            type: Array,
            require: true,
        },
        templates: {
            type: Array,
            require: true,
        },
        openModal: {
            type: String,
            default:false
        },
        canBeReplied: {
            type: String,
            default:false
        },
        accountId: {
            type: String,
            require: true,
        }
    },
    mounted() {

        if (this.openModal) {
            this.showModal = true;
        } else {
            this.showModal = false;
        }
        if (this.replyTo != '' && this.openModal) {
            this.formData.EmailTo = this.replyTo;
        }
        if(typeof this.templates != 'undefined') {
            this.templates.Clear = ''
        }

    },
    computed: {

        maxWidth() {
            switch (this.width) {
                case "xs":
                    return "max-w-lg";
                case "sm":
                    return "max-w-xl";
                case "md":
                    return "max-w-2xl";
                case "lg":
                    return "max-w-3xl";
                case "full":
                    return "max-w-full";
            }
        },
        shade() {
            switch (this.type) {
                case "info":
                    return "gray";
                case "warning":
                    return "yellow";
                case "success":
                    return "teal";
                case "danger":
                    return "red";
            }
        },
        color() {
            return `text-${this.shade}-600`;
        },
    },
    methods: {
        setReplyContent(val) {
            return ' <br><br><br><div dir="ltr">'+this.emailData.email_date+' &lt;<a href="mailto:'+this.emailData.from+'" target="_blank">'+this.emailData.from+'</a>&gt;:<br></div>' +
                '<blockquote style="border-left:1px solid #0857A6; margin:10px; padding:0 0 0 10px;">'+val.body+' </blockquote>';
        },
        setReplySubject(val) {
            return val.includes('Re:') ? val : 'Re: '+val;
        },
        uploadFiles(e) {
            this.formData.Files = e.target.files;

        },
        visitDashboard() {
            this.$splade.visit("/dashboard");
        },
         async submitForm() {

             this.message = "";
             this.loading = true

            if (this.accountId == '') {
                this.message = this.textNotes.SmtpError;
                this.messageType = 'error'
            }
            if (this.formData.EmailTo == '') {
                this.message = this.textNotes.EmailToEmpty;
                this.messageType = 'error'
            }
            if (this.formData.Subject == '') {
                this.message = this.textNotes.SubjectEmpty;
                this.messageType = 'error'
            }
            if (this.formData.Content == '') {
                this.message = this.textNotes.ContentEmpty;
                this.messageType = 'error'
            }
            if(this.message != '') {
                this.$toast.open({
                    message: this.message,
                    type: this.messageType,
                });
                return false;
            }
            this.message = "";
            this.loading = true

            this.bodyFormData.append('mail_to', this.formData.EmailTo);
            this.bodyFormData.append('mail_cc', this.formData.EmailCc);
            this.bodyFormData.append('message_id', this.formData.EmailMessageId);
            this.bodyFormData.append('subject', this.formData.Subject);
            this.bodyFormData.append('body', this.formData.Content);
             for (var x = 0; x < this.formData.Files.length; x++) {
                 this.bodyFormData.append("attachment[]", this.formData.Files[x]);
             }

            await  axios({
                method: "post",
                url: "/send_email",
                data: this.bodyFormData,
                headers: {"Content-Type": "multipart/form-data"},
            })
                .then(res => {
                    this.message = res.data.message
                    this.messageType = res.data.type
                })
                .catch(error => {
                    this.messageType = 'error'
                    this.message = error.response.data.message
                })
                .finally(() => {
                    this.loading = false
                    if (this.message != '') {

                        this.showModal = false;

                        this.$toast.open({
                            message: this.message,
                            type: this.messageType,
                        });
                    }
                    this.bodyFormData = new FormData()

                });

        },
        chooseOption(val) {
            this.formData.Content = this.templates[val];
        },
        openNewMessageModal() {
            this.showModal = true
            this.formData = {
                    EmailCc: '',
                    EmailTo: '',
                    EmailMessageId: '',
                    Content: '',
                    Subject: '',
                    Files: '',
            }
        },
        openReplyMessageModal() {

            this.formData.Files = '';
            this.formData.EmailMessageId = this.emailData.message_id ?? '';
            if (this.replyTo != '') {
                this.formData.EmailTo = this.replyTo;
            }
            if (this.emailData.cc != '') {
                this.formData.EmailCc = this.emailData.cc;
            }
            if (this.emailData.subject != '') {
                this.formData.Subject = this.setReplySubject(this.emailData.subject);
            }
            if (this.emailData.body != '') {
                this.formData.Content = this.setReplyContent(this.emailData);
            }
            this.showModal = true
        },

    },
}
</script>
<template>

    <button @click="openNewMessageModal" v-show="!showModal && accountId" class="
     z-20 text-black flex flex-col shrink-0 grow-0 justify-around
                  fixed bottom-0 right-0 rounded-lg
                  mr-1 mb-5 lg:mr-5 lg:mb-5 xl:mr-10 xl:mb-10 px-3 py-2
                  bg-gray-400 hover:bg-gray-600 text-white rounded-md font-semibold">
        {{ textNotes.NewMessage }}
    </button>
    <button @click="openReplyMessageModal" v-show="!showModal && canBeReplied && accountId" class="
     z-20 text-black flex flex-col shrink-0 grow-0 justify-around
                  fixed bottom-0  right-150 rounded-lg
                  mr-1 mb-5 lg:mr-5 lg:mb-5 xl:mr-10 xl:mb-10 px-3 py-2
                  bg-gray-400 hover:bg-gray-600 text-white rounded-md font-semibold">
        {{ textNotes.Reply }}
    </button>


    <v-modal v-if="showModal" title="Confirm Action" width="sm" v-on:close="showModal = false">
        <div class="fixed w-full h-full top-0 left-0 flex items-center justify-center z-10">
            <div class="absolute w-full h-full bg-gray-900 opacity-50"
                 @click="showModal = false"></div>

            <div class="absolute max-h-full" :class="maxWidth">
                <div class="container bg-white overflow-hidden md:rounded">
                    <div
                        class="px-4 py-4 leading-none flex justify-between items-center font-medium text-sm bg-gray-100 border-b select-none">
                        <h3>{{ title }}</h3>
                        <div @click="showModal = false"
                             class="text-2xl hover:text-gray-600 cursor-pointer">
                            &#215;
                        </div>
                    </div>

                    <div class="max-height-700 px-4 ">
                        <div class="container mx-auto py-2 px-4">
                            <form @submit.prevent="submitForm"
                                  class="max-w-4xl mx-auto bg-white rounded-lg">
                                <div class="mt-3 mb-3 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                    <div class="sm:col-span-3">
                                        <label for="to"
                                               class="block text-gray-700 mb-2">
                                            {{ textNotes.EmailTo }}
                                        </label>

                                        <input v-model="formData.EmailTo"
                                               type="text" id="to" name="mail_to"
                                               class="w-full form-input p-1 border rounded-md shadow-sm" required>
                                    </div>
                                    <div class="sm:col-span-3">
                                        <label for="cc"
                                               class="block text-gray-700 mb-2">
                                            {{ textNotes.EmailCc }}
                                        </label>
                                        <input v-model="formData.EmailCc"
                                               type="text" id="cc" name="cc"
                                               class="w-full form-input p-1 border rounded-md shadow-sm">
                                    </div>

                                    <div class="sm:col-span-6">
                                        <label for="subject"
                                               class="block text-gray-700 mb-2">
                                            {{ textNotes.Subject }}
                                        </label>
                                        <input v-model="formData.Subject"
                                               type="text" id="subject" name="subject"
                                               class="w-full form-input p-1 border
                                      rounded-md shadow-sm" required>
                                    </div>
                                    <div class="sm:col-span-3">
                                        <label
                                            class="block text-gray-700 mb-2">
                                            {{ textNotes.Choose_Template }}
                                        </label>
                                        <Vdropdown placement="left"
                                                   :chooseOption="chooseOption"
                                                   :dropdownTitle="dropdownTitle"
                                                   :options="templates"
                                        >
                                            <template v-slot:button></template>
                                            <template v-slot:content></template>
                                        </Vdropdown>
                                    </div>
                                    <div class="sm:col-span-3">
                                        <label
                                            class="block text-gray-700 mb-2">
                                            {{ textNotes.Upload_Files }}
                                        </label>
                                        <div>
                                            <input type="file" class="w-full p-1" multiple @change=uploadFiles>
                                        </div>
                                    </div>
                                    <div class="sm:col-span-6">
                                        <label for="address"
                                               class="block text-gray-700 mb-2">
                                            {{ textNotes.Content }}
                                        </label>

                                        <jodit-editor name="body" v-model="formData.Content"/>
                                        <p class="text-right mt-2">{{textNotes.Keywords}} {url} , {sender_name} , {sender_email} , {recipient_name} , {recipient_email} , {email_date}</p>
                                    </div>
                                </div>

                                <button type="submit" :disabled="loading"
                                        class="px-3 py-2 bg-green-400 hover:bg-green-600 text-white rounded-md font-semibold ">
                                    {{ textNotes.Send }}
                                </button>
                            </form>

                        </div>

                    </div>
                </div>
            </div>
        </div>

    </v-modal>


</template>

<style scoped>
.max-height-700 {
    max-height: 700px;
    overflow-y: scroll;
}
.right-150 {
    right: 150px;
}
</style>
