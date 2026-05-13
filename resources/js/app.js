const API = `${window.location.origin}/api`;

document.addEventListener('DOMContentLoaded', () => {
    const tablaUsuarios = document.getElementById('tablaUsuarios');
    const tablaPartidas = document.getElementById('tablaPartidas');
    const tablaAsistencias = document.getElementById('tablaAsistencias');
    const formAsistencia = document.getElementById('formAsistencia');

    if (!tablaUsuarios && !tablaPartidas && !tablaAsistencias && !formAsistencia) {
        return;
    }

    async function fetchJson(url, options = {}) {
        const response = await fetch(url, options);

        if (!response.ok) {
            throw new Error(`Request failed with status ${response.status}`);
        }

        return response.json();
    }

    async function cargarUsuarios() {
        if (!tablaUsuarios) {
            return;
        }

        const data = await fetchJson(`${API}/usuarios`);

        tablaUsuarios.innerHTML = `
            <tr><th>ID</th><th>Nombre</th><th>Email</th></tr>
            ${data.map((u) => `
                <tr>
                    <td>${u.id}</td>
                    <td>${u.nombre}</td>
                    <td>${u.email}</td>
                </tr>
            `).join('')}
        `;
    }

    async function cargarPartidas() {
        if (!tablaPartidas) {
            return;
        }

        const data = await fetchJson(`${API}/partidas`);

        tablaPartidas.innerHTML = `
            <tr><th>ID</th><th>Título</th><th>Fecha</th><th>Lugar</th></tr>
            ${data.map((p) => `
                <tr>
                    <td>${p.id}</td>
                    <td>${p.titulo}</td>
                    <td>${p.fecha}</td>
                    <td>${p.lugar}</td>
                </tr>
            `).join('')}
        `;
    }

    async function cargarAsistencias() {
        if (!tablaAsistencias) {
            return;
        }

        const data = await fetchJson(`${API}/asistencia`);

        tablaAsistencias.innerHTML = `
            <tr><th>ID</th><th>Usuario</th><th>Partida</th><th>Estado</th></tr>
            ${data.map((a) => `
                <tr>
                    <td>${a.id}</td>
                    <td>${a.usuario?.nombre ?? '-'}</td>
                    <td>${a.partida?.titulo ?? '-'}</td>
                    <td>${a.estado ?? '-'}</td>
                </tr>
            `).join('')}
        `;
    }

    if (formAsistencia) {
        formAsistencia.addEventListener('submit', async (e) => {
            e.preventDefault();

            const body = {
                usuario_id: document.getElementById('usuario_id')?.value,
                partida_id: document.getElementById('partida_id')?.value,
                estado: document.getElementById('estado')?.value,
            };

            await fetchJson(`${API}/asistencia`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(body),
            });

            cargarAsistencias();
        });
    }

    Promise.allSettled([
        cargarUsuarios(),
        cargarPartidas(),
        cargarAsistencias(),
    ]).catch(() => {
        return;
    });
});
