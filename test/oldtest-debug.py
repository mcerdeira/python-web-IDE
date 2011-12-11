import sys
import linecache

def traceit(frame, event, arg):
    if event == "line":
        if frame.f_globals["__file__"] != __file__:
           filename = frame.f_globals["__file__"]
        else:
           filename = debug_file
        if (filename.endswith(".pyc") or
            filename.endswith(".pyo")):
            filename = filename[:-1]

        lineno = frame.f_lineno
        line = linecache.getline(filename, lineno)
        print "%s: %s" % (lineno, line.rstrip())
        dispatch_line(frame)
    return traceit

def dispatch_line(frame):
    user_line(frame)
    return traceit

def user_line(frame):
    while 1:
        b = raw_input(">:")
        if b == "s":
           break

import __main__
globals = __main__.__dict__
locals = globals

debug_file = "1.py"
sys.settrace(traceit)
statement = 'execfile( "%s")' % debug_file

exec statement in globals, locals