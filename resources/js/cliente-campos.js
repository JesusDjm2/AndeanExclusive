// Campos de cliente (clientes/partials/campos): "Agencia / empresa" solo se muestra para agencias.
export function initClienteCampos() {
    document.querySelectorAll('[data-cliente-campos]').forEach((group) => {
        const tipo = group.querySelector('[data-cliente-tipo]');
        const empresa = group.querySelector('[data-cliente-empresa]');
        if (!tipo || !empresa) return;

        const sync = () => { empresa.hidden = tipo.value !== 'agencia'; };
        tipo.addEventListener('change', sync);
        sync();
    });
}
