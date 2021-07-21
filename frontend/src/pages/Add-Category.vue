<template>
  <div class="row justify-center">
    <h4>Add Category</h4>
  </div>
  <div class="row">
    <div class="col-md-10 offset-md-1 q-mt-xl">
      <q-form @submit.prevent="onSubmit" class="q-gutter-md">
        <q-input
          filled
          label="Category Name *"
          type="text"
          v-model="body.name"
          lazy-rules
          :style="{ width: '300px' }"
          :rules="[(val) => (val && val.length > 0) || 'Please type something']"
        />
        <div>
          <q-btn label="Submit" type="submit" color="primary" />
        </div>
      </q-form>
    </div>
  </div>
</template>
<style></style>
<script lang="ts">
import { defineComponent, onMounted, reactive } from "vue";
import useCategory from "../hooks/category/index";
export default defineComponent({
  name: "AddCategoryPage",
  setup(props) {
    const { getCategories, postCategoryState, createCategory } = useCategory();
    const body = reactive({
      name: "",
    });
    onMounted(async () => {
      await getCategories();
    });
    const onSubmit = () => {
      createCategory(body).then(async () => {
        if (postCategoryState.fetched === true) {
          window.location.reload();
        }
      });
    };
    return {
      body,
      onSubmit,
      postCategoryState,
    };
  },
});
</script>
