<template>
    <div>
        <notifications group="document" position="top center" />
        <div class="flex flex-row flex-wrap items-center">
            <h1 class="text-4xl p-2">
                {{ this.title }}
            </h1>
            <dk-button @click="showConfirm = true">{{ $lang.get('general.save') }}</dk-button>
        </div>
        <div class="p-2" >
            <ckeditor :editor="editor" @ready="getDocument()" v-model="editorData" :config="editorConfig"></ckeditor>
        </div>
        <dk-modal :show="showConfirm" @close="showConfirm = false">
            <div class="m-2 w-full sm:w-1/2 md:w-4/12 lg: w-1/12 bg-white rounded text-black p-3 mx-auto">
                <div class="text-center pb-3 text-gray-900 text-xl" >
                    {{ $lang.get('documentedit.save-confirm') }}
                </div>
                <div class="flex flex-wrap justify-center flex-row">
                    <dk-button hoverColor="red-500" @click="showConfirm = false">
                        {{ $lang.get('general.abort') }}
                    </dk-button>
                    <dk-button @click="updateDocument()">
                        {{ $lang.get('general.save') }}
                    </dk-button>
                </div>
            </div>
        </dk-modal>
    </div>
</template>
<script>

import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

export default {
    props: {
        title: String,
        routeprefix: String
    },

    data()  {
        return {
            editor: ClassicEditor,
            editorData: '',
            showConfirm: false,
            editorConfig: {
                toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote' ],
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                        { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading2' }
                    ]
                },
            },
        };
    },
    methods: { 

        // retrieves the document from the backend
        getDocument() {
            axios.get('/' + this.routeprefix + '/get')
                .then(response => {
                    this.editorData = response.data.content;
                })
                .catch(error => {
                    this.$notify({
                        group: 'document',
                        title: 'Error',
                        text: error,
                        type: 'error'
                    });
                });
        },

        // updates the document with the edior content
        updateDocument() {
            axios.post('/' + this.routeprefix + '/update', {content: this.editorData})
                .then(response => {
                    this.showConfirm = false;
                    this.$notify({
                        group: 'document',
                        title: 'Gespeichert',
                        type: 'success'
                    });
                })
                .catch(error => {
                    this.showConfirm = false;
                    this.$notify({
                        group: 'document',
                        title: 'Error',
                        text: error,
                        type: 'error'
                    });
                });
        }
    }
}
</script>
