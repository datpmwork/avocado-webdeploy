
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

window.Vue = require('vue');
window.axios = require('axios');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
var _axios = window.axios.create({
    baseURL: window.location.origin,
    headers: {'X-Requested-With': 'XMLHttpRequest'}
});
var website = new Vue({
    el: "#website",
    data: {
        website: window.website,
        loading: false,
        expandingLog: false,
        socket: null
    },
    methods: {
        saveChange: function() {
            var _ = this;
            _.loading = true;
            _axios.post('/website/store', this.website).then(function(response) {
                _.loading = false;
            });
        },
        initSocket: function() {
            this.socket = io.connect('http://localhost:3000');
            this.socket.on('connect', function(msg){
                console.log("connected to server");
            });
            this.socket.on("disconnect", function(){
                console.log("client disconnected from server");
            });
            this.socket.on('event', function(data) {
                console.log(data);
            });
        }
    },
    mounted: function() {
        var _ = this;
        $(document).ready(function() {
            var editor_deploy_scripts = ace.edit("deploy_scripts");
            editor_deploy_scripts.setTheme("ace/theme/github");
            editor_deploy_scripts.getSession().setMode("ace/mode/batchfile");
            editor_deploy_scripts.getSession().on('change', function() {
                _.website.deploy_scripts = editor_deploy_scripts.getValue();
            });

            var editor_apache_config = ace.edit("apache_config");
            editor_apache_config.setTheme("ace/theme/github");
            editor_apache_config.getSession().setMode("ace/mode/apache_conf");
            editor_apache_config.getSession().on('change', function() {
                _.website.apache_config = editor_apache_config.getValue();
            });

            $(".logs-viewer").each(function() {
                var id = "ace-" + Math.floor(Math.random() * 10000);
                $(this).attr("id", id);
                var editor_logs = ace.edit(id);
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
        this.initSocket();
    }
});