const UserList = {
    template: `
        <div class="user-list">
            <div class="row">
                <div class="col-sm-6">
                    <!--  -->
                    <div class="users">
                        <user 
                            v-for="(user, index) in users" :key="user.id"
                            :user="user"
                            :selectedUserByEmail="selectedUserByEmail"
                            @remove-user="removeUser" 
                            @add-estate="addEstate(user)"
                        ></user>
                    </div>
                </div>
                
                 <div class="col-sm-6">
                    <div class="btn-group" role="group" aria-label="Простой пример">
                        <button type="button" class="btn " 
                            :class="formMode==='user' ? 'btn-primary' : 'btn-outline-secondary'" 
                            @click="formMode='user'">Пользователи</button>
                          
                        <button type="button" class="btn" :disabled="selectedUserByEmail===''"
                           :class="formMode==='estate' ? 'btn-primary' : 'btn-outline-secondary'" 
                           @click="formMode='estate'">Имущество</button>
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
                        <h3>Имущество {{ selectedUserByEmail }}</h3>
                        <div class="mb-3">
                            <input 
                                v-model="estate" 
                                type="text" 
                                class="form-control mb-1" 
                                placeholder="Имущество"
                            >
                             <input 
                                v-model="cost" 
                                type="text" 
                                class="form-control mb-1" 
                                placeholder="Стоимость"
                            >
                            <button @click="saveEstate()" class="btn btn-success">
                                Добавить имущество
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
            formMode: 'user', //user/estate
            estate: '',
            cost: 0,
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
                estates: []
            }
        },
        removeUser(email) {
            if (email === this.selectedUserByEmail) {
                this.selectedUserByEmail = ''
                this.formMode = 'estate'
            }
            this.$emit('remove-user', email);
        },
        addEstate(user) {
            this.formMode = 'estate'
            this.selectedUserByEmail = user.email
        },
        saveEstate() {
            this.$emit('add-estate-to-user', {
                userEmail: this.selectedUserByEmail,
                estate: {
                    estate: this.estate,
                    cost: this.cost
                }
            });
        }
    }
}