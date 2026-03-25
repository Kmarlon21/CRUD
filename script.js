// Animações e interatividade
document.addEventListener('DOMContentLoaded', function() {
    // Adicionar efeito de fade-in nos elementos
    const elements = document.querySelectorAll('.card, .stat-card');
    elements.forEach((el, index) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        setTimeout(() => {
            el.style.transition = 'all 0.5s ease';
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }, index * 100);
    });
    
    // Confirmação de exclusão com modal centralizado
    const deleteLinks = document.querySelectorAll('.delete-link');
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.href;
            
            // Criar overlay do modal
            const modalOverlay = document.createElement('div');
            modalOverlay.className = 'modal';
            modalOverlay.innerHTML = `
                <div class="modal-content">
                    <div class="modal-header">
                        <h3><i class="fas fa-exclamation-triangle"></i> Confirmar exclusão</h3>
                        <button class="modal-close-btn">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja excluir este registro?</p>
                        <p style="color: #ef4444; margin-top: 10px;">Esta ação não pode ser desfeita!</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary modal-cancel">Cancelar</button>
                        <button class="btn btn-danger modal-confirm">Confirmar exclusão</button>
                    </div>
                </div>
            `;
            document.body.appendChild(modalOverlay);
            document.body.style.overflow = 'hidden'; // Evita rolagem do fundo
            
            // Função para fechar o modal
            const closeModal = () => {
                modalOverlay.remove();
                document.body.style.overflow = '';
                document.removeEventListener('keydown', escHandler);
            };
            
            // Eventos para fechar
            modalOverlay.querySelector('.modal-close-btn').addEventListener('click', closeModal);
            modalOverlay.querySelector('.modal-cancel').addEventListener('click', closeModal);
            modalOverlay.querySelector('.modal-confirm').addEventListener('click', () => {
                window.location.href = url;
            });
            
            // Fechar ao clicar no overlay (fora do modal)
            modalOverlay.addEventListener('click', (e) => {
                if (e.target === modalOverlay) closeModal();
            });
            
            // Fechar com tecla ESC
            const escHandler = (e) => {
                if (e.key === 'Escape') closeModal();
            };
            document.addEventListener('keydown', escHandler);
        });
    });
    
    // Filtro de busca em tempo real
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const searchText = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('.table tbody tr');
            
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchText) ? '' : 'none';
            });
        });
    }
    
    // Tooltips
    const tooltips = document.querySelectorAll('[data-tooltip]');
    tooltips.forEach(element => {
        element.addEventListener('mouseenter', function(e) {
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.textContent = this.dataset.tooltip;
            tooltip.style.position = 'absolute';
            tooltip.style.background = '#1f2937';
            tooltip.style.color = 'white';
            tooltip.style.padding = '5px 10px';
            tooltip.style.borderRadius = '6px';
            tooltip.style.fontSize = '12px';
            tooltip.style.pointerEvents = 'none';
            tooltip.style.zIndex = '1000';
            
            document.body.appendChild(tooltip);
            
            const rect = this.getBoundingClientRect();
            tooltip.style.left = rect.left + 'px';
            tooltip.style.top = (rect.top - 30) + 'px';
            
            this.tooltip = tooltip;
        });
        
        element.addEventListener('mouseleave', function() {
            if (this.tooltip) {
                this.tooltip.remove();
            }
        });
    });
});

// Função para mostrar notificações
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type}`;
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '1000';
    notification.style.maxWidth = '300px';
    notification.style.animation = 'slideIn 0.3s ease-out';
    notification.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${message}`;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

// Função para carregar dados via AJAX
async function loadUserData(userId) {
    try {
        const response = await fetch(`api.php?id=${userId}`);
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Erro:', error);
        showNotification('Erro ao carregar dados', 'danger');
    }
}