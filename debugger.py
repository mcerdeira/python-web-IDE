""" So, you call yourself a debugger?"""

import sys
import linecache

class Debugger:
    """ A tiny debugger class for multipourpose
    This class is originally developed for integrating with Django-IDE
    """
    def __init__(self):
        self.breaks = {} # filename, lineno (with optional condition)
        self.cur_line = -1 # current debugged line number
        self.cur_file = None # current debugged file name
        self.debug_file = None # main debugger file
        self.started = False # is the debugger running?
        self.command = "" # command interface
        self.supported_commands = ['s', 'r', 'q'] # Step, Run, Quit: supported commands =)

    def set_breakpoint(filename, lineno, condition = None):
        self.breaks{filename} = None # ToDo

    def debug(self, debug_file):
        #import __main__
        #print __main__.__dict__
        #globals = __main__.__dict__
        #locals = globals
        self.debug_file = debug_file
        sys.settrace(self.traceit)
        statement = 'execfile( "%s")' % self.debug_file
        exec statement

    def _filter(self, filename):
        # Dirty hack for not debugging these threading related files
        # waiting for a better solution...
        if self.command == 'q':
            sys.exit(0)
            return True
        elif self.command == 'r':
            return True
        else:
            self.command = ''

        if 'threading.py' in filename:
            return True
        else:
            return False

    def traceit(self, frame, event, arg):
        if self._filter(frame.f_globals["__file__"]):
           return
        if event == "line" or event == 'call':
            if frame.f_globals["__file__"] != __file__:
               filename = frame.f_globals["__file__"]
            else:
               filename = self.debug_file
            if filename.endswith(".pyc") or filename.endswith(".pyo"):
                filename = filename[:-1]
            lineno = frame.f_lineno
            line = linecache.getline(filename, lineno)
            self.cur_file = filename
            self.cur_line = lineno
            print "%s: %s" % (lineno, line.rstrip())
            self.dispatch_line(frame)
        return self.traceit

    def dispatch_line(self, frame):
        self.user_line(frame)
        return self.traceit

    def user_line(self, frame):
        self.wait()

    def command_if(self, command):
        self.command = command

    def wait(self):
        while True:
            if self.command in self.supported_commands:
                return