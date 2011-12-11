import multiprocessing
import time

def trabajador():
    b = 0
    print "Empiezo a trabajar"
    while True:
        b += 1
        if b == 100000000:
           return
    print "Termino de trabajar"

def main():
    print "Empieza el programa principal"
    hilo = multiprocessing.Process(target=trabajador)
    print "Lanzo el hilo"
    hilo.start()
    print "El hilo esta lanzado"

    while hilo.is_alive():
        # Aca iria el codigo para que la aplicacion
        # se vea "viva", una barra de progreso, o simplemente
        # seguir trabajando normalmente.
        print "El hilo sigue corriendo"
        # Esperamos un ratito, o hasta que termine el hilo,
        # lo que sea mas corto.
        hilo.join(.3)
    print "Termina el programa"

if __name__ == '__main__':
    main()