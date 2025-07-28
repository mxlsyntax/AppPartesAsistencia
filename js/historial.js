class Historial {
    constructor(cdappfijo) {
        this.pila = this.obtenerHistorial(cdappfijo) || [];
    }

    push(entrada) {
        this.pila.push(entrada);
        this.guardarHistorial(cdappfijo);
        console.log('Historial actualizado:', this.pila);
    }

    pop() {
        if (this.pila.length > 0) {
            const entrada = this.pila.pop();
            this.guardarHistorial(cdappfijo);
            console.log('Última entrada eliminada:', entrada);
        } else {
            console.log('No hay más entradas en el historial.');
        }
    }

    top() {
        return this.pila.length > 0 ? this.pila[this.pila.length - 1] : null;
    }

    prev() {
        return this.pila.length > 1 ? this.pila[this.pila.length - 2] : null;
    }

    mostrarHistorial() {
        return this.pila;
    }

    guardarHistorial(cdappfijo) {
        localStorage.setItem('miHistorial' + cdappfijo, JSON.stringify(this.pila));
    }

    obtenerHistorial(cdappfijo) {
        const historialGuardado = localStorage.getItem('miHistorial' + cdappfijo);
        return historialGuardado ? JSON.parse(historialGuardado) : null;
    }
}

// Exportar la clase para que esté disponible en otros archivos
window.Historial = Historial;