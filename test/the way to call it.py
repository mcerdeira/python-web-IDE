import debugger
import time
import threading

def lolo(db):
    print "Inicio debugger en ", db
    db.debug("uno.py")

def lala():
    db = debugger.Debugger()
    print db
    hilo = threading.Thread(target=lolo, args=(db,))
    hilo.start()
    print "lanzo"
    while hilo.isAlive():
        #time.sleep(5)
        c  = raw_input("Comando: \n")
        db.command_if(c)
        hilo.join(.3)

if __name__ == '__main__':
   lala()