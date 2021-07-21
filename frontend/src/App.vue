<template>
  <router-view />
</template>
<script lang="ts">
import {
  defineComponent,
  onBeforeMount,
} from "vue";
import useLogin from "src/hooks/auth/userLogin";
export default defineComponent({
  name: "App",
  setup() {
    const { done, isLoggedIn } = useLogin();
    onBeforeMount(() => {
      isLoggedIn();
    });
    /*     watchEffect(() => {
      if (done.value === true && route.path === "/login") {
        router.push("/");
      }
    }); */
    return { done };
  },
});
</script>
