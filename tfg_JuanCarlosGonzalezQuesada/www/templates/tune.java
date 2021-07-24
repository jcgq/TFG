public class ControlAccesoTunel {
private  boolean TunelLleno = false;
private boolean TunelVacio = true;
	private boolean Sent = true;
	private int cochesS1 = 0;
	private int cochesS2 = 0;
	private int finaly MaxCochPer= 8;
	ConditionVariable ListoParaEntrar = new ConditionVariable();
	ConditionVariable ListoParaSalir = new ConditionVariable();

public synchronized int EntradaTunel() throws InterruptedException {

If (this.Sent) {    //Sentido = True  S1
   	   synchronized (ListoParaEntrar)  //bloquea la variable de condición
	   {
	     synchronized (this)   //bloquea el monitor
	     {

\\ Si el túnel está lleno o hay coches en sentido contrario, hay que poner a esperar ListoParaEntrar
If (this.CochesS2>0 | this.TunelLLeno) {
 		ListoParaEntrar.AEsperar = true;
} else
{
ListoParaEntrar.AEsperar = false;
}
	     } //libera el monitor
     If (ListoParaEntrar.AEsperar) ListoParaEntrar.wait();
    }
    this.CochesS1++;
    this.TunelLleno = this.CochesS1== this.MaxCochPer;
    this.TunelVacio = false;
    ListoParaSalir.NotifyAll();
    return this.CochesS1;
else  {  // S2
  	 synchronized (ListoParaEntrar)  //bloquea la variable de condición
	   {
	     synchronized (this)   //bloquea el monitor
	     {
\\ Si el túnel está lleno o hay coches en sentido contrario, hay que poner a esperar ListoParaEntrar
If (this.CochesS1>0 | this.TunelLLeno) {
 			ListoParaEntrar.AEsperar = True;
} else
{
ListoParaEntrar.AEsperar = false;
}
		} //desbloquea el monitor
If (ListoParaEntrar.AEsperar) ListoParaEntrar.wait();
		this.CochesS2++;
		this.TunelLleno = this.CochesS2== this.MaxCochPer;
		this.TunelVacio = false;
ListoParaSalir.NotifyAll();
return this.CochesS2;
		}
	     }
	   }
	}  // fin de EntradaTunel

	public int SalidaTunel() throws InterruptedException{
\\ Si el túnel está vacío, no pueden salir coches, pongo a esperar ListoParaSalir
  	 synchronized (ListoParaSalir)  //bloquea la variable de condición
	   {
	     synchronized (this)   //bloquea el monitor
	     {

  If (this.TunelVacio) {
 			ListoParaSalir.AEsperar = True;
  } else
  {
ListoParaSalir.AEsperar = false;
  }
		} // desbloquea el monitor
If (ListoParaSalir.AEsperar) ListoParaSalir.wait();
	    }
		If (this.Sent) { // S1
			this.CochesS1 --;
			this.TunelVacio = this.CochesS1==0;
			this.TunelLleno = false;
ListoParaEntrar.NotifyAll();
return this.cochesS1;
		}
		else { // S2
			this.CochesS2--;
			this.TunelVacio = this.CochesS2==0;
			this.TunelLleno = false;
ListoParaEntrar.NotifyAll();
return this.cochesS2;
		}
	}  //fin de SalidaTunel
	\\ Clase para la variable de condición
	public class ConditionVariable {
		public boolean AEsperar =false;
	}

	public  static void main (string args[]){
		ControlAccesoTunel   Acc_Tunel = new ControlAccesoTunel();
		Entrada entradaT = new Entrada (Acc_Tunel);
		Salida salidaT = new Salida (Acc_Tunel);
		entradaT.start();
		salidaT.start();
	}  //fin de main
}  // fin clase principal ControlAccesoTunel

public class Entrada extend Thread {
 	ControlAccesoTunel  AccesoTunelEntrada;
private  int CochesEntrado;
public Entrada (ControlAccesoTunel  AccTunel ){
		this. AccesoTunelEntrada = AccTunel;
		}
	public void run(){
while (true) {
    try {
				thread.sleep (10000);
				CochesEntrado= AccesoTunelEntrada.EntradaTunel();
System.out.println (“El número de vehículos que han entrado es: ” + CochesEntrado);
			    } catch (InterruptedException ie) { }
		}
	}
} //fin  de clase entrada

public class Salida extend thread {
 	ControlAccesoTunel  AccesoTunelSalida;
private  int CochesSalido;
	public Salida (ControlAccesoTunel  AccTunel){
		this. AccesoTunelSalida  = AccTunel;
		}
	public void run(){
while (true) {
			try {
				thread.sleep (10000);
				CochesSalido= AccesoTunelSalida.SalidaTunel()
System.out.println (“El número de vehículos que han salido es: ” + CochesSalido);
			      } catch (InterruptedException ie) { }
		}
	}
}// fin de clase salida

