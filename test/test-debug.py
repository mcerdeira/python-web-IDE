import debugger
import multiprocessing

def doit():
    db = debugger.Debugger()
    db.debug("1.py")
    db.command("s")

hilo = multiprocessing.Process(target = doit)
hilo.start()

print "paso por aca!"

# NO ANDA