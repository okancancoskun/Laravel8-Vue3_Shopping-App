<template>
  <div class="row justify-center">
    <h4>Register</h4>
  </div>
  <div class="row">
    <div class="col-md-10 offset-md-1 q-mt-xl">
      <q-form @submit.prevent="onSubmit" class="q-gutter-md">
        <q-input
          filled
          label="Name *"
          hint="Your Name"
          type="textbox"
          v-model="body.name"
          lazy-rules
          v-bind:style="{ width: '300px' }"
          :rules="[(val) => (val && val.length > 0) || 'Please type something']"
        />
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
import { IRegister } from "src/interfaces";
import { useRouter, Router } from "vue-router";
import useRegister from "src/hooks/auth/userRegister";
import useLogin from "src/hooks/auth/userLogin";
export default defineComponent({
  name: "RegisterPage",
  setup(props) {
    const { register, state } = useRegister();
    const { done } = useLogin();
    const router: Router = useRouter();
    const body = reactive<IRegister>({
      email: "",
      name: "",
      password: "",
    });
    const onSubmit: Function = (): void => {
      register(body).then(async () => {
        if (state.fetched) {
          await router.push("/login");
        }
      });
    };
    watchEffect((): void => {
      if (done.value === true) {
        router.push("/");
      }
    });
    return { body, done, onSubmit };
  },
});
</script>
