/*
    
    This is WaveManager Front-end.

*/

var
    codeEditor = null,
    currentInstanceIndex = -1,
    editorFullscreen = false;


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

function saveStorage()
{
    chrome.storage.local.set(storage);
}

function loadModules()
{
    m0s_log('Reloading modules');
    var len = (typeof storage.modules !== 'undefined') ? storage.modules.length : 0;

    if (len == 0)
    {
        m0s_log('No elements found, probably a first run.', 'warn');

        storage.modules = [];

        storage.modules.push (
            {
            'name'   : 'Make Galaxy great again!',
            'code'   : '// This is a simple test. This module does exactly nothing. Write your own!',
            'active' : true
            });

        len++;
        saveStorage();
    }

    m0s_log('Found '+len+' element(s)');

    var mlist = $('#modules-list');

    mlist.html('');

    for (i = 0; i < len; i++)
    {
        var modState = (storage.modules[i].active) ? 'active' : 'inactive';

        mlist.append('<div class="module" id="module-'+i+'">'+
                        '<div class="module-tools">' +
                            '<div class="module-tool '+modState+'" data-module-index="'+i+'" title="Activate/deactivate this module"></div>' +
                            '<div class="module-tool edit" data-module-index="'+i+'" title="Edit the code of this module"></div>' +
                            '<div class="module-tool remove" data-module-index="'+i+'" title="Remove this module"></div>' +
                        '</div>' +
                        '<div class="module-name">'+sanitize(storage.modules[i].name)+'</div>' +
                    '</div>');
    }

    $('.module-tool.edit').each(function (idx, ctl) {
        $(ctl).click(prepareForEdit);
    });

    $('.module-tool.remove').each(function (idx, ctl) {
        $(ctl).click(removeModule);
    });

    $('.module-tool.active, .module-tool.inactive').each(function (idx, ctl) {
        $(ctl).click(toggleModuleActive);
    });

    m0s_log('Everything is loaded and ready.', 'info');
}

function editorReset()
{
    $('#module-name').val('');
    codeEditor.setValue(M0SSION_CODE_DEFAULT, 1);
    currentModuleIndex = -1;
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

        $('#module-code').removeClass('fullscreen');
    }
    else
    {
        tg.removeClass('expand');
        tg.addClass('collapse');
        ed.addClass('fullscreen');

        $('#module-code').addClass('fullscreen');
    }

    codeEditor.resize();
}

function newModule()
{
    editorReset();
    switchTab('editor');
}

function prepareForEdit()
{
    var index = +$(this).data('module-index');

    if (typeof storage.modules[index] === 'undefined')
        m0s_log('Module editing: no module at '+index+' index! That\'s a bug!', 'warn');
    else
    {
        $('#module-name').val(storage.modules[index].name);
        codeEditor.setValue(storage.modules[index].code, 1);
        currentModuleIndex = index;
        m0s_log('Editing module at index '+currentModuleIndex+' (\''+sanitize(storage.modules[index].name)+'\')');

        switchTab('editor');
    }
}

function saveModule()
{
    var name = $('#module-name').val(),
        code = codeEditor.getValue(),
        index = currentModuleIndex;

    if (!name || !code)
    {
        m0s_log('No module name or code present, editing cancelled!', 'warn');
        showMessage('No module name or code, will not save.', 'error');
        return;
    }

    if (index == -1)
    {
        storage.modules.push ({
            'name'   : name,
            'code'   : code,
            'active' : true
        });
        currentModuleIndex = storage.modules.length - 1;

        m0s_log('Creating a new module '+currentModuleIndex+' (\''+sanitize(name)+'\')');
        showMessage('Added new module '+sanitize(name));
    }
    else
    {
        if (typeof storage.modules[index] === 'undefined')
            m0s_log('Module editing: no module at '+index+' index! That\'s a bug!', 'warn');
        else
        {
            storage.modules[index].name = name;
            storage.modules[index].code = code;

            m0s_log('Edited module at index '+currentModuleIndex+' (\''+sanitize(name)+'\')');

            showMessage(sanitize(name)+' edited successfully');
        }
    }

    saveStorage();
    loadModules();
}

function toggleModuleActive()
{
    var index = +$(this).data('module-index');

    if (typeof storage.modules[index] === 'undefined')
        m0s_log('Module (de)activating: no module at '+index+' index! That\'s a bug!', 'warn');
    else
    {
        storage.modules[index].active = !storage.modules[index].active;
        saveStorage();
        loadModules();

        m0s_log('(De)activated module at index '+index+' (\''+sanitize(storage.modules[index].name)+'\')');

        showMessage(sanitize(storage.modules[index].name) + ' has been successfully '
            +(storage.modules[index].active ? '' : 'de')+'activated');
    }
}

function removeModule ()
{
    var index = +$(this).data('module-index');

    if (typeof storage.modules[index] === 'undefined')
        m0s_log('Module removal: no module at '+index+' index! That\'s a bug!', 'warn');
    else
    {
        var name = storage.modules[index].name;
        storage.modules.splice(index, 1);
        saveStorage();
        loadModules();

        m0s_log('Removed module at index '+currentModuleIndex);

        showMessage(sanitize(name) + ' has been successfully removed.');

        if (currentModuleIndex == index)
            currentModuleIndex = -1;
    }
}

function wm_init()
{    
    //m0s_log('Welcome to m0ssion/'+M0SSION_VERSION, 'info');
    // $('#m0s-version').text(M0SSION_VERSION);

    //logControlsInit();

   // m0s_log('Setting up UI...');
    $('.tab-pointer').each(function (idx, tab) {
        $(tab).click(function() {switchTab(this)});
    });

    codeEditor = ace.edit("module-code");
    codeEditor.setTheme("ace/theme/tomorrow_night_bright");
    codeEditor.getSession().setMode("ace/mode/javascript");
    codeEditor.getSession().setUseWrapMode(true);
    codeEditor.setHighlightActiveLine(false);

    $('#editor-save').click(saveModule);
    $('#editor-reset').click(editorReset);
    $('#modules-new').click(newModule);
    $('#editor-fullscreen-toggler').click(toggleEditorFullscreen);

    switchTab('instances');
}

$(document).ready(wm_init);