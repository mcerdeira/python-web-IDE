$(document).ready(function() {
    $("#message").show();
    $("#debugger").hide();
});

function closeNotice() {
    $("#message").fadeOut("slow");
}

function saveNotice(){
    var content = window.aceEditor.getSession().getValue();
    var curFullFile = window.curFullFile;
    $.post("save.file", {content: content, curFullFile: curFullFile});
}

function saveasNotice(){
    var curFile = window.curFile;
    var projectPath = window.projectPath;
    var content = window.aceEditor.getSession().getValue();
    var newFile = prompt("Enter file name",$.trim(curFile));
    if (newFile != null){
       if (newFile){
          $.post("saveas.file", {content: content, newFile: newFile, projectPath: projectPath},
                 function(){
                     // TODO: add some sort of control, to check if can create the file
                     window.location.href = "/django-ide/editor/" + newFile; // Reload file
                 }
          );
       }else{
          alert("You must enter a file name.");
       }
    }
}

function deleteNotice(){
    var curFullFile = window.curFullFile;
    var projectName = window.projectName;
    if(confirm("Do you want to delete " + $.trim(curFullFile) + " ?")){
        $.post("delete.file", {curFullFile: curFullFile},
                function(){
                     // TODO: add some sort of control, to check if can delete the file
                     window.location.href = "/django-ide/open/" + $.trim(projectName); // Go to project main
                 }
        );
    }
}

/*
  ###############################################
  #############  Debugger functions #############
  ###############################################
*/

function debugNotice(){
    $("#debugger").fadeIn("slow");
}

function debuggerClose(){
    $("#debugger").fadeOut("slow");
}

function debuggerStep(){
}

function debuggerRun(){
    var curFullFile = window.curFullFile;
    $.post("debug.file", {curFullFile: curFullFile}); //Must pass the breakpoints too
}

function debuggerQuit(){
}

