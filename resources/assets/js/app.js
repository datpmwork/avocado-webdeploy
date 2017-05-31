
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
            expandingLog: true,
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
            _axios.get('/website/show/' + window.websiteId).then(function(response) {
                _.website = response.data;
                _.loading = false;
            });
            $(document).ready(function() {
                let editor_deploy_scripts = ace.edit("deploy_scripts");
                editor_deploy_scripts.setTheme("ace/theme/github");
                editor_deploy_scripts.getSession().setMode("ace/mode/batchfile");
                editor_deploy_scripts.getSession().on('change', function() {
                    _.website.deploy_scripts = editor_deploy_scripts.getValue();
                });

                let editor_apache_config = ace.edit("apache_config");
                editor_apache_config.setTheme("ace/theme/github");
                editor_apache_config.getSession().setMode("ace/mode/apache_conf");
                editor_apache_config.getSession().on('change', function() {
                    _.website.apache_config = editor_apache_config.getValue();
                });

                $(".logs-viewer").each(function() {
                    let id = "ace-" + Math.floor(Math.random() * 10000);
                    $(this).attr("id", id);
                    let editor_logs = ace.edit(id);
                    editor_logs.setTheme("ace/theme/github");
                    editor_logs.getSession().setMode("ace/mode/sh");
                    editor_logs.getSession().on('change', function() {});
                });

                $('.ui.dropdown').dropdown();
                $('.menu .item').tab();
                $('.ui.form').form({
                    fields: {
                        servername     : 'empty',
                        document_root   : 'empty',
                        git_root : 'empty',
                        name: 'empty'
                    },
                    onSuccess: function(e) {
                        e.preventDefault();
                        _.saveChange();
                    }
                });
            });
            this.listen();
        }
    });
}