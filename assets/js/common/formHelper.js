// Универсальная функция обработки ошибок API
export function apiErrorHandle(error, defaultMessage = 'Произошла ошибка') {
    if (error.response?.data?.errors) {
        let errors = error.response.data.errors || {}
        let message = Object.entries(errors)
            .map(([k, v]) => isNaN(k) ? `${k}: ${v}` : v)
            .join(', ');
        return `Ошибка сервера: ${message} (${error.response.status})`;
    } else if (error.response) {
        return `Ошибка сервера: ${error.response.status} ${error.response.statusText || ''}`.trim();
    } else if (error.request) {
        return 'Ошибка сети: нет ответа от сервера';
    } else {
        return `${defaultMessage}: ${error.message}`;
    }
}

// Поиск csrf tokena
export function getCsrfToken() {
    let metaTag = document.querySelector('meta[name="csrf-token"]');
    if (metaTag) {
        return metaTag.getAttribute('content');
    }

    let tokenInput = document.querySelector('input[name="_token"]');
    if (tokenInput) {
        return tokenInput.value;
    }

    console.error('Ошибка! Не найден CSRF токен на странице');
    return '';
}
