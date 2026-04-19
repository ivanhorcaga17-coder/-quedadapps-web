<div id="popup"
     class="hidden fixed inset-0 bg-black/40 flex justify-center items-center z-50">

    <div class="bg-white p-8 rounded-xl w-[400px]">

        <h2 class="text-2xl font-bold mb-4">Añadir jugadores:</h2>

        <!-- BUSCADOR -->
        <input type="text" placeholder="Buscar jugador..."
               class="w-full p-3 border rounded mb-4">

        <!-- BOTÓN AÑADIR -->
        <button class="w-full bg-black text-white py-2 rounded-lg mb-4">
            Añadir jugador
        </button>

        <!-- LISTA -->
        <div class="border p-4 rounded-lg">
            <p class="font-semibold mb-2">Jugadores:</p>

            <ul id="lista-jugadores" class="space-y-2">
                <!-- Aquí se añadirán jugadores -->
            </ul>
        </div>

        <!-- CERRAR -->
        <button onclick="cerrarPopup()"
                class="mt-6 w-full bg-gray-200 py-2 rounded-lg hover:bg-gray-300">
            Cerrar
        </button>

    </div>

</div>
