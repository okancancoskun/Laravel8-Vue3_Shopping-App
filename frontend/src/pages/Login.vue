<template>
  <div class="row justify-center">
    <h4>Login</h4>
  </div>
  <div class="row">
    <div class="col-md-10 offset-md-1 q-mt-xl">
      <q-form @submit.prevent="onSubmit" class="q-gutter-md">
        <q-input
          filled
          label="Email *"
          hint="Your Email"
          type="textbox"
          v-model="body.email"
          lazy-rules
          v-bind:style="{ width: '300px' }"
          :rules="[(val) => (val && val.length > 0) || 'Please type something']"
        />

        <q-input
          filled
          type="password"
          label="Password *"
          v-model="body.password"
          lazy-rules
          v-bind:style="{ width: '300px' }"
          :rules="[(val) => (val && val.length > 0) || 'Please type something']"
        />

        <div>
          <q-btn label="Submit" type="submit" color="primary" />
        </div>
      </q-form>
    </div>
  </div>
</template>
<style>
.my-card {
  width: 100%;
  max-width: 250px;
}
</style>
<script lang="ts">
import { defineComponent, reactive, watchEffect } from "vue";
import { ILogin } from "src/interfaces";
import { useRouter, Router } from "vue-router";
import useLogin from "src/hooks/auth/userLogin";
export default defineComponent({
  name: "LoginPage",
  setup(props) {
    const { login, done, isLoggedIn } = useLogin();
    const router: Router = useRouter();
    const body = reactive<ILogin>({
      email: "",
      password: "",
    });
    const onSubmit: Function = (): void => {
      login(body);
    };
    console.log("STATE=>", done);
    watchEffect((): void => {
      if (done.value === true) {
        router.push("/");
      }
    });
    return { body, login, done, onSubmit };
  },
});
</script>
