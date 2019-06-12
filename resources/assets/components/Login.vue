<template id="add-post">
  <v-container justify-center>
    <v-layout row wrap>
      <v-flex xs4 offset-xs4>
        <v-form ref="form" id="login-form" v-model="valid">
          <v-text-field
                  v-model="email"
                  placeholder="E-mail"
                  :error-messages="emailErrors"
                  required
          ></v-text-field>
          <v-text-field
                  v-model="password"
                  :counter="60"
                  placeholder="Password"
                  :append-icon="showPass ? 'visibility' : 'visibility_off'"
                  :append-icon-cb="() => (showPass = !showPass)"
                  :type="showPass ? 'text' : 'password'"
                  hint="At least 6 characters"
                  :error-messages="passwordErrors"
                  min="6"
                  required
          ></v-text-field>
          <v-btn @click="submit" type="button">
            Submit
          </v-btn>
        </v-form>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
  export default {
    data() {
      return {
        email: '',
        password: '',
        showPass: false,
        valid: false,
        emailErrors: [],
        passwordErrors: []
      };
    },
    methods: {
      submit: function () {
        const params = {
          email: this.email,
          password: this.password
        };

        if (this.$refs.form.validate()) {
          Axios.post('/auth/login', params).then( (response) => {
            const data = response.data;
            this.$store.commit('setToken', data.data.token);

            Axios.get('/api/users/me?token=' + this.$store.state.token, ).then((response) => {
                this.$store.commit('setUser', response.data.data);
                this.$router.push('/admin');
            })

          }).catch((error) => {
            console.log(error.response.data.error);
            this.emailErrors = this.passwordErrors = [];
            const errors = error.response.data.error;
            this.$store.commit('deleteToken');

            for (let key in error.response.data.error) {
              if (key === 'password') this.passwordErrors = Object.values(errors[key]);
              if (key === 'email') this.emailErrors = Object.values(errors[key]);
            }
          });
        }
      }
    }
  }
</script>

<style scoped>
  #login-form {
    padding-top: 20px
  }

</style>
