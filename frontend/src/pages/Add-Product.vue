<template>
  <div class="row justify-center">
    <h4>Add Product</h4>
  </div>
  <div v-if="categoryState.data.length > 0" class="row">
    <div class="col-md-10 offset-md-1 q-mt-xl">
      <q-form @submit.prevent="onSubmit" class="q-gutter-md">
        <q-input
          filled
          label="Product Name *"
          type="text"
          v-model="body.name"
          lazy-rules
          :style="{ width: '300px' }"
          :rules="[(val) => (val && val.length > 0) || 'Please type something']"
        />

        <q-input
          filled
          type="textarea"
          label="Product Detail"
          v-model="body.detail"
          lazy-rules
          :style="{ width: '300px' }"
          :rules="[(val) => (val && val.length > 0) || 'Please type something']"
        />
        <q-input
          filled
          type="number"
          label="Product Price"
          v-model="body.price"
          lazy-rules
          :style="{ width: '300px' }"
          :rules="[(val) => (val && val.length > 0) || 'Please type something']"
        />
        <q-select
          v-model="body.categoryId"
          filled
          :options="selectOptions"
          label="Choose Category"
          map-options
          :style="{ width: '300px' }"
          emit-value
          lazy-rules
          :rules="[(val) => val || 'Please choose a category']"
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
import { defineComponent, onMounted, reactive, computed, ref } from "vue";
import { IPostProduct } from "src/interfaces";
import { useRouter, Router } from "vue-router";
import useProduct from "src/hooks/product";
import useCategory from "../hooks/category/index";
export default defineComponent({
  name: "AddProductPage",
  setup(props) {
    const { getCategories, categoryState } = useCategory();
    const { createProduct, postProductState } = useProduct();
    const router: Router = useRouter();
    const newref = ref("");
    const body = reactive({
      name: "",
      detail: "",
      price: undefined,
      categoryId: undefined,
    }) as IPostProduct;
    onMounted(async () => {
      await getCategories();
    });
    const onSubmit = () => {
      createProduct(body).then(async () => {
        if (postProductState.fetched === true) {
          await router.push("/");
        }
      });
    };
    return {
      body,
      onSubmit,
      selectOptions: computed(() =>
        categoryState.data.map((item: any) => {
          return {
            label: item.name,
            value: item.id,
          };
        })
      ),
      categoryState,
      newref,
    };
  },
});
</script>
