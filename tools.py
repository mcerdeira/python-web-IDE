# All pourpose dirty, ugly tools and stuff

import os, sys, sqlite3
import shutil, subprocess, webbrowser, time


##############################
######### CLASSES ############
##############################

class BreakPoints():
    def __init__(self):
        self.breaks = {}

    def toggle_line(self, filename, lineno):
        """ manages adds and finds break points and files that owns them
        brk_obj: a dictionary
        filename: the name of the file, serves as the dictionary key
        lineno: the line number to toggle
        """
        if filename not in self.breaks.keys():
           self.breaks[filename] = [lineno]
        else:
           if lineno not in self.breaks[filename]:
               self.breaks[filename].append(lineno)
           else:
               self.breaks[filename].remove(lineno)


##############################
######### FUNCTIONS ##########
##############################

def add_doubledash(fname):
    """ turns slashes into double slashes
    in order to pass to javascript,and back
    """
    return fname.replace("\\","\\\\")

def filesize(num):
    for x in ['bytes','KB','MB','GB','TB']:
        if num < 1024.0:
           return "%3.1f%s" % (num, x)
        num /= 1024.0


def findexts (f):
    """ finds extention of file
    """
    return os.path.splitext(f)[1]


def render_main(mfile):
    retvalue = ''
    f = open(mfile, 'r')
    for l in f:
        retvalue += l
    f.close()

    retvalue += get_latest()
    retvalue += '''</body>
                 </head>'''
    return retvalue

def file_lister(root, project):
    retvalue = """
    <!DOCTYPE html>
    <html>
    <head>
       <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
       <title>Django IDE: """ + project + """</title>
       <link href="/ui/css/base.css" rel="stylesheet" type="text/css" media="screen" />
       <link href="/ui/css/print.css" rel="stylesheet" type="text/css" media="print" />
       <link rel="stylesheet" href="/ui/css/pygments.css" type="text/css" />
       <link rel="shortcut icon" href="/ui/img/favicon.ico" />
    </head>

    <body id="documentation" class="default">
    <div id="container">
    <div id="header">
      <h1 id="logo"><a href="/django-ide"><img src="/ui/img/hdr_logo.gif" alt="Django-IDE"/></a></h1>
      <ul id="nav-global">
        <li id="nav-open"><a href="/django-ide/open">Open Project</a></li>
        <li id="nav-new"><a href="/django-ide/new">New Project</a></li>
        <li id="nav-docs"><a href="/django-ide/docs">Documentation</a></li>
      </ul>
    </div>
    <div id="billboard">
   <h2><a></a></h2>
  </div>


       <div class="section">
    """

    # opens this directory
    dirArray = os.listdir(root)

    #  counts elements in array
    indexCount = len(dirArray);

    # sorts files
    dirArray.sort()

    # print 'em
    retvalue += """<table width='100%' cellspacing='10'>
                 <tr>
                   <td class='head'>Filename</td>
                   <td class='head'>Type</td>
                   <td class='head'>Size</td></tr>"""

    # loops through the array of files and print them all
    for f in dirArray:
        extension = findexts(f)
        if not extension.endswith(".pyc") and not extension.endswith(".pyo"):
            retvalue += """<tr><td><a href='""" + """/django-ide/editor/""" + f + """'>""" + f + """</a></td>"""
            retvalue += """<td>"""
            retvalue += extension
            retvalue += """</td>"""
            retvalue += """<td>"""
            retvalue += filesize(os.path.getsize(os.path.join(root, f)))
            retvalue += """</td>"""
            retvalue += """</tr>"""

    retvalue += """<tr><td><a href='""" + """/django-ide/editornew/'>add a new file</a></td>"""
    retvalue += """<td>"""
    retvalue += """</td>"""
    retvalue += """<td>"""
    retvalue += """</td>"""
    retvalue += """</tr>"""

    retvalue += """</table>"""
    retvalue += """
                       </div>
                           <form method='post'>
                               <br>
                               <input type="hidden" name="pyaction" id="pyaction" value="">
                               <input type="submit" onclick="document.getElementById('pyaction').value='run'" value="Run Project"/>
                           </form>
                       </body>
                       </html>
                    """
    return retvalue

