document.addEventListener('DOMContentLoaded', function() {
    // Обработка добавления/удаления из избранного через AJAX
    document.querySelectorAll('.favorite-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const form = this.closest('form');
            const url = form.action;
            const method = form.querySelector('input[name="_method"]') ?
                          form.querySelector('input[name="_method"]').value : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Обновляем кнопку
                    const newButton = data.is_favorite ?
                        `<button type="submit" class="btn btn-outline-danger favorite-btn">
                            <i class="fas fa-heart"></i> В избранном
                        </button>` :
                        `<button type="submit" class="btn btn-outline-secondary favorite-btn">
                            <i class="far fa-heart"></i> В избранное
                        </button>`;

                    form.innerHTML = newButton;
                    form.querySelector('.favorite-btn').addEventListener('click', arguments.callee);

                    // Показываем уведомление
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-success alert-dismissible fade show';
                    alert.innerHTML = `
                        ${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;
                    document.body.prepend(alert);

                    // Удаляем уведомление через 3 секунды
                    setTimeout(() => {
                        alert.remove();
                    }, 3000);
                }
            });
        });
    });
});
