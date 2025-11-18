const UserList = {
    template: `
        <div class="user-list">
            <div class="row">
                <div class="col-sm-6">
                    <div class="users">
                        <user 
                            v-for="(user, index) in users" :key="user.id"
                            :user="user"
                            :selectedUserByEmail="selectedUserByEmail"
                            @remove-user="removeUser" 
                            @add-skill="addSkill(user)"
                        ></user>
                    </div>
                </div>
                
                 <div class="col-sm-6">
                    <div class="btn-group" role="group" aria-label="Простой пример">
                        <button type="button" class="btn " 
                            :class="formMode==='user' ? 'btn-primary' : 'btn-outline-secondary'" 
                            @click="formMode='user'">Пользователи</button>
                          
                        <button type="button" class="btn" :disabled="selectedUserByEmail===''"
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
                            <button @click="addUser()" class="btn btn-success">
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
                            <button @click="saveSkill()" class="btn btn-success">
                                Добавить навык
                            </button>
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