def extension_man(filext):
    print filext
    if filext == '.py':
       return 'python'
    elif filext == '.js':
       return 'javascript'
    elif filext == '.php':
       return 'php'
    else:
       return ''

def div_events():
    """ Sets div doble click events
    """
    retVal = """
    <script>
    $('div.ace_gutter-cell').live("dblclick",function() {
        var line_no = $(this).text();
        // Draw the break point
        if ($(this).attr("class")=="ace_gutter-cell"){
            $(this).attr("class", "ace_gutter-cell ace_error");
        }else{
            $(this).attr("class", "ace_gutter-cell");
        }
        // Pass the lineno to the pre-debugger
        $.post("add.breakpoint", {line_no: line_no});
    });
    </script>
    """
    return retVal

def render_editor(project_path, filename, filext, project, filebuf):
    if filebuf:
       save_cmd = "saveNotice()"
    else:
       # If the file content is empty, save() must be saveAs()
       save_cmd = "saveasNotice()"

    retvalue = """ <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
      <title>""" + project + """ : """ + filename + """</title>
      <link rel="shortcut icon" href="/ui/img/favicon.ico" />
      <link href="/ui/css/toolbar.css" rel="stylesheet" type="text/css" />
      <script src="/ui/js/jquery.min.js"></script>
      <script src="/ui/js/toolbar.js"></script>"""
    retvalue += """<div id="message" style="display: none;">
       <a href="/django-ide/open/""" + project + """" class="close-notify">Project</a>
       <a href="/django-ide/editornew/" class="close-notify">New File</a>
       <a href="#" class="close-notify" onclick=" """ + save_cmd + """ ">Save</a>
       <a href="#" class="close-notify" onclick="saveasNotice()">Save As</a>
       <a href="#" class="close-notify" onclick="deleteNotice()">Delete</a>"""

    if filext == '.py': #Debbuger only for .py files!
        retvalue += """<a href="#" class="close-notify" onclick="debugNotice()">Debug</a>
          </div>
          <div id="debugger" style="display: none;">
          <a href="#" class="close-notify" onclick="debuggerStep()">Step</a>
          <a href="#" class="close-notify" onclick="debuggerRun()">Run</a>
          <a href="#" class="close-notify" onclick="debuggerQuit()">Quit</a>
          <a href="#" class="close-notify" onclick="debuggerClose()">X</a>"""

    retvalue += """</div>"""
    retvalue += """<style type="text/css" media="screen">
        body {
            overflow: hidden;
        }
        #editor {
            margin: 0;
            position: absolute;
            top: 35px;
            bottom: 0;
            left: 0;
            right: 0;
        }
      </style>
    </head>
    <body>

    <pre id="editor">"""
    retvalue += filebuf
    retvalue += """</pre>"""

    retvalue += """
    <script src="/ui/ace/src/ace-uncompressed.js" type="text/javascript" charset="utf-8"></script>
    <script src="/ui/ace/src/theme-crimson_editor.js" type="text/javascript" charset="utf-8"></script>
    <script src="/ui/ace/src/mode-""" + extension_man(filext) + """.js" type="text/javascript" charset="utf-8"></script>

    <script>
    window.onload = function() {
        window.aceEditor = ace.edit("editor");
        window.curFullFile = " """ + add_doubledash(os.path.join(project_path, filename)) + """ ";
        window.curFile = " """ + add_doubledash(os.path.join(filename)) + """ ";
        window.projectPath = " """ + add_doubledash(project_path) + """ ";
        window.projectName = " """ + project.strip() + """ ";

        var editor = window.aceEditor;
        editor.setTheme("ace/theme/crimson_editor");
        var EditorMode = require("ace/mode/""" + extension_man(filext) + """").Mode;
        editor.getSession().setMode(new EditorMode());
    };
    </script>"""
    
    retvalue += div_events()

    retvalue += """
    </body>
    </html>
    """
    return retvalue


# SQL Lite related functions

