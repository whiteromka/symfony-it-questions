const UserList = {
    template: `
        <div class="user-list">
            <div class="row">
                <!-- Левая колонка - Список пол-ей -->
                <div class="col-lg-7 mb-4">
                    <div class="card shadow-sm skill-card">
                        <div class="card-header bg-warning d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-users text-dark me-2"></i>
                                Список пользователей
                            </h5>
                            <span class="badge bg-dark text-white rounded-pill" v-text="users.length"></span>
                        </div>
                        <div class="card-body">
                            <user 
                                v-for="user in users" :key="user.id"
                                :user="user"
                                :selected-user="selectedUser"
                                @remove-user="removeUser" 
                                @add-skill="wantAddSkill(user)"
                                @detach-skill="detachSkill"
                            ></user>
                        </div>
                    </div>
                </div>
   
                <!-- Правая колонка - Добавление пользователя/навыка -->
                <div class="col-lg-5 mb-4">
                    <div class="card shadow-sm skill-form">
                        <div class="card-header bg-warning">
                            <h5 class="card-title mb-0">
                                Добавить пользователя/навык
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="btn-group w-100" role="group">
                                <button type="button" class="btn w-50" 
                                    :class="formMode==='user' ? 'btn-primary' : 'btn-outline-secondary'" 
                                    @click="formMode='user'">Пользователи</button>
                                <button type="button" class="btn w-50" :disabled="!selectedUser"
                                   :class="formMode==='skill' ? 'btn-primary' : 'btn-outline-secondary'" 
                                   @click="formMode='skill'">Навыки</button>
                            </div>
                            
                            <!-- Форма добавления пользователя -->
                            <div v-if="formMode === 'user'" class="mt-3">
                                <h6>Добавить пользователя</h6>
                                <div class="mb-2">
                                    <input 
                                        v-model="newUser.name" 
                                        type="text" 
                                        class="form-control mb-2" 
                                        placeholder="Имя"
                                        required
                                    >
                                    <input 
                                        v-model="newUser.lastName" 
                                        type="text" 
                                        class="form-control mb-2" 
                                        placeholder="Фамилия"
                                    >
                                    <input 
                                        v-model="newUser.email" 
                                        type="email" 
                                        class="form-control mb-2" 
                                        placeholder="Email"
                                        required
                                    >
                                    <input 
                                        v-model="newUser.phone" 
                                        type="text" 
                                        class="form-control mb-2" 
                                        placeholder="Телефон"
                                    >
                                    <button @click="createUser()" class="btn btn-success w-100" :disabled="!isUserFormValid">
                                        Создать пользователя
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Форма работы с навыками -->
                            <div v-else class="mt-3">
                                <h6>Навыки для {{ selectedUser?.name }}</h6>
                                
                                <!-- Создание нового навыка -->
                                <div class="mb-3">
                                    <input 
                                        v-model="newSkill.name" 
                                        type="text" 
                                        class="form-control mb-2" 
                                        placeholder="Название навыка"
                                    >
                                    <textarea
                                        v-model="newSkill.descr"
                                        class="form-control mb-2"
                                        placeholder="Описание навыка"
                                        rows="2"
                                    ></textarea>
                                    <button @click="createSkill()" class="btn btn-success w-100" :disabled="!newSkill.name">
                                        Создать навык
                                    </button>
                                </div>
                                
                                <!-- Прикрепление существующего навыка -->
                                <div class="mb-3">
                                    <select v-model="selectedSkillId" class="form-select mb-2">
                                        <option value="">Выберите навык</option>
                                        <option v-for="skill in availableSkills" :key="skill.id" :value="skill.id">
                                            {{ skill.name }}
                                        </option>
                                    </select>
                                    <button @click="attachSkill()" class="btn btn-primary w-100" 
                                            :disabled="!selectedSkillId">
                                        Прикрепить навык
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Сообщения об ошибках -->
                            <div v-if="errorMessage" class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <span v-text="errorMessage"></span>
                                <button type="button" class="btn-close" @click="errorMessage = ''"></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `,

    components: {
        'user': User
    },
    props: ['users'],
    data() {
        return {
            selectedUser: null,
            formMode: 'user',
            newUser: {
                name: '',
                lastName: '',
                email: '',
                phone: '',
                status: 1,
                roles: ['ROLE_USER']
            },
            newSkill: {
                name: '',
                descr: ''
            },
            availableSkills: [],
            selectedSkillId: '',
            errorMessage: ''
        }
    },
    computed: {
        isUserFormValid() {
            return this.newUser.name.trim() && this.newUser.email.trim();
        }
    },
    watch: {
        formMode(newMode) {
            if (newMode === 'skill' && this.selectedUser) {
                this.loadAvailableSkills();
            }
        }
    },
    methods: {
        async loadAvailableSkills() {
            try {
                const response = await axios.get('/api/skill/get-all');
                this.availableSkills = response.data.data;
            } catch (error) {
                this.errorMessage = 'Ошибка загрузки списка навыков';
            }
        },

        async createUser() {
            try {
                const response = await axios.post('/api/user/create', this.newUser);
                if (response.data.success) {
                    this.$emit('add-user', response.data.data);
                    this.newUser = { name: '', lastName: '', email: '', phone: '', status: 1, roles: ['ROLE_USER'] };
                    this.errorMessage = '';
                } else {
                    this.errorMessage = response.data.errors.join(', ');
                }
            } catch (error) {
                this.errorMessage = this.apiErrorHandle(error, 'Ошибка создания пользователя');
            }
        },

        async createSkill() {
            try {
                const response = await axios.post('/api/skill/create', this.newSkill);
                if (response.data.success) {
                    await this.loadAvailableSkills();
                    this.newSkill = { name: '', descr: '' };
                    this.errorMessage = '';
                } else {
                    this.errorMessage = response.data.errors.join(', ');
                }
            } catch (error) {
                this.errorMessage = this.apiErrorHandle(error, 'Ошибка создания навыка');
            }
        },

        async attachSkill() {
            if (!this.selectedUser || !this.selectedSkillId) return;

            try {
                const response = await axios.post(`/api/user/attach-skill/${this.selectedUser.id}/${this.selectedSkillId}`);
                if (response.data.success) {
                    this.$emit('attach-skill', response.data.data);
                    this.selectedSkillId = '';
                    this.errorMessage = '';
                } else {
                    this.errorMessage = response.data.errors.join(', ');
                }
            } catch (error) {
                this.errorMessage = this.apiErrorHandle(error, 'Ошибка прикрепления навыка');
            }
        },

        wantAddSkill(user) {
            this.formMode = 'skill';
            this.selectedUser = user;
        },

        removeUser(userId) {
            this.$emit('remove-user', userId);
            if (this.selectedUser && this.selectedUser.id === userId) {
                this.selectedUser = null;
                this.formMode = 'user';
            }
        },

        detachSkill(data) {
            this.$emit('detach-skill', data);
        },

        apiErrorHandle(error, defaultMessage) {
            if (error.response && error.response.data && error.response.data.errors) {
                return error.response.data.errors.join(', ');
            }
            return defaultMessage;
        }
    }
}