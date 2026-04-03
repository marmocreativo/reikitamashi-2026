import Alpine from 'alpinejs'

import ClassicEditor from '@ckeditor/ckeditor5-build-classic'

window.editorCKEditor = (contenidoInicial) => ({
    contenido: contenidoInicial,
    editor: null,

    init() {
        ClassicEditor.create(this.$refs.editorEl, {
            toolbar: {
                items: [
                    'heading', '|',
                    'bold', 'italic', 'underline', '|',
                    'bulletedList', 'numberedList', '|',
                    'link', 'blockQuote', 'insertTable', '|',
                    'undo', 'redo',
                ],
            },
            initialData: this.contenido,
        }).then(editor => {
            this.editor = editor
            editor.model.document.on('change:data', () => {
                this.contenido = editor.getData()
            })
        })
    },

    destroy() {
        this.editor?.destroy()
    },
})

window.Alpine = Alpine

Alpine.start()