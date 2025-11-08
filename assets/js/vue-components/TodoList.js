const TodoList = {
    template: `
        <div class="todo-list">
            <h3>Мой список дел</h3>
            
            <!-- Поле для ввода нового дела -->
            <div class="input-group mb-3">
                <input 
                    v-model="newTodo" 
                    @keyup.enter="addTodo"
                    type="text" 
                    class="form-control" 
                    placeholder="Введите новое дело..."
                >
                <button @click="addTodo()" class="btn btn-primary">
                    Добавить
                </button>
            </div>
            
            <!-- Список дел -->
            <div class="todos">
                <!-- Рендерим компонент TodoItem для каждого дела -->
                <todo-item 
                    v-for="(todo, index) in todos" :key="index"
                    :text="todo"
                ></todo-item>
            </div>
        </div>
    `,

    components: {
        'todo-item': TodoItem
    },
    data() {
        return {
            todos: [
                "Изучить Vue.js",
                "Разобраться с Symfony"
            ],
            newTodo: ""
        }
    },
    methods: {
        addTodo() {
            if (this.newTodo.trim() !== "") {
                this.todos.push(this.newTodo);
                this.newTodo = "";
            }
        }
    }

}