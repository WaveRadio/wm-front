/*
    
    This is WaveManager Front-end.

*/

var
    codeEditor = null,
    currentInstanceIndex = -1,
    editorFullscreen = false;

function sanitize (s) {
    var map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        
    s = s.replace(/[&<>"']/g, function(m) { return map[m]; });

    return s;
}

function rnd(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function randword() {
    var s = '';
    var ltr = 'qwertyuiopasdfghjklzxcvbnm';
    while (s.length < 20)
    {
        s += ltr[rnd(0, 20)];
    }
    return s;
}

function showMessage (message, type)
{
    type = type || 'info';

    var id = randword();

    $('#messages-wrapper').append('<div class="message '+type+'" id="'+id+'">'
        +message+'</div>');

    var block = $('#'+id);

    block.click(function() {block.fadeOut(100, function() { block.remove(); });});

    setTimeout(function() {block.fadeOut(100, function() { block.remove(); });},
        5000);

    block.fadeIn(100);
}

function switchTab (object)
{
    var name = (typeof object === 'object') ? $(object).data('tab-name') : object;

    $('.tab-content').hide();
    $('.tab-pointer').removeClass('active');
    $('#tab-content-'+name).show();
    $('#tab-'+name).addClass('active');

    // automatically scroll log, autoscroll doesn't work on hidden elements
    if (name == 'log')
    {
        var  evLog = $('#event-log');
        evLog.scrollTop(evLog[0].scrollHeight);
    }
}

function editorReset()
{
    $('#instance-name').val('');
    codeEditor.setValue('', 1);
}

function toggleEditorFullscreen()
{
    var ed = $('#editor-wrap'),
        tg = $('#editor-fullscreen-toggler');

    if (ed.hasClass('fullscreen'))
    {
        tg.removeClass('collapse');
        tg.addClass('expand');
        ed.removeClass('fullscreen');

        $('#instance-code').removeClass('fullscreen');
    }
    else
    {
        tg.removeClass('expand');
        tg.addClass('collapse');
        ed.addClass('fullscreen');

        $('#instance-code').addClass('fullscreen');
    }

    codeEditor.resize();
}

function wm_clearInstanceList() {
    $('#instances-list').html('');
}

function wm_addInstance(instanceDescription) {
    var instances = $('#instances-list'),
        modState = (instanceDescription.state === "up") ? 'active' : 'inactive';

        instances.append('<div class="instance" id="instance-' + sanitize(instanceDescription.type) + '-' + sanitize(instanceDescription.tag) + '">' +
                        '<div class="instance-tools">' +
                            '<div class="instance-tool ' + modState + '" data-instance-tag="' + sanitize(instanceDescription.tag) + '" data-instance-type="' + sanitize(instanceDescription.type) + '" title="Start/stop this instance"></div>' +

                            '<div class="instance-tool edit" data-instance-tag="' + sanitize(instanceDescription.tag) + '" data-instance-type="' + sanitize(instanceDescription.type) + '" title="Edit the code of this instance"></div>' +

                            '<div class="instance-tool remove" data-instance-tag="'+sanitize(instanceDescription.tag) + '" data-instance-type="' + sanitize(instanceDescription.type) + '" title="Remove this instance"></div>' +
                        '</div>' +
                        '<div class="instance-name">' + sanitize(instanceDescription.tag) + '</div>' +
                    '</div>');
}

function wm_newInstance()
{
    editorReset();
    switchTab('editor');
}

function wm_saveInstance() {
    // todo: send API command to save instance
}

function wm_initTabs(defaultTab) {
    $('.tab-pointer').each(function (idx, tab) {
        $(tab).click(function() {switchTab(this)});
    });

    switchTab(defaultTab);
}

function wm_init(wmBackendSettings) {    
    

    /* codeEditor = ace.edit("instance-code");
    codeEditor.setTheme("ace/theme/tomorrow_night_bright");
    codeEditor.getSession().setMode("ace/mode/javascript");
    codeEditor.getSession().setUseWrapMode(true);
    codeEditor.setHighlightActiveLine(false);

    $('#editor-save').click(wm_saveInstance);
    $('#editor-reset').click(editorReset);
    $('#instances-new').click(wm_newInstance);
    $('#editor-fullscreen-toggler').click(toggleEditorFullscreen); */

    wm_initTabs('instances');

    wmc_init(wmBackendSettings);
}