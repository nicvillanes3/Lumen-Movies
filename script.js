document.addEventListener('DOMContentLoaded', () => {
    const search = document.querySelector('.search-form input[type="search"]');
    const grid = document.querySelector('#movieGrid');

    // Filtro instantâneo no catálogo, sem recarregar a página.
    if (search && grid) {
        const cards = [...grid.querySelectorAll('.movie-card')];
        search.addEventListener('input', () => {
            const term = search.value.trim().toLocaleLowerCase('pt-BR');
            let visible = 0;
            cards.forEach(card => {
                const title = card.dataset.title || '';
                const category = card.dataset.category || '';
                const show = title.includes(term) || category.includes(term);
                card.hidden = !show;
                if (show) visible++;
            });
            let empty = grid.parentElement.querySelector('.js-empty');
            if (!visible) {
                if (!empty) {
                    empty = document.createElement('div');
                    empty.className = 'empty-state js-empty';
                    empty.innerHTML = '<span>⌕</span><h3>Nenhum título encontrado</h3><p>Tente outro nome ou categoria.</p>';
                    grid.after(empty);
                }
                empty.hidden = false;
            } else if (empty) {
                empty.hidden = true;
            }
        });
    }

    const playDemo = document.querySelector('#playDemo');
    const demoMessage = document.querySelector('#demoMessage');
    if (playDemo && demoMessage) {
        playDemo.addEventListener('click', () => {
            demoMessage.hidden = !demoMessage.hidden;
        });
    }
});
