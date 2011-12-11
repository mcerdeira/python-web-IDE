function debuggerStep() {

    jQuery.post('http://'+DEBUGGER_HOST+':'+DEBUGGER_PORT,
                {'command':'step'
                 },
                function(results){
                    $(document).trigger("debugger-step");
                });
    
}


function debuggerNext() {

    jQuery.post('http://'+DEBUGGER_HOST+':'+DEBUGGER_PORT,
                {'command':'next'
                 },
                function(results){
                    $(document).trigger("debugger-next");
                });

}

function debuggerContinue() {

    jQuery.post('http://'+DEBUGGER_HOST+':'+DEBUGGER_PORT,
                {'command':'continue'
                 },
                function(results){
                    $(document).trigger("debugger-continue");
                });

}

function debuggerReturn() {

    jQuery.post('http://'+DEBUGGER_HOST+':'+DEBUGGER_PORT,
                {'command':'return'
                 },
                function(results){
                    $(document).trigger("debugger-return");
                });

}

function addBreakpointsForFile(){
    jQuery.post('http://'+AUX_HOST+':'+AUX_PORT,
                {'command':'get-breakpoints',
                 'filename':env.editor.session.filename
                 },
                function(results){
                    if (results != ""){
                        var split = results.split(":");
                        env.editor.session.setBreakpoints(split);
                    }
                    else{
                    }
                });
    
}

$(document).bind("file-opened", addBreakpointsForFile);

function addBreakpoint($this) {
//     env.editor.session.setBreakpoint(row);
//     $.ajax({type: 'POST', url: 'http://'+AUX_HOST+':'+AUX_PORT, data: {'command':'add-breakpoint','row': 2}, async : false})
/*
    $.ajax({type: 'POST',
            url: 'http://'+AUX_HOST+':'+AUX_PORT,
            data: {'command':'add-breakpoint',
                   'row': row},
            async : false,
            success : function(results){
                          env.editor.session.setBreakpoint(row);
                       }
    });*/

    jQuery.post('http://'+AUX_HOST+':'+AUX_PORT,
                {'command':'add-breakpoint',
                 'line':$this.html(),
                 'filename':env.editor.session.filename
                 },
                function(results){
                    if (results == "True"){
                        
                        env.editor.session.setBreakpoint($this.html()-1);
                        $(document).trigger("breakpoint-set");
                    }
                    else{
                        $(document).trigger("breakpoint-set-error");
                    }
                });

}

function removeBreakpoint($this) {
//     env.editor.session.clearBreakpoint(row);
//     $.ajax({type: 'POST', url: 'http://'+AUX_HOST+':'+AUX_PORT, data: {'command':'add-breakpoint','row': 2}, async : false})
    
/*
    $.ajax({type: 'POST',
            url: 'http://'+AUX_HOST+':'+AUX_PORT,
            data: {'command':'remove-breakpoint',
                   'row': row},
            async : false,
            success : function(results){
                        env.editor.session.clearBreakpoint(row);
                       }
    });*/

    jQuery.post('http://'+AUX_HOST+':'+AUX_PORT,
                {'command':'remove-breakpoint',
                 'line':$this.html(),
                 'filename':env.editor.session.filename
                 },
                function(results){
                    if (results == "True"){
                        env.editor.session.clearBreakpoint($this.html()-1);
                        $(document).trigger("breakpoint-unset");
                    }
                    else{
                        $(document).trigger("breakpoint-unset-error");
                    }
                });


}

function addRemoveBreakpoint(){
//     $('.ace_gutter-cell').toggle(function(){$this = $(this); line_number = $this.html(); })

    if ($(this).hasClass("ace_breakpoint")){
        removeBreakpoint($(this));
    }
    else{
        addBreakpoint($(this));
    }
    
//     breakpoints = env.editor.session.getBreakpoints();

}

function removeCurrentLinePositionMarker(){
    if (env.editor.session.$debugger_current_line_marker !== undefined){
        env.editor.session.removeMarker(env.editor.session.$debugger_current_line_marker);
    }
}

$(document).bind("debugger-step", removeCurrentLinePositionMarker);
$(document).bind("debugger-next", removeCurrentLinePositionMarker);
$(document).bind("debugger-continue", removeCurrentLinePositionMarker);
$(document).bind("debugger-return", removeCurrentLinePositionMarker);
                    
function getDebuggerStatus(){

    var res = jQuery.ajax({type: 'POST',
                            url: 'http://'+AUX_HOST+':'+AUX_PORT,
                            async : false,
                            data: {'command': 'is-stopped'},
                            success: function(results){
                                        if (results != "False" && results != ""){
                                            var split = results.split(":");
                                            var full_path = split[0];
                                            var lineno = split[1];
                                            loadFileFromFullPath(full_path);
                                            env.editor.scrollToLine(lineno, true);
                                            addBreakpointsForFile();
                                            var range = new Range(lineno -1, 0, lineno*1, 0); // Adding *1 to the string will cast it to int (???????)
                                            env.editor.session.$debugger_current_line_marker = env.editor.session.addMarker(range, "debugger_current_line", "line");
                                        }

                                        }
                            });

    return res;
}


function checkDebuggerStopped(){
    // XXX: Reimplement this with socket.io
    var res = getDebuggerStatus();
    if (res.response == "False" || res.response == ""){
        // Hide controls
        $(".debugger-controls").css('display','none');
        $('#debugger-console').css('display','none');
        var height = $('#editor-main').height() - 75;
        $('#editor').height(height);
        env.split.resize();
        var debugger_checkbox = $("#debugger-checkbox:checked");
        if (debugger_checkbox.val() !== undefined){
            setTimeout(checkDebuggerStopped, 500);
        }
    }
    else{
        $(document).trigger("debugger-stopped");
        // Show controls
        $(".debugger-controls").css('display','block');
        $('#debugger-console').css('display','block');
        var height = $('#editor-main').height() - 75;
        $('#debugger-console').height(height * 0.3);
        $('#editor').height(height * 0.7);
        env.split.resize();
    }
}

$(document).bind("debugger-step", checkDebuggerStopped);
$(document).bind("debugger-next", checkDebuggerStopped);
$(document).bind("debugger-continue", checkDebuggerStopped);
$(document).bind("debugger-return", checkDebuggerStopped);

function enableDebugging(){

    $.ajax({
            url: 'http://'+AUX_HOST+':'+AUX_PORT,
            async: true,
            data: {'command':'start-debugging'},
            success: function(results){
                checkDebuggerStopped();
            }
            });    
    
    
}

function disableDebugging(){

    $.ajax({
            url: 'http://'+AUX_HOST+':'+AUX_PORT,
            async: true,
            data: {'command':'stop-debugging'}
            });    
    
}

function toggleDebugging(){
    var debugger_checkbox = $("#debugger-checkbox:checked");
    if (debugger_checkbox.val() !== undefined){
        enableDebugging();
    }
    else{
        disableDebugging();
    }
        
}

