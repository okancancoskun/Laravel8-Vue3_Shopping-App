import {
  IProduct,
  IBaseState,
  IPostProduct,
  IUpdateProduct,
} from "../../interfaces";
import { reactive, toRefs } from "vue";
import { api } from "src/boot/axios";
interface IProductsResponse {
  current_page: number;
  data: IProduct[];
  first_page_url: string;
  from: number;
  last_page: number;
  last_page_url: string;
  links: any[];
  next_page_url: string;
  path: string;
  per_page: number;
  prev_page_url?: string;
  to: number;
  total: number;
}

export interface IState extends IBaseState<IProductsResponse> {
  lastPage?: number;
  currentPage?: number;
  minPage?: number;
}

export interface IProductState extends IBaseState<IProduct> {}
export interface IPostState extends IBaseState<IProduct> {}

const productsState = reactive<IState>({
  fetching: false,
  fetched: false,
  data: <IProductsResponse>{},
  error: <any>{},
  lastPage: undefined,
  currentPage: undefined,
  minPage: undefined,
});

const productState = reactive<IProductState>({
  fetching: false,
  fetched: false,
  data: <IProduct>{},
  error: <any>{},
});

const postState = reactive<IPostState>({
  fetching: false,
  fetched: false,
  data: <IProduct>{},
  error: <any>{},
});

export default function useProducts() {
  const getProducts = async (page?: number) => {
    productsState.fetching = true;
    try {
      const res = await api.get(`/product?page=${page}`);
      if (res.status === 200) {
        productsState.fetched = true;
        productsState.lastPage = res.data.last_page;
        productsState.currentPage = res.data.current_page;
        productsState.data = res.data;
      }
    } catch (e) {
      productsState.error = e;
    } finally {
      productsState.fetching = false;
    }
  };

  const getProduct = async (id: number) => {
    productState.fetching = true;
    try {
      const res = await api.get(`/product/${id}`);
      if (res.status === 200) {
        productState.fetched = true;
        productState.data = res.data;
      }
    } catch (error) {
      productState.error = error;
    } finally {
      productState.fetching = false;
    }
  };

  const createProduct = async (data: IPostProduct) => {
    postState.fetching = true;
    try {
      const res = await api({
        url: "/product/create",
        method: "POST",
        data: { ...data },
      });
      if (res.status === 201) {
        postState.fetched = true;
        postState.data = res.data;
      }
    } catch (error) {
      postState.error = error;
    } finally {
      postState.fetching = false;
    }
  };

  const updateProduct = async (data: IUpdateProduct, id: number) => {
    postState.fetching = true;
    try {
      const res = await api({
        url: `/product/${id}`,
        method: "PUT",
        data: { ...data },
      });
      if (res.status === 200) {
        postState.fetched = true;
      }
    } catch (error) {
      postState.error = error;
    } finally {
      postState.fetching = false;
    }
  };

  const deleteProduct = async (id: number) => {
    postState.fetching = true;
    try {
      const res = await api.delete(`/product/${id}`);
      if (res.status === 204) {
        postState.fetched = true;
      }
    } catch (error) {
      postState.error = error;
    } finally {
      postState.fetching = false;
    }
  };
  return {
    getProducts,
    productsState: productsState,
    productState: productState,
    postProductState: postState,
    getProduct,
    createProduct,
    updateProduct,
    deleteProduct,
  };
}