def get_latest():
    # Get the latest used projects, and returns the list, html formated
    conn = get_conn()
    c = conn.cursor()
    html = ''
    c.execute('''select name from latest order by qtty''')
    buf = ''
    for row in c:
        if row[0]:
            buf += '''<a href="/django-ide/open/''' + row[0] + '''">''' + row[0] + '''</a>'''
            buf += '''<br>'''
    c.close()
    if buf:
       buf = '''<h2>Latest Projects<a class="headerlink"></a></h2>''' + buf

    return html + buf

def set_latest(project, path):
    # Set the project as a project used
    conn = get_conn()
    c = conn.cursor()
    c.execute("""select qtty from latest where name='""" + project + """'""")
    qtty = 0
    for row in c:
        qtty = row[0]

    if qtty == 0 or qtty is None:
        c.execute("""insert into latest values('""" + project + """','""" + path + """',1)""")
    else:
        c.execute("""update latest set qtty=qtty+1 where name='""" + project + """'""")

    conn.commit()
    c.close()

def get_conn():
    if not os.path.exists(os.getcwd() + '/ui/data/projects.db'):
        conn = sqlite3.connect(os.getcwd() + '/ui/data/projects.db')
        c = conn.cursor()
        c.execute('''create table latest (name text, path text, qtty real)''')
        conn.commit()
        c.close()
    else:
        conn = sqlite3.connect(os.getcwd() + '/ui/data/projects.db')
    return conn

def get_project_path(project):
    conn = get_conn()
    c = conn.cursor()
    c.execute("""select path from latest where name='""" + project + """'""")
    for row in c:
        retval = row[0]
    c.close()
    return retval

def delete_project(project):
    conn = get_conn()
    c = conn.cursor()
    c.execute("""delete from latest where name='""" + project + """'""")
    conn.commit()
    c.close()

def get_project_path_dip(project):
    print project
    f = open(project, 'r')
    for l in f:
        if l.startswith('NAME: '):
            return l.replace("NAME: ", "")

def set_project_data(path, project,ide_path):
    # Adds the project file and the debugger code
    dpname = project + '.dip'
    f = open(os.path.join(path, dpname), 'w')
    f.write('This is a project marker file for Django-IDE, do not erase it! \n')
    f.write('PATH: ' + path + '\n')
    f.write('NAME: ' + project + '\n')
    f.close()
    # Copy debugger file
    shutil.copy2(os.path.join(ide_path, "debugger.py"), os.path.join(path, "debugger.py"))

def launch_debugger(filename, main_func):
    # Launchs a debugging session
    output = "import " + filename[:-3] + "\n"
    output += "import debugger \n"
    output += "db = debugger.Debugger()"
    output += "db.debug(" + filename + ")"
    output += filename[:-1] + "." + main_func

def run_django_run(project_name):
    retValue = """
        <!DOCTYPE html>
          <html>
          <head>
             <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
             <title>Django IDE: """ + project_name + """</title>
             <link href="/ui/css/base.css" rel="stylesheet" type="text/css" media="screen" />
             <link href="/ui/css/print.css" rel="stylesheet" type="text/css" media="print" />
             <link rel="stylesheet" href="/ui/css/pygments.css" type="text/css" />
             <link rel="shortcut icon" href="/ui/img/favicon.ico" />
          </head>
          <body id="documentation" class="default">
          <div id="container">
          <div id="header">
            <h1 id="logo"><a href="/django-ide"><img src="/ui/img/hdr_logo.gif" alt="Django-IDE"/></a></h1>
            <ul id="nav-global">
              <li id="nav-open"><a href="/django-ide/open">Open Project</a></li>
              <li id="nav-new"><a href="/django-ide/new">New Project</a></li>
              <li id="nav-docs"><a href="/django-ide/docs">Documentation</a></li>
            </ul>
          </div>
          <div id="billboard">
              <h2><a></a></h2>
          </div>
          <div id="columnwrap">
          <div id="content-main">
          <div class="section">
          <p>Running... on <a href="http://localhost:8000">http://localhost:8000</a></p>
    """
    return retValue

def django_runserver(project_path, project_name):
    curr_path = os.getcwd()
    pid = subprocess.Popen([sys.executable, os.path.join(project_path, 'manage.py'), 'runserver', '8000']).pid
    os.chdir(curr_path)
    return run_django_run(project_name)