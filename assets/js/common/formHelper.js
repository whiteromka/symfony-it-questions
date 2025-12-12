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
