<script>

import * as XLSX from 'xlsx/xlsx.mjs';
import * as fs from 'fs';

XLSX.set_fs(fs);

export default {
    components: {XLSX},

    data() {
        return {
            textNotes: {
                'Upload_File': 'Upload File',
                'Import': 'Import',
                'Preview': 'Parsed Data',
                'Reset': 'Reset',
                'ImportFileError': 'Please Upload File',
            },
            showModal: false,
            message: '',
            loading: false,
            bodyFormData: new FormData(),
            excelData: [],
            formData: {
                File: null,
            },
        }
    },
    props: {
        importType: {
            type: String,
            require: true,
        },
        importTitle: {
            type: String,
            require: true,
        }
    },
    mounted() {
    },
    computed: {
        parsedData() {
            if (this.excelData.length > 0) {
                return this.createTable(JSON.parse(this.excelData));
            } else {
                return this.textNotes.Preview;
            }
        },
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
        resetUploaded() {
            this.excelData = [];
            this.formData.File = null;
            this.$refs.fileToUpload.value = null;
            this.bodyFormData = new FormData()
        },
        createTable(tableData) {
            var result = "<table class=\"min-w-full divide-y divide-gray-200 bg-white\">";

            result += "<thead class=\"bg-gray-50\"><tr>";
            result += "<th class=\"px-6 py-4 text-xs font-medium tracking-wide text-gray-500\">#</th>";
            for (var j = 0; j < tableData[0].length; j++) {
                result += "<th class=\"px-6 py-4 text-xs font-medium tracking-wide text-gray-500\">" + tableData[0][j] + "</th>";
            }
            result += "</tr></thead>";

            result += "<tbody class=\"divide-y divide-gray-200 bg-white\">";

            for (var i = 1; i < tableData.length; i++) {
                result += "<tr class=\"hover:bg-gray-50\">";
                result += "<td class=\"whitespace-nowrap text-sm px-6 py-4 text-gray-500\">" + i + "</td>";
                for (var j = 0; j < tableData[i].length; j++) {
                    result += "<td class=\"whitespace-nowrap text-sm px-6 py-4 text-gray-500\">" + tableData[i][j] + "</td>";
                }
                result += "</tr>";
            }
            result += "</tbody></table>";
            return result;
        },

        openImportModal() {
            this.showModal = true
        },
        async submitForm() {

            this.message = "";
            this.loading = true

            if (this.formData.File == null) {
                this.$toast.open({
                    message: this.textNotes.ImportFileError,
                    type: 'error',
                });
                return false;
            }

            this.bodyFormData.append("file", this.formData.File);


            await  axios({
                method: "post",
                url: "/import/"+this.importType,
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
                    if (this.message !== '') {

                        this.showModal = false;

                        this.$toast.open({
                            message: this.message,
                            type: this.messageType,
                        });
                    }
                   this.resetUploaded()
                    this.$splade.visit("/"+this.importType);
                });

        },
        fileUploaded(event) {
            let file = event.target.files ? event.target.files[0] : null;
            if (file) {
                this.formData.File = file;
                const reader = new FileReader();

                reader.onload = (e) => {
                    /* Parse data */
                    const bstr = e.target.result;
                    const wb = XLSX.read(bstr, {type: 'binary'});
                    /* Get first worksheet */
                    const wsname = wb.SheetNames[0];

                    const ws = wb.Sheets[wsname];
                    /* Convert array of arrays */
                    this.excelData = JSON.stringify(XLSX.utils.sheet_to_json(ws, {header: 1}));
                }

                reader.readAsBinaryString(this.formData.File);
            }

        },

    },
}
</script>
<template>

    <a @click="openImportModal" v-show="!showModal"
       class=" px-4 py-2 mr-3 bg-slate-400 hover:bg-slate-600 text-white rounded-md cursor-pointer">
        {{ importTitle }}
    </a>

    <v-modal v-if="showModal" title="Confirm Action" width="sm" v-on:close="showModal = false">
        <div class="fixed w-full h-full top-0 left-0 flex items-center justify-center z-10">
            <div class="absolute w-full h-full bg-gray-900 opacity-50"
                 @click="showModal = false"></div>

            <div class="absolute max-h-full" :class="maxWidth">
                <div class="container bg-white overflow-hidden md:rounded">
                    <div
                        class="px-4 py-4 leading-none flex justify-between items-center font-medium text-sm bg-gray-100 border-b select-none">
                        <h3>{{ importTitle }}</h3>
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
                                    <div class="sm:col-span-1">
                                        <label
                                            class=" text-gray-700 mb-2 float-right">
                                            {{ textNotes.Upload_File }}
                                        </label>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <div>
                                            <input type="file" name="fileToUpload" ref="fileToUpload"
                                                   @change="fileUploaded"
                                                   accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"/>
                                        </div>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <button type="submit" :disabled="loading"
                                                class="px-3 py-1 bg-green-400 hover:bg-green-600 text-blue rounded-md font-semibold ">
                                            {{ textNotes.Import }}
                                        </button>
                                        <button type="button" @click="resetUploaded" :disabled="loading" v-show="this.excelData.length > 0"
                                                class="px-3 py-1 ml-3 bg-sky-400 hover:bg-sky-600 text-blue rounded-md font-semibold ">
                                            {{ textNotes.Reset }}
                                        </button>
                                    </div>
                                </div>

                                <div v-html="parsedData"
                                     class="shadow-sm relative border border-gray-200 sm:rounded-md mt-3 mb-3 text-center">

                                </div>


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
