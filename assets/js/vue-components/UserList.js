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
                                v-for="(user, index) in users" :key="user.id"
                                :user="user"
                                :selectedUserByEmail="selectedUserByEmail"
                                @remove-user="removeUser" 
                                @add-skill="addSkill(user)"
                            ></user>
                            
                        </div>
                    </div>
                </div>
   
                <!-- Правая колонка - Добавление навыка/навыка -->
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
                                <button type="button" class="btn w-50" :disabled="selectedUserByEmail===''"
                                   :class="formMode==='skill' ? 'btn-primary' : 'btn-outline-secondary'" 
                                   @click="formMode='skill'">Навыки</button>
                            </div>
                            <div v-if="formMode === 'user'">
                                <h3>Пользователи</h3>
                                <div class="mb-3">
                                    <input 
                                        v-model="userName" 
                                        type="text" 
                                        class="form-control mb-1" 
                                        placeholder="Введите имя"
                                    >
                                     <input 
                                        v-model="userLastName" 
                                        type="text" 
                                        class="form-control mb-1" 
                                        placeholder="Введите фамилию"
                                    >
                                    <input 
                                        v-model="email" 
                                        type="text" 
                                        class="form-control mb-1" 
                                        placeholder="Введите email"
                                    >
                                    <button @click="addUser()" class="btn btn-primary">
                                        Добавить пользователя
                                    </button>
                                </div>
                            </div>
                            <div v-else>
                                <h3>Навыки {{ selectedUserByEmail }}</h3>
                                <div class="mb-3">
                                    <input 
                                        v-model="skill" 
                                        type="text" 
                                        class="form-control mb-1" 
                                        placeholder="Навык"
                                    >
                                    <button @click="saveSkill()" class="btn btn-primary">
                                        Добавить навык
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                 
                                      <!-- Сообщения об ошибках -->
<!--                            <div v-if="errorMessage" class="alert alert-danger alert-dismissible fade show mt-3" role="alert">-->
<!--                                <i class="fas fa-exclamation-triangle me-2"></i>-->
<!--                                <span v-text="errorMessage"></span>-->
<!--                                <button type="button" class="btn-close" @click="errorMessage = ''"></button>-->
<!--                            </div>-->       
        
            </div>
        </div>
    `,

    components: {
        'user': User
    },
    props: ['users'],
    data() {
        return {
            userName: '',
            userLastName: '',
            email: '',
            selectedUserByEmail: '',
            formMode: 'user', //user/skill
            skill: '',
        }
    },
    watch: {
        users(newUsers, oldUsers) {
            if(this.users.length === 0 ||  this.selectedUserByEmail == '') {
                this.formMode = 'user'
            }
        },
        formMode(newMode, oldMode) {
            if (newMode === 'user') {
                this.selectedUserByEmail = ''
            }
        }
    },
    methods: {
        select(email) {
            this.selectedUserByEmail = email
        },
        addUser() {
            if (this.userName.trim() !== "" && this.userLastName.trim() !== "" && this.email.trim() !== "") {
                let user = this.createNewUser(this.userName, this.userLastName, this.email)
                this.$emit('add-user', user);
                this.userName = '';
                this.userLastName = '';
                this.email = '';
            } else {
                alert('Поля не могут быть пустыми!')
            }
        },
        createNewUser(name, userLastName, email) {
            return {
                id: Date.now(),
                name: name,
                lastName: userLastName,
                email: email,
                status: 1,
                phone: "89998887766",
                roles: [
                    "admin",
                    "user",
                    "ROLE_USER"
                ],
                skills: []
            }
        },
        removeUser(email) {
            if (email === this.selectedUserByEmail) {
                this.selectedUserByEmail = ''
                this.formMode = 'skill'
            }
            this.$emit('remove-user', email);
        },
        addSkill(user) {
            this.formMode = 'skill'
            this.selectedUserByEmail = user.email
        },
        saveSkill() {
            this.$emit('add-skill-to-user', {
                userEmail: this.selectedUserByEmail,
                skill: {
                    skill: this.skill,
                    level: 100
                }
            })
            this.skill = ''
        }
    }
}