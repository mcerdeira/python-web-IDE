# Django IDE is based on the following products
# Django: https://www.djangoproject.com
# Bottle: http://bottlepy.org
#    Ace: http://ace.ajax.org
# author: mRt: martincerdeira@gmail.com

import sys, os, subprocess, tools
from bottle import run, route, error, request, response, get, post, request, redirect, static_file, debug

PROJECT_PATH = ""  # It would be nice to implement this PROJEC_ variables, with sessions...
PROJECT_NAME = ""
debugger = None

try:
     import django
except ImportError, e:
     print e
else:
    @post('/django-ide/editor/debug.file')
    def debug_file():
        curFullFile = request.forms.get('curFullFile')
        global debugger
        # do brilliant stuff here =)

    @post('/django-ide/editor/save.file')
    def save_file():
        content = request.forms.get('content')
        curFullFile = request.forms.get('curFullFile')
        f = open(curFullFile.strip(),'w')
        f.write(content)
        f.close

    @post('/django-ide/editor/delete.file')
    def delete_file():
        curFullFile = request.forms.get('curFullFile')
        os.remove(curFullFile.strip())

    @post('/django-ide/editornew/saveas.file')
    @post('/django-ide/editor/saveas.file')
    def saveas_file():
        content = request.forms.get('content')
        newFile = request.forms.get('newFile')
        projectPath = request.forms.get('projectPath')
        curFullFile = os.path.join(projectPath.strip(), newFile.strip())
        f = open(curFullFile.strip(),'w')
        f.write(content)
        f.close

    @route('/ui/css/:filename')
    def css_file(filename):
        return static_file(filename, root=os.getcwd() + '/ui/css/')

    @route('/ui/img/:filename')
    def css_file(filename):
        return static_file(filename, root=os.getcwd() + '/ui/img/')

    @route('/ui/ace/src/:filename')
    def ace_file(filename):
        return static_file(filename, root=os.getcwd() + '/ui/ace/src/')

    @route('/ui/js/:filename')
    def js_file(filename):
        return static_file(filename, root=os.getcwd() + '/ui/js/')

    @route('/django-ide/open/:project')
    def open_project_with_name(project):
        if project:
            global PROJECT_PATH
            global PROJECT_NAME
            PROJECT_PATH = tools.get_project_path(project)
            PROJECT_NAME = project
            if not os.path.exists(PROJECT_PATH): # Somebody deleted it!
                tools.delete_project(PROJECT_NAME)
                return "The project no longer exists"
            else:
                tools.set_latest(PROJECT_NAME, PROJECT_PATH) # Increment project usage
                return tools.file_lister(PROJECT_PATH, PROJECT_NAME)

    @post('/django-ide/open/:project')
    def open_project_with_name(project):
        if project:
           global PROJECT_PATH
           global PROJECT_NAME
           action = request.forms.get('pyaction')
           if action == 'run':
               return tools.django_runserver(PROJECT_PATH, PROJECT_NAME)

    @route('/django-ide/open')
    def open_project():
        return static_file('open.html', root=os.getcwd() + '/ui/')

    @post('/django-ide/open')
    def openproject():
        path = request.forms.get('Path')
        project = tools.get_project_path_dip(path)
        open_project_with_name(project)

    @route('/django-ide/new')
    def new_project():
        return static_file('new.html', root=os.getcwd() + '/ui/')

    @route('/django-ide')
    def main_app():
        return tools.render_main(os.getcwd() + '/ui/main.html')

    @route('/django-ide/editor/:filename')
    def editor_req(filename):
        global PROJECT_PATH
        global PROJECT_NAME
        ext = os.path.splitext(filename)[1]
        buf = ''
        f = open(os.path.join(PROJECT_PATH, filename),'r')
        for l in f:
            buf += l
        f.close
        return tools.render_editor(PROJECT_PATH, filename, ext, PROJECT_NAME, buf)

    @route('/django-ide/editornew/')
    def editor_req():
        global PROJECT_PATH
        global PROJECT_NAME
        ext = '.py'
        return tools.render_editor(PROJECT_PATH, 'new file', ext, PROJECT_NAME, '')

    @post('/django-ide/new')
    def create():
        path = request.forms.get('Path')
        project = request.forms.get('Name')
        if path:
           curr_path = os.getcwd()
           django_path = os.path.join(django.__path__[0], 'bin\\django-admin.py')
           os.chdir(path)
           global PROJECT_PATH
           global PROJECT_NAME
           PROJECT_PATH = os.path.join(path, project)
           PROJECT_NAME = project
           if not os.path.exists(PROJECT_PATH): # We won't create a project, if it exists...
               subprocess.call([sys.executable, django_path, 'startproject', project])
               tools.set_project_data(PROJECT_PATH, project, curr_path)

           os.chdir(curr_path)
           tools.set_latest(PROJECT_NAME, PROJECT_PATH) # Increment project usage
           return tools.file_lister(PROJECT_PATH, PROJECT_NAME)
        else:
           return static_file('error.html', root=os.getcwd() + '/ui/')

    @error(404)
    def error_hdl(error):
        return static_file('error.html', root=os.getcwd() + '/ui/')

    def start_ide():
        #Development server
        debug(True)
        print '======================================================='
        print 'Django IDE Launched on http://localhost:8080/django-ide'
        print '======================================================='
        run(host='localhost', port=8080)

    if __name__ == '__main__':
        start_ide()