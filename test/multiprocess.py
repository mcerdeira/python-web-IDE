# -*- coding: utf-8 -*-
import threading
import time

def trabajador():
    print "Empiezo a trabajar"
    time.sleep(2)
    print "Termino de trabajar"

def main():
    print "Empieza el programa principal"
    hilo = threading.Thread(target=trabajador, args(db,))
    print "Lanzo el hilo"
    hilo.start()
    print "El hilo está lanzado"
    
    # isAlive() da false cuando el hilo termina.
    while hilo.is_alive():
        # Acá iría el código para que la aplicación
        # se vea "viva", una barra de progreso, o simplemente
        # seguir trabajando normalmente.
        print "El hilo sigue corriendo"
        # Esperamos un ratito, o hasta que termine el hilo,
        # lo que sea más corto.
        hilo.join(.3)
    print "Termina el programa"

# Importante: los módulos no deberían ejecutar
# código al ser importados
if __name__ == '__main__':
    main()