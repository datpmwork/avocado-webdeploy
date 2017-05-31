@extends('layouts.root')

@section('styles')
    <link rel="stylesheet" href="{{ asset('semantic/component/dropdown.css') }}">
@endsection

@section('content')
    <div class="ui segment" id="website">
        <div class="ui grid">
            <div class="ui dimmer" v-bind:class="{ active: loading }">
                <div class="ui loader"></div>
            </div>
            <div class="column" v-bind:class="{ 'twelve wide': !expandingLog, 'eight wide': expandingLog }">
                <form class="ui form">
                    <h4 class="ui dividing header">General Infomation</h4>
                    <div class="three fields">
                        <div class="field">
                            <label>Website Name</label>
                            <input type="text"  placeholder="Website Name" v-model="website.name" name="name">
                        </div>
                        <div class="field">
                            <label>Website Type</label>
                            <input type="text"  placeholder="Website Type" v-model="website.type" readonly name="type">
                        </div>
                        <div class="field">
                            <label>Checking out Branch</label>
                            <select class="ui dropdown">
                                <option value="">Checking out Branch</option>
                                <option value="master">master</option>
                            </select>
                        </div>
                    </div>
                    <h4 class="ui dividing header">Server Information</h4>
                    <div class="three fields">
                        <div class="field">
                            <label>Website State</label>
                            <div class="ui toggle checkbox">
                                <input type="checkbox" v-model="website.is_on" name="is_on">
                                <label>Toogle State</label>
                            </div>
                        </div>
                        <div class="field">
                            <label>Username</label>
                            <input type="text" v-model="website.username" readonly name="username">
                        </div>
                        <div class="field">
                            <label>Password</label>
                            <input type="text" v-model="website.password" readonly name="password">
                        </div>
                    </div>
                    <div class="three fields">
                        <div class="field">
                            <label>Server Name</label>
                            <input type="text" v-model="website.servername" name="servername">
                        </div>
                        <div class="field">
                            <label>Document Root</label>
                            <input type="text" readonly v-model="website.document_root" name="document_root">
                        </div>
                        <div class="field">
                            <label>Git Root</label>
                            <input type="text" readonly v-model="website.git_root" name="git_root">
                        </div>
                    </div>
                    <h4 class="ui dividing header">Scripts</h4>
                    <div class="two fields">
                        <div class="field">
                            <label for="">Deploy Script</label>
                            <textarea v-model="website.deploy_scripts" id="deploy_scripts"></textarea>
                        </div>
                        <div class="field">
                            <label for="">Apache Config</label>
                            <textarea v-model="website.apache_config" id="apache_config"></textarea>
                        </div>
                    </div>
                    <button class="ui button basic green">Save changes</button>
                </form>
            </div>
            <div class="column logs" v-bind:class="{ 'four wide': !expandingLog, 'eight wide': expandingLog }">
                <label><b>Logs</b></label>
                <div class="ui top attached tabular menu">
                    <a class="item active" data-tab="activity">Activity</a>
                    <a class="item" data-tab="apache_log">Apache Logs</a>
                    <a class="item" data-tab="apache_error_log">Error Logs</a>
                </div>
                <div class="ui bottom attached tab segment active" data-tab="activity">
                    <editor v-model="website.activity_logs" mode="apache_conf"></editor>
                </div>
                <div class="ui bottom attached tab segment" data-tab="apache_log">
                    <textarea class="logs-viewer"></textarea>
                </div>
                <div class="ui bottom attached tab segment" data-tab="apache_error_log">
                    <textarea class="logs-viewer"></textarea>
                </div>
                <button class="ui button basic green" v-on:click="expandingLog = !expandingLog">@{{ expandingLog ? 'Smaller' : 'Larger' }}</button>
            </div>
        </div>
    </div>
@endsection

@push('before-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.3/ace.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.6/theme-github.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.6/mode-apache_conf.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.6/mode-batchfile.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.6/mode-sh.js"></script>
<script>
    window.websiteId = {!! $website->id !!};
</script>
@endpush