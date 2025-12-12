export const skillService = {
    _error: '',

    getError() {
      return  this._error
    },

    async add(skill) {
        try {
            const result = await axios.post('/api/skill/create', skill)
            if (result.data.success) {
                return true
            }
            this._error = result.data?.errors?.[0] ?? 'Ошибка сервера'
            return false
        } catch (e) {
            this._error = this.apiErrorHandle(e, 'Ошибка при добавлении навыка')
            return false
        }
    },

    async remove(skillName) {
        try {
            const result = await axios.post(`/api/skill/delete/${skillName}`)
            if (result.data.success) {
                return true;
            }
            this._error = result.data?.errors?.[0] ?? 'Ошибка сервера'
            return false
        } catch (e) {
            this._error = this.apiErrorHandle(e, 'Ошибка при удалении навыка')
            return false
        }
    },

    async loadSkills() {
        try {
            const result = await axios.get('/api/skill/get-all')
            if (result.data.success) {
                return result.data.data
            }
            this._error = result.data.errors?.[0] ?? 'Ошибка сервера'
            return []
        } catch (e) {
            this._error = this.apiErrorHandle(e, 'Ошибка при удалении навыка')
            return []
        }
    },

    apiErrorHandle(error, defaultMessage = 'Произошла ошибка') {
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
}
