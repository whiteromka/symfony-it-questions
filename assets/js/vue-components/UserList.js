const UserList = {
    template: `
        <div class="user-list">
            <h3>Мой список дел</h3>
            
            <!-- Поле для ввода нового дела -->
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
            <hr>
            
            <!-- Список дел -->
            <div class="todos">
                <user 
                    v-for="(user, index) in users" :key="user.id"
                    :user="user"
                    @remove-user="removeUser" 
                ></user>
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
        }
    },
    methods: {
        addUser() {
            if (this.userName.trim() !== "" && this.userLastName.trim() !== "" && this.email.trim() !== "") {
                let user = this.createNewUser(this.userName, this.userLastName, this.email)
                this.$emit('add-user', user); // Исправлено: используем $emit
                // Очищаем поля после добавления
                this.userName = '';
                this.userLastName = '';
                this.email = '';
            } else {
                alert('Поля не могут быть пустыми!')
            }
        },
        createNewUser(name, userLastName, email) {
            return {
                id: Date.now(), // Добавляем ID для ключа
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
            }
        },
        removeUser(email) {
            this.$emit('remove-user', email);
        }
    }
}