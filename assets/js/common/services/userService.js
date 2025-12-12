export const userService = {
    _error: '',

    getError() {
      return this._error
    },

    async add(user) {
        try {
            const result = await axios.post('/api/user/create', user)
            if (result.data.success) {
                return result.data.data
            }
            this._error = result.data?.errors?.[0] ?? 'Ошибка сервера'
            return []
        } catch (e) {
            this._error = this.apiErrorHandle(e, 'Ошибка при добавлении пользователя')
            return []
        }
    },

    async remove(userId) {
        try {
            const result = await axios.post(`/api/user/delete/${userId}`)
            if (result.data.success) {
                return true;
            }
            this._error = result.data?.errors?.[0] ?? 'Ошибка сервера'
        } catch (e) {
            this._error = this.apiErrorHandle(e, 'Ошибка при удалении пользователя')
        }
        return false
    },

    async loadUsers() {
        try {
            const result = await axios.get('/api/user/get-all')
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

    async attachSkill(data) {
        try {
            const response = await axios.post(`/api/user/attach-skill/${data.userId}/${data.skillName}`);
            if (response.data.success) {
                return true;
            } else {
                this.error = response.data.errors.join(', ');
            }
        } catch (error) {
            this.error = this.apiErrorHandle(error, 'Ошибка прикрепления навыка');
        }
        return false
    },

    async detachSkill(data) {
        try {
            const response = await axios.post(`/api/user/detach-skill/${data.userId}/${data.skillName}`);
            if (response.data.success) {
                return true;
            } else {
                this.error = response.data.errors.join(', ');
            }
        } catch (error) {
            this.error = this.apiErrorHandle(error, 'Ошибка открепления навыка');
        }
        return false
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
