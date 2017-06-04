
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.axios = require('axios');

Vue.component('editor', require('./components/editor.vue'));

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
let _axios = window.axios.create({
    baseURL: window.location.origin,
    headers: {'X-Requested-With': 'XMLHttpRequest'}
});

if (document.querySelector("#website")) {
    let website = new Vue({
        el: "#website",
        data: {
            website: {},
            loading: true,
            expandingLog: false,
        },
        methods: {
            saveChange: function() {
                let _ = this;
                _.loading = true;
                _axios.post('/website/store', this.website).then(function(response) {
                    _.loading = false;
                });
            },
            listen: function() {
                let _ = this;
                Echo.channel('system').listen('system_event', (e) => {
                    //TODO: Alert Notify
                    console.log(e);
                });
                Echo.channel('website_channel_' + window.websiteId).listen('website_event', function(e) {
                    _.loading = true;
                    _.website = e.website;
                    _.loading = false;
                });
            }
        },
        mounted: function() {
            let _ = this;
            _axios.get('/website/show/' + window.websiteId).then(function (response) {
                _.website = response.data;
                _.loading = false;
            });
            $(document).ready(function () {
                $(".logs-viewer").each(function () {
                    let id = "ace-" + Math.floor(Math.random() * 10000);
                    $(this).attr("id", id);
                    let editor_logs = ace.edit(id);
                    editor_logs.setTheme("ace/theme/github");
                    editor_logs.getSession().setMode("ace/mode/sh");
                    editor_logs.getSession().on('change', function () {
                    });
                });

                $('.menu .item').tab();
                $('.ui.dropdown').dropdown();
                $('.ui.form').form({
                    fields: {
                        servername: 'empty',
                        document_root: 'empty',
                        git_remote_url: 'empty',
                        name: 'empty',
                        checkout: 'empty'
                    },
                    onSuccess: function (e) {
                        e.preventDefault();
                        _.saveChange();
                    }
                });
            });
            this.listen();
        }
    });
}