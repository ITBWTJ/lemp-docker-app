import store from '../store';

export default {
    methods: {
        findUserById: (id) => {
            this.id = id;
            return store.state.posts.find((obj) => {
                return obj.id === this.id;
            })
        },

    }
}