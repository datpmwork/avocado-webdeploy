<template>
    <textarea :value="val"></textarea>
</template>

<script>
    export default {
        props: ['value', 'mode'],
        data: function() {
            return { id: '', editor: null, val: this.value }
        },
        methods: {
            updateValue: (value) => {

            }
        },
        mounted() {
            let _ = this;
            this.id = "ace-" + Math.floor(Math.random() * 10000);
            this.$el.setAttribute('id', this.id);
            this.editor = ace.edit(this.id);
            this.editor.setReadOnly(true);
            this.editor.setTheme("ace/theme/github");
            this.editor.getSession().setMode("ace/mode/" + this.mode);
            this.editor.getSession().on('change', () => {
                let newValue = _.editor.getValue();
                _.$emit('input', newValue);
            });
            this.$el.addEventListener('change', (e) => {
                console.log(e.value);
            });
        },
        watch: {
            value(val) {
                this.editor.setValue(val);
                this.editor.clearSelection();
            }
        }
    }
</script>